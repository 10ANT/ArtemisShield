<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoricalFire;

class HistoricalMapController extends Controller
{
    /**
     * Display the historical map view.
     */
    public function showMap()
    {
        return view('predictions.historical-map');
    }

    /**
     * Provide historical fire data as a JSON API endpoint, with filtering capabilities.
     */
    public function getFireData(Request $request)
    {
        $query = HistoricalFire::query();

        // Filter by date range
        if ($request->has('start_date') && $request->filled('start_date')) {
            $query->where('acq_date', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date') && $request->filled('end_date')) {
            $query->where('acq_date', '<=', $request->input('end_date'));
        }

        // Filter by geographic bounding box (bbox)
        if ($request->has('bbox') && $request->filled('bbox')) {
            $bbox = explode(',', $request->input('bbox'));
            if (count($bbox) === 4) {
                $minLng = $bbox[0];
                $minLat = $bbox[1];
                $maxLng = $bbox[2];
                $maxLat = $bbox[3];
                $query->whereBetween('longitude', [$minLng, $maxLng])
                      ->whereBetween('latitude', [$minLat, $maxLat]);
            }
        }
        
        // Limit the results
        if (!$request->hasAny(['start_date', 'end_date', 'bbox'])) {
             $fires = $query->inRandomOrder()->limit(50000);
        } else {
            $fires = $query->limit(100000);
        }

        // **MODIFIED: Fetch all columns for the details modal**
        // We no longer restrict the columns with ->get([...])
        $data = $fires->get(); 

        return response()->json($data);
    }
}