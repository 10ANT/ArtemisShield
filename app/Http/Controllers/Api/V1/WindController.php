<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WindController extends Controller
{
    /**
     * Fetch and return GFS wind data.
     *
     * This method fetches GFS wind data required by the leaflet-velocity library.
     * It uses a public, static JSON file as a data source. For truly live data,
     * this source URL would need to point to a service that regularly updates GFS data in this format.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGfsData()
    {
        $cacheKey = 'gfs_wind_data';
        // Cache the data for 3 hours (10800 seconds) to avoid excessive requests to the source.
        $cacheDuration = 10800; 

        try {
            // The 'remember' method gets the item from the cache if it exists,
            // otherwise, it executes the Closure and stores the result in the cache.
            $windData = Cache::remember($cacheKey, $cacheDuration, function () {
                // This is a standard demo file for wind data. You can replace this URL
                // with any other source that provides GFS data in the leaflet-velocity JSON format.
                $dataSourceUrl = 'https://onaci.github.io/leaflet-velocity/wind-global.json';
                
                Log::info('Fetching new GFS wind data from source.', ['url' => $dataSourceUrl]);

                $response = Http::timeout(20)->get($dataSourceUrl);

                if ($response->successful()) {
                    Log::info('Successfully fetched GFS wind data.');
                    // The response body should be a JSON array, so we decode it.
                    return $response->json();
                }

                // If the request failed, log the error and return null.
                // Returning null will prevent caching a failed response.
                Log::error('Failed to fetch GFS wind data.', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return null;
            });

            // If the data is null (either from a failed fetch or cached failure), return an error.
            if (!$windData) {
                return response()->json(['error' => 'Could not retrieve wind data from the source.'], 502); // 502 Bad Gateway
            }

            // Return the data as a JSON response.
            return response()->json($windData);

        } catch (\Exception $e) {
            Log::critical('An exception occurred while fetching GFS wind data.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'An internal server error occurred.'], 500);
        }
    }
}