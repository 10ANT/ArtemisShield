<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirefighterController extends Controller
{
    //
    public function dashboard()
    {
       return view('firefighter.dashboard');   
    }
}
