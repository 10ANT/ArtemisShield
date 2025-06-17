<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WildfireRiskController extends Controller
{
    /**
     * Fetches point data from the Wildfire Risk API.
     * Acts as a proxy to avoid CORS issues and hide the external API from the client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPointData(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180',
        ]);

        $lat = $request->input('lat');
        $lon = $request->input('lon');

        $apiUrl = "https://wildfirerisk.org/api/rc/v1/search/point?lat={$lat}&lon={$lon}";

        try {
            $response = Http::timeout(15)->get($apiUrl);

            if ($response->successful()) {
                return $response->json();
            }

            // If the request failed, log the error and return a generic error message
            Log::error('Wildfire Risk API request failed.', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json(['error' => 'Failed to retrieve data from the external service.'], $response->status());

        } catch (\Exception $e) {
            Log::error('Exception while calling Wildfire Risk API.', [
                'message' => $e->getMessage()
            ]);
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}