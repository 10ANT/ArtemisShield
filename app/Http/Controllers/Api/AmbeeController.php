<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AmbeeController extends Controller
{
    /**
     * Fetches fire data from the Ambee API based on latitude and longitude.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFireDataByLatLng(Request $request)
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

        $apiUrl = "https://api.ambeedata.com/fire/latest/by-lat-lng?lat={$lat}&lng={$lng}";

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'Content-type' => 'application/json'
            ])->timeout(15)->get($apiUrl);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Ambee API request failed.', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // Pass Ambee's error message through if possible
            $errorMsg = $response->json('message', 'Failed to retrieve data from Ambee.');
            return response()->json(['error' => $errorMsg], $response->status());

        } catch (\Exception $e) {
            Log::error('Exception while calling Ambee API.', [
                'message' => $e->getMessage()
            ]);
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}