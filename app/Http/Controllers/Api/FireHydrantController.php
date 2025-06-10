<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FireHydrant; // Import your FireHydrant model

class FireHydrantController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all fire hydrants from the database
        // You might want to add pagination or spatial filtering here for large datasets
        $hydrants = FireHydrant::all();

        $features = [];
        foreach ($hydrants as $hydrant) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$hydrant->lon, (float)$hydrant->lat]
                ],
                'properties' => [
                    'osm_id' => $hydrant->osm_id,
                    'fire_hydrant_type' => $hydrant->fire_hydrant_type,
                    'color' => $hydrant->color ?? $hydrant->colour, // Prefer 'color' if present, else 'colour'
                    'all_tags' => $hydrant->all_tags, // The full tags array
                    // Add other properties you want to display on the map's popups
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