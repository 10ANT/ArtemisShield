<?php

namespace App\Http\Controllers\WildfireOfficer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('wildfire-officer.dashboard');
    }
}