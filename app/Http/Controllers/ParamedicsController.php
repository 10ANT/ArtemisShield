<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParamedicsController extends Controller
{
    //
    function dashboard()
    {
        return view('paramedics.dashboard');
    }
}
