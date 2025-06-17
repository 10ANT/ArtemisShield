<?php

namespace App\Http\Controllers\WildfireOfficer;

use App\Http\Controllers\Controller;
use App\Models\StatusUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StatusUpdatesController extends Controller
{
    public function index()
    {
        Log::info('Wildfire officer viewing status updates page.');
        $updates = StatusUpdate::with('user')->latest()->paginate(50);
        return view('wildfire-officer.status-updates', ['updates' => $updates]);
    }
}