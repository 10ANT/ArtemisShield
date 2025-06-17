<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoricalFire; // Import the model

class HistoricalMapController extends Controller
{
    /**
     * Display the historical map view.
     */
    public function showMap()
    {
        // This method just returns the Blade view for the map.
        // The data will be loaded asynchronously via an API call.
        return view('predictions.historical-map');
    }

    /**
     * Provide historical fire data as a JSON API endpoint.
     */
    public function getFireData()
    {
        // IMPORTANT: Sending millions of records at once will crash the browser.
        // We will fetch a large, random sample of the data. 50,000 is a good number
        // that balances map density with performance, especially with marker clustering.
        // You can adjust this number based on your needs.
        $fires = HistoricalFire::inRandomOrder()
            ->limit(50000)
            ->get([
                'latitude',
                'longitude',
                'brightness',
                'acq_date',
                'acq_time',
                'satellite',
                'confidence',
                'frp'
            ]);

        return response()->json($fires);
    }
}