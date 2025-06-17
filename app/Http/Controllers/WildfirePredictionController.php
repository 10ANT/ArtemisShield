<?php

namespace App\Http\Controllers;

use App\Services\AzureMLClassificationService;
use App\Services\AzureMLService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class WildfirePredictionController extends Controller
{
    // ... all of your other existing methods like predictIntensity, showForm, etc. remain here ...

    public function predictIntensity(Request $request)
    {
        Log::info('Received AI intensity prediction request.', $request->all());

        $validator = Validator::make($request->all(), [
            'latitude'   => 'required|numeric|between:-90,90',
            'longitude'  => 'required|numeric|between:-180,180',
            'brightness' => 'required|numeric',
            'confidence' => 'required|numeric',
            'bright_t31' => 'required|numeric',
            'daynight'   => 'required|string|in:D,N',
        ]);

        if ($validator->fails()) {
            $errorMessage = 'Missing or invalid input data required for prediction.';
            Log::warning('AI prediction validation failed.', [
                'message' => $errorMessage,
                'errors' => $validator->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json(['message' => $errorMessage, 'errors' => $validator->errors()], 422);
        }

        try {
            $azureMLService = new AzureMLService();
            $prediction = $azureMLService->getPrediction($validator->validated());

            if ($prediction !== null) {
                Log::info('Successfully returned AI prediction.', ['prediction' => $prediction]);
                return response()->json(['predicted_frp' => $prediction]);
            } else {
                Log::error('AI prediction returned null from service.');
                return response()->json(['message' => 'The model endpoint returned a null or invalid response.'], 502);
            }
        } catch (\Exception $e) {
            Log::error('The AI Prediction service failed to initialize or execute.', [
                'exception_message' => $e->getMessage()
            ]);
            return response()->json(['message' => 'The prediction service is currently unavailable.'], 503);
        }
    }


    /**
     * NEW METHOD: Orchestrates getting weather and a classification
     * to predict the potential fire spread direction and size.
     */
    public function predictSpread(Request $request)
    {
        Log::info('Received AI spread prediction request.', $request->all());

        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'fire_properties' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Missing required fire data.', 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();
        $lat = $validated['latitude'];
        $lon = $validated['longitude'];
        $properties = $validated['fire_properties'];

        try {
            // 1. Get Live Weather Data for wind direction
            $weatherApiKey = config('services.openweather.api_key');
            $weatherResponse = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $weatherApiKey,
                'units' => 'metric'
            ]);

            if (!$weatherResponse->successful()) {
                Log::error('OpenWeatherMap API request failed.', ['status' => $weatherResponse->status(), 'body' => $weatherResponse->body()]);
                return response()->json(['message' => 'Could not retrieve live weather data for prediction.'], 502);
            }
            $windDirection = $weatherResponse->json()['wind']['deg'] ?? 0;

            // 2. Get the AI's prediction for the fire's potential class
            $classificationService = new AzureMLClassificationService();
            $predictedClass = $classificationService->getSpreadPrediction($properties);

            if ($predictedClass === null) {
                 return response()->json(['message' => 'The AI model could not predict the fire spread class.'], 502);
            }
            
            // === HEURISTIC OVERRIDE LOGIC ===
            // 3. Determine the fire's ACTUAL class based on its current acreage
            $currentAcres = $properties['poly_GISAcres'] ?? 0;
            $currentClass = $this->acresToClass($currentAcres);

            // 4. Choose the more severe class between reality and the AI's prediction
            $finalClass = $this->determineFinalClass($currentClass, $predictedClass);

            Log::info('Successfully generated spread prediction.', [
                'current_acres' => $currentAcres,
                'class_from_acres' => $currentClass,
                'class_from_ai' => $predictedClass,
                'final_class_used' => $finalClass,
                'wind_direction' => $windDirection
            ]);

            // 5. Return combined results to the frontend
            return response()->json([
                'success' => true,
                'wind_direction' => $windDirection,
                'spread_class' => $finalClass
            ]);

        } catch (Exception $e) {
            Log::error('The AI Spread Prediction service failed.', [
                'exception_message' => $e->getMessage()
            ]);
            return response()->json(['message' => 'The prediction service is currently unavailable.'], 503);
        }
    }

    /**
     * Helper function to convert acres to a standard fire size class.
     * @param float $acres
     * @return string
     */
    private function acresToClass(float $acres): string
    {
        if ($acres <= 0.25) return 'A';
        if ($acres <= 9.9) return 'B';
        if ($acres <= 99.9) return 'C';
        if ($acres <= 299.9) return 'D';
        if ($acres <= 999.9) return 'E';
        if ($acres <= 4999.9) return 'F';
        return 'G';
    }

    /**
     * Helper function to determine which fire class is more severe.
     * @param string $classA
     * @param string $classB
     * @return string
     */
    private function determineFinalClass(string $classA, string $classB): string
    {
        // The further down the alphabet, the more severe the fire class.
        // We can simply compare them alphabetically.
        return $classA > $classB ? $classA : $classB;
    }
}