<?php

namespace App\Http\Controllers\EndUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        Log::info('End user viewing their dashboard.');
        return view('end-user.dashboard');
    }
}