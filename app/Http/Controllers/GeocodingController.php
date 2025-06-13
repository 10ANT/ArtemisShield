<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingController extends Controller
{
    /**
     * Acts as a proxy to the Nominatim geocoding service to avoid CORS issues.
     */
    public function geocode(Request $request)
    {
        $location = $request->query('q');

        if (!$location) {
            return response()->json(['error' => 'Location query parameter (q) is required.'], 400);
        }

        try {
            // Nominatim requires a descriptive User-Agent header.
            $response = Http::withHeaders([
                'User-Agent' => 'ArtemisShield Dashboard Agent/1.0 (https://your-domain.com)'
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $location,
                'format' => 'json',
                'limit' => 5,
                'addressdetails' => 1
            ]);
            
            Log::info('Geocoding proxy request successful for location:', ['location' => $location]);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Geocoding proxy failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch geocoding data.'], 502);
        }
    }
}