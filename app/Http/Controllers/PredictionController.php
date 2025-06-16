<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PredictionController extends Controller
{
    //
    function dashboard()
    {
        return view('prediction.dashboard');
    }
}
