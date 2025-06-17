<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AmbeeController extends Controller
{
    // ... (getFireDataByLatLng and getFireRiskDataByLatLng methods remain the same) ...

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
     * **NEW:** Proxies an image classification request to an Azure Function.
     * Note: This assumes the Azure function can accept a Base64 string.
     * The user's curl example uses a URL, but for a dynamic web app,
     * sending Base64 data is a more direct approach.
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

        $imageData = $request->input('image_b64');
        
        // This is a placeholder for the logic your Azure function might use.
        // We are sending a payload with a base64 string. Your function might
        // need a different key like 'image_data' instead of 'image_url'.
        $payload = [
            'image_url' => $imageData 
        ];

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("{$functionUrl}?code={$functionCode}", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Azure Function request failed.', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return response()->json(['error' => 'Image analysis service failed.'], $response->status());

        } catch (\Exception $e) {
            Log::error('Exception while calling Azure Function.', ['message' => $e->getMessage()]);
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
            $response = Http::withHeaders(['x-api-key' => $apiKey])->timeout(15)->get($apiUrl);
            if ($response->successful()) return $response->json();
            $errorMsg = $response->json('message', 'Failed to retrieve data from Ambee.');
            return response()->json(['error' => $errorMsg], $response->status());
        } catch (\Exception $e) {
            Log::error('Exception calling Ambee API.', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}