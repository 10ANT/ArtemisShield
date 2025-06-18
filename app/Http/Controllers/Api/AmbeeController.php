<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;

class AmbeeController extends Controller
{
    /**
     * Fetches LIVE fire data from the Ambee API.
     */
    public function getFireDataByLatLng(Request $request)
    {
        return $this->proxyAmbeeRequest($request, 'https://api.ambeedata.com/fire/latest/by-lat-lng');
    }

    /**
     * Fetches PREDICTED fire risk data from the Ambee API.
     */
    public function getFireRiskDataByLatLng(Request $request)
    {
        return $this->proxyAmbeeRequest($request, 'https://api.ambeedata.com/fire/risk/by-lat-lng');
    }

    /**
     * Receives a Base64 image, decodes it, and sends it as a file upload
     * to the existing 'classify' Azure Function.
     */
    public function classifyImage(Request $request)
    {
        $request->validate([
            'image_b64' => 'required|string',
        ]);

        $functionUrl = config('services.azure.function_url');
        $functionCode = config('services.azure.function_code');

        if (!$functionUrl || !$functionCode) {
            Log::error('Azure Function URL or Code is not set.');
            return response()->json(['error' => 'Server configuration error for image analysis.'], 500);
        }

        try {
            // 1. Decode the Base64 string received from the frontend
            $b64string = $request->input('image_b64');
            
            // Strip the "data:image/..." header if it exists
            if (strpos($b64string, ',') !== false) {
                list(, $b64string) = explode(',', $b64string, 2);
            }
            
            $imageData = base64_decode($b64string);

            if ($imageData === false) {
                Log::error('Failed to decode Base64 image string.');
                return response()->json(['error' => 'Invalid image data received.'], 400);
            }

            // 2. Programmatically change the URL to point to the 'classify' (file upload) endpoint
            // This avoids needing to change the .env file.
            $uploadUrl = str_replace('/classify_url', '/classify', $functionUrl);

            // 3. Send a multipart/form-data request (a file upload) to the Azure Function
            $response = Http::asMultipart()
                ->timeout(90) // Keep the generous timeout for the AI model
                ->attach(
                    'image',      // The form field name the Python code expects in req.files
                    $imageData,   // The raw image bytes, NOT the base64 string
                    'tile.jpg'    // A filename is required for multipart uploads
                )
                ->post("{$uploadUrl}?code={$functionCode}");

            if ($response->successful()) {
                return $response->json();
            }

            // Log the error from Azure for easier debugging
            Log::error('Azure Function (classify) request failed.', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return response()->json(['error' => 'Image analysis service returned an error.'], $response->status());

        } catch (ConnectionException $e) {
            Log::error('ConnectionException while calling Azure Function.', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Could not connect to the image analysis service. It may be timed out.'], 504); // 504 Gateway Timeout
        } catch (\Exception $e) {
            Log::error('Exception while preparing request for Azure Function.', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'An unexpected error occurred during image analysis.'], 500);
        }
    }

    private function proxyAmbeeRequest(Request $request, string $apiUrlTemplate)
    {
        $request->validate(['lat' => 'required|numeric', 'lng' => 'required|numeric']);
        $apiKey = config('services.ambee.key');
        if (!$apiKey) {
            Log::error('Ambee API Key is not set.');
            return response()->json(['error' => 'Server configuration error.'], 500);
        }
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $apiUrl = "{$apiUrlTemplate}?lat={$lat}&lng={$lng}";
        try {
            $response = Http::withHeaders(['x-api-key' => $apiKey])->timeout(20)->get($apiUrl);
            if ($response->successful()) return $response->json();
            $errorMsg = $response->json('message', 'Failed to retrieve data from Ambee.');
            return response()->json(['error' => $errorMsg], $response->status());
        } catch (\Exception $e) {
            Log::error('Exception calling Ambee API.', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}