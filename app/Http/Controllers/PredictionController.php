<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PredictionController extends Controller
{
    //
    function dashboard()
    {
        return view('predictions.dashboard');
    }

    function wildfireRisk()
    {
        return view('predictions.analyst-wildfire-risk');
    }
}
