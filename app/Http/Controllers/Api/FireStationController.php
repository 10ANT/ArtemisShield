<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FireStation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // <-- Add Request
use Illuminate\Support\Facades\DB; // <-- Add DB

class FireStationController extends Controller
{
    public function index(Request $request): JsonResponse // <-- Add Request
    {
        // --- NEW: Bounding Box Logic ---
        $query = FireStation::query();

        if ($request->has('bbox')) {
            $bbox = explode(',', $request->input('bbox'));
             if (count($bbox) === 4) {
                // IMPORTANT: Ensure your lat/lon columns are indexed
                $minLon = (float)$bbox[0];
                $minLat = (float)$bbox[1];
                $maxLon = (float)$bbox[2];
                $maxLat = (float)$bbox[3];

                $query->whereBetween('lon', [$minLon, $maxLon])
                      ->whereBetween('lat', [$minLat, $maxLat]);
            }
        } else {
             return response()->json(['type' => 'FeatureCollection', 'features' => []]);
        }

        $fireStations = $query->limit(500)->get();
        // --- END NEW ---


        $features = [];
        foreach ($fireStations as $station) {
            // Your existing logic for formatting properties is fine...
            $properties = $station->toArray(); 
            // ... etc ...
            // (The rest of your foreach loop remains the same)

            unset($properties['lat'], $properties['lon'], $properties['id']); 
            if (isset($properties['all_tags']) && is_string($properties['all_tags'])) {
                $properties['all_tags'] = json_decode($properties['all_tags'], true);
            }
            $features[] = [
                'type'       => 'Feature',
                'geometry'   => [
                    'type'        => 'Point',
                    'coordinates' => [(float)$station->lon, (float)$station->lat],
                ],
                'properties' => $properties,
            ];
        }

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}