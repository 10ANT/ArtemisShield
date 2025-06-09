<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ApiController extends Controller
{
    public function getFireData(Request $request)
    {
        $layer = $request->get('layer', 'viirs_24');
        
        switch ($layer) {
            case 'viirs_24':
                return $this->getVIIRS24Data();
            case 'viirs_48':
                return $this->getVIIRS48Data();
            case 'modis_24':
                return $this->getMODIS24Data();
            case 'active_incidents':
                return $this->getActiveIncidents();
            default:
                return response()->json(['error' => 'Invalid layer'], 400);
        }
    }

    private function getVIIRS24Data()
    {
        // Mock data for VIIRS 24-hour fires
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [-118.2437, 34.0522]
                    ],
                    'properties' => [
                        'brightness' => 345.2,
                        'confidence' => 85,
                        'acq_date' => '2025-06-09',
                        'acq_time' => '1234'
                    ]
                ]
            ]
        ]);
    }

    private function getVIIRS48Data()
    {
        // Similar structure for 48-hour data
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => []
        ]);
    }

    private function getMODIS24Data()
    {
        // MODIS 24-hour data
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => []
        ]);
    }

    private function getActiveIncidents()
    {
        $wildfires = \App\Models\Wildfire::where('status', 'active')->get();
        
        $features = $wildfires->map(function ($fire) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$fire->longitude, $fire->latitude]
                ],
                'properties' => [
                    'name' => $fire->name,
                    'severity' => $fire->severity,
                    'started_at' => $fire->started_at,
                    'affected_area' => $fire->affected_area
                ]
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }
}