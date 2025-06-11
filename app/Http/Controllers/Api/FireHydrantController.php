<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FireHydrant;
use Illuminate\Support\Facades\DB; // <-- Import DB Facade

class FireHydrantController extends Controller
{
    public function index(Request $request)
    {
        // --- NEW: Bounding Box Logic ---
        $query = FireHydrant::query();

        if ($request->has('bbox')) {
            $bbox = explode(',', $request->input('bbox'));
            if (count($bbox) === 4) {
                // IMPORTANT: Ensure your lat/lon columns are indexed in the database for performance!
                // ALTER TABLE fire_hydrants ADD INDEX(lat);
                // ALTER TABLE fire_hydrants ADD INDEX(lon);
                $minLon = (float)$bbox[0];
                $minLat = (float)$bbox[1];
                $maxLon = (float)$bbox[2];
                $maxLat = (float)$bbox[3];

                $query->whereBetween('lon', [$minLon, $maxLon])
                      ->whereBetween('lat', [$minLat, $maxLat]);
            }
        } else {
            // To prevent accidental full loads, return nothing if no bbox is provided.
             return response()->json(['type' => 'FeatureCollection', 'features' => []]);
        }
        
        // Add a limit to prevent overwhelming results even within a bbox
        $hydrants = $query->limit(1000)->get();
        // --- END NEW ---


        $features = [];
        foreach ($hydrants as $hydrant) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$hydrant->lon, (float)$hydrant->lat]
                ],
                // Properties remain the same
                'properties' => [
                    'osm_id' => $hydrant->osm_id,
                    'fire_hydrant_type' => $hydrant->fire_hydrant_type,
                    'color' => $hydrant->color ?? $hydrant->colour,
                    'all_tags' => $hydrant->all_tags,
                    'emergency' => $hydrant->emergency,
                    'operator' => $hydrant->operator,
                    'addr_street' => $hydrant->addr_street,
                    'addr_city' => $hydrant->addr_city,
                    'addr_state' => $hydrant->addr_state,
                    'fire_hydrant_position' => $hydrant->fire_hydrant_position,
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}