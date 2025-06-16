<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AmbeeController extends Controller
{
    /**
     * Fetches LIVE fire data from the Ambee API.
     */
    public function getFireDataByLatLng(Request $request)
    {
        return $this->proxyRequest($request, 'https://api.ambeedata.com/fire/latest/by-lat-lng');
    }

    /**
     * Fetches PREDICTED fire risk data from the Ambee API.
     */
    public function getFireRiskDataByLatLng(Request $request)
    {
        return $this->proxyRequest($request, 'https://api.ambeedata.com/fire/risk/by-lat-lng');
    }

    /**
     * Generic proxy function to handle requests to the Ambee API.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $apiUrlTemplate
     * @return \Illuminate\Http\JsonResponse
     */
    private function proxyRequest(Request $request, string $apiUrlTemplate)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $apiKey = config('services.ambee.key');
        if (!$apiKey) {
            Log::error('Ambee API Key is not set in services config.');
            return response()->json(['error' => 'Server configuration error.'], 500);
        }

        $lat = $request->input('lat');
        $lng = $request->input('lng');

        $apiUrl = "{$apiUrlTemplate}?lat={$lat}&lng={$lng}";

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'Content-type' => 'application/json'
            ])->timeout(15)->get($apiUrl);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Ambee API request failed.', [
                'url' => $apiUrl,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            $errorMsg = $response->json('message', 'Failed to retrieve data from Ambee.');
            return response()->json(['error' => $errorMsg], $response->status());

        } catch (\Exception $e) {
            Log::error('Exception while calling Ambee API.', [
                'url' => $apiUrl,
                'message' => $e->getMessage()
            ]);
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}