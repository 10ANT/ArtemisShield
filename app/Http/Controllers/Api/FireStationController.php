<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FireStation; // Make sure to import your model
use Illuminate\Http\JsonResponse;

class FireStationController extends Controller
{
    /**
     * Get fire station data in GeoJSON format.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $fireStations = FireStation::all(); // Fetch all fire stations

        $features = [];
        foreach ($fireStations as $station) {
            $properties = $station->toArray(); // Get all attributes as an array
            unset($properties['lat'], $properties['lon'], $properties['id']); // Remove specific fields if not needed in properties

            // Handle nested all_tags if it's a JSON string
            if (isset($properties['all_tags']) && is_string($properties['all_tags'])) {
                $properties['all_tags'] = json_decode($properties['all_tags'], true);
            }

            // Convert snake_case back to original colon format for display in popup if desired
            // Or simply use the snake_case names as in the JavaScript for consistency
            $formattedProperties = [];
            foreach ($properties as $key => $value) {
                $formattedKey = str_replace('_', ':', $key); // Convert back for display
                $formattedProperties[$formattedKey] = $value;
            }
            // Overwrite the 'all_tags' key in $formattedProperties to keep it as a nested object
            if (isset($properties['all_tags'])) {
                 $formattedProperties['all_tags'] = $properties['all_tags'];
            }
            // Handle addr:street, addr:housenumber, etc. explicitly
            $formattedProperties['addr:street'] = $station->addr_street;
            $formattedProperties['addr:housenumber'] = $station->addr_housenumber;
            $formattedProperties['addr:city'] = $station->addr_city;
            $formattedProperties['addr:postcode'] = $station->addr_postcode;
            $formattedProperties['addr:state'] = $station->addr_state;
            $formattedProperties['addr:country'] = $station->addr_country;
            $formattedProperties['operator:type'] = $station->operator_type;
            $formattedProperties['fire_station:type'] = $station->fire_station_type;
            $formattedProperties['building:levels'] = $station->building_levels;
            $formattedProperties['ref:nfirs'] = $station->ref_nfirs;
            $formattedProperties['fire_station:code'] = $station->fire_station_code;
            $formattedProperties['fire_station:apparatus'] = $station->fire_station_apparatus;
            $formattedProperties['fire_station:staffing'] = $station->fire_station_staffing;
            $formattedProperties['contact:phone'] = $station->contact_phone;
            $formattedProperties['contact:website'] = $station->contact_website;
            $formattedProperties['contact:email'] = $station->contact_email;


            $features[] = [
                'type'       => 'Feature',
                'geometry'   => [
                    'type'        => 'Point',
                    'coordinates' => [(float)$station->lon, (float)$station->lat], // [longitude, latitude]
                ],
                'properties' => $formattedProperties, // Pass all other properties
            ];
        }

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}