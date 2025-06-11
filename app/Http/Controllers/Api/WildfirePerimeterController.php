<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WildfirePerimeterController extends Controller
{
    /**
     * Fetch the latest wildfire perimeters from the WFIGS ArcGIS service.
     */
    public function index()
    {
        // This is the official API endpoint for Year-to-Date Interagency Perimeters
        $apiUrl = 'https://services3.arcgis.com/T4QMspbfLg3qTGWY/arcgis/rest/services/WFIGS_Interagency_Perimeters_YearToDate/FeatureServer/0/query';

        $queryParams = [
            'where' => '1=1',                // Get all features
            'outFields' => '*',              // Get all available data fields
            'f' => 'geojson',                // Request the format as GeoJSON
            'returnGeometry' => 'true',      // Ensure geometry is always returned
            'orderByFields' => 'poly_DateCurrent DESC' // Get the most recent first
        ];

        try {
            $response = Http::timeout(30)->get($apiUrl, $queryParams);

            if ($response->successful()) {
                // Return the GeoJSON directly to our frontend
                return $response->json();
            }

            // Log the error if the external API call fails
            Log::error('Failed to fetch wildfire perimeters', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json(['error' => 'Failed to fetch data from the wildfire service.'], 502); // 502 Bad Gateway

        } catch (\Exception $e) {
            Log::error('Exception while fetching wildfire perimeters: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while contacting the wildfire service.'], 500);
        }
    }
}