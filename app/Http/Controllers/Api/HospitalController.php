<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital; // <-- Import the Hospital model

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        // Start a query on the Hospital model
        $query = Hospital::query();

        // Your frontend sends the map's current view (a "bounding box").
        // We will use this to only fetch hospitals visible on the screen.
        // This is very efficient!
        if ($request->has('bbox')) {
            list($minLng, $minLat, $maxLng, $maxLat) = explode(',', $request->input('bbox'));

            $query->whereBetween('longitude', [$minLng, $maxLng])
                  ->whereBetween('latitude', [$minLat, $maxLat]);
        }
        
        // Add a limit to prevent sending too much data at once on high zoom levels
        $hospitals = $query->limit(2000)->get();

        // Convert the database results into the GeoJSON format that Leaflet needs.
        $geoJsonData = $hospitals->map(function ($hospital) {
            return [
                'type' => 'Feature',
                'properties' => $hospital,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        $hospital->longitude,
                        $hospital->latitude,
                    ],
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $geoJsonData,
        ]);
    }
}