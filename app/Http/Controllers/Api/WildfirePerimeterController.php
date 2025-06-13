<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use \DateTime;

class WildfirePerimeterController extends Controller
{
    /**
     * Fetch wildfire perimeters and then filter them on the server side.
     * NOTE: This approach is less efficient than filtering at the API level, as it
     * requires downloading the entire dataset first, which can be slow and memory-intensive.
     * The recommended approach is to let the API handle the filtering via its 'where' parameter.
     */
    public function index(Request $request)
    {
        $apiUrl = 'https://services3.arcgis.com/T4QMspbfLg3qTGWY/arcgis/rest/services/WFIGS_Interagency_Perimeters_YearToDate/FeatureServer/0/query';

        // Step 1: Always fetch ALL data from the API.
        $queryParams = [
            'where' => '1=1', // Get all records
            'outFields' => '*',
            'f' => 'geojson',
            'returnGeometry' => 'true',
            'orderByFields' => 'poly_DateCurrent DESC'
        ];

        try {
            $response = Http::timeout(60)->get($apiUrl, $queryParams);
            $data = $response->json();

            // Ensure the initial fetch was successful and we have features to process.
            if (!$response->successful() || !isset($data['features'])) {
                Log::error('Failed to fetch the full wildfire dataset from the source API.', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['error' => 'Failed to fetch data from the wildfire service.'], 502);
            }

            // Step 2: Filter the results on our server based on request parameters.
            $allFeatures = $data['features'];
            $filteredFeatures = $allFeatures; // Start with all features and narrow down.

            // A. Apply the "discovery_date" filter
            if ($request->has('discovery_date') && !empty($request->input('discovery_date'))) {
                try {
                    $filterDate = new DateTime($request->input('discovery_date'));
                    $filterDate->setTime(0, 0, 0);
                    $filterTimestamp = $filterDate->getTimestamp() * 1000;

                    $filteredFeatures = array_filter($filteredFeatures, function ($feature) use ($filterTimestamp) {
                        $properties = $feature['properties'];
                        // Keep the feature only if its discovery date is on or after the filter date
                        return isset($properties['attr_FireDiscoveryDateTime']) && $properties['attr_FireDiscoveryDateTime'] >= $filterTimestamp;
                    });
                } catch (\Exception $e) {
                    Log::warning('Invalid discovery_date format for server-side filtering: ' . $request->input('discovery_date'));
                }
            }
            
            // B. Apply the "hide_contained" filter
            if ($request->input('hide_contained') === 'true') {
                $filteredFeatures = array_filter($filteredFeatures, function ($feature) {
                    $properties = $feature['properties'];
                    // Keep the feature only if BOTH containment and fire-out dates are NULL
                    return !isset($properties['attr_ContainmentDateTime']) && !isset($properties['attr_FireOutDateTime']);
                });
            }

            // Step 3: Return the filtered data in the original GeoJSON structure.
            // array_values is used to reset array keys for proper JSON array conversion.
            $data['features'] = array_values($filteredFeatures);
            
            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Exception during server-side filtering of wildfire perimeters: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing wildfire data.'], 500);
        }
    }
}