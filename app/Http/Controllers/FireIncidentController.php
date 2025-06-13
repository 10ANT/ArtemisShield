<?php

namespace App\Http\Controllers;

use App\Models\FireIncident;
use App\Services\NasaFirmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FireIncidentController extends Controller
{
    /**
     * Display the firefighter dashboard.
     * This method will also trigger the data fetch if needed.
     */
    public function dashboard(NasaFirmsService $firmsService)
    {
        // To prevent API abuse, only fetch new data once every 30 minutes.
        // The 'Cache::lock' ensures that even with simultaneous requests, the fetch runs only once.
        $lock = Cache::lock('fetch-nasa-firms-data', 10); // Lock for 10 seconds

        if ($lock->get()) {
            // Check if we have fetched in the last 30 minutes
            $lastFetch = Cache::get('nasa_firms_last_fetch');
            if (!$lastFetch || now()->diffInMinutes($lastFetch) > 30) {
                $firmsService->fetchAndStoreIncidents();
                Cache::put('nasa_firms_last_fetch', now());
            }
            $lock->release();
        }

        return view('firefighter/dashboard'); // Assumes your blade file is named this
    }

    /**
     * API endpoint to get all recent fire incidents for the map.
     */
    public function getApiIncidents()
    {
        // Fetch incidents from the last 24 hours from our database
        $incidents = FireIncident::where('acq_date', '>=', now()->subDay()->toDateString())->get();

        // We can format the data here to make it easier for JavaScript
        $geoJson = $incidents->map(function ($incident) {
            return [
                'type' => 'Feature',
                'properties' => [
                    'latitude' => $incident->latitude,
                    'longitude' => $incident->longitude,
                    'brightness' => $incident->brightness,
                    'confidence' => $incident->confidence,
                    'acq_date' => $incident->acq_date->format('Y-m-d'),
                    'acq_time' => $incident->acq_time->format('H:i'),
                    'satellite' => $incident->satellite,
                    'frp' => $incident->frp, // Fire Radiative Power
                    'daynight' => $incident->daynight == 'D' ? 'Day' : 'Night',
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$incident->longitude, $incident->latitude]
                ]
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $geoJson
        ]);
    }
}