<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FireStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoutingController extends Controller
{
    /**
     * Find the nearest fire station and get a route from a fire to it.
     */
    public function getRouteToNearestStation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180',
        ]);

        $fireLat = (float) $request->input('lat');
        $fireLon = (float) $request->input('lon');

        // --- Find Nearest Fire Station using Haversine Formula in SQL ---
        // This is much more efficient than fetching all stations and calculating in PHP.
        // Assumes your FireStation model uses a table named 'fire_stations' with 'lat' and 'lon' columns.
        $nearestStation = FireStation::select(
            'fire_stations.*',
            DB::raw("
                ( 6371 * acos( cos( radians(?) ) *
                  cos( radians( lat ) )
                  * cos( radians( lon ) - radians(?)
                  ) + sin( radians(?) ) *
                  sin( radians( lat ) ) )
                ) AS distance")
        )
        ->setBindings([$fireLat, $fireLon, $fireLat])
        ->orderBy('distance', 'asc')
        ->first();

        if (!$nearestStation) {
            return response()->json(['success' => false, 'error' => 'No fire stations found in the database.'], 404);
        }

        // --- Get Route from OSRM (Open Source Routing Machine) ---
        // OSRM is a free and open-source routing engine. We use its public demo server.
        $stationLat = $nearestStation->lat;
        $stationLon = $nearestStation->lon;
        
        $osrmUrl = "http://router.project-osrm.org/route/v1/driving/{$fireLon},{$fireLat};{$stationLon},{$stationLat}?overview=full&geometries=geojson";

        try {
            $response = Http::timeout(15)->get($osrmUrl);

            if ($response->successful() && $response->json('code') === 'Ok') {
                $routeData = $response->json('routes')[0] ?? null;

                if (!$routeData) {
                    return response()->json(['success' => false, 'error' => 'Could not find a valid route.'], 404);
                }

                return response()->json([
                    'success' => true,
                    'station' => $nearestStation,
                    'route' => [
                        'geometry' => $routeData['geometry'], // This is the GeoJSON line for the map
                        'duration' => round($routeData['duration'] / 60), // minutes
                        'distance' => round($routeData['distance'] / 1000, 1), // kilometers
                    ]
                ]);
            }

            Log::error('OSRM API failed', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['success' => false, 'error' => 'Routing service failed.'], 500);

        } catch (\Exception $e) {
            Log::error('Routing request failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'Could not connect to the routing service.'], 500);
        }
    }
}