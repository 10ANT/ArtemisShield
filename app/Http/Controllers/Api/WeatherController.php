<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    /**
     * Fetch weather for a specific point from OpenWeatherMap.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getWeatherForPoint(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        $apiKey = config('services.openweather.api_key');

        if (!$apiKey || $apiKey === 'YOUR_FALLBACK_KEY') {
            return response()->json(['error' => 'OpenWeatherMap API key is not configured.'], 500);
        }

        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat' => $validated['lat'],
            'lon' => $validated['lon'],
            'appid' => $apiKey,
            'units' => 'metric' // Use metric for Celsius
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        // Forward the error from the weather service if possible
        return response()->json($response->json(), $response->status());
    }
}