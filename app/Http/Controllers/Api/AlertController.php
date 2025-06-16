<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Events\AlertCreated;
use App\Events\AlertDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AlertController extends Controller
{
    public function index()
    {
        Log::info('Fetching all alerts');
        return Alert::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1',
        ]);

        Log::info('Storing new alert', $validated);

        $alert = Alert::create([
            'user_id' => Auth::id(), // Assumes the officer is logged in
            'message' => $validated['message'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'radius' => $validated['radius'],
        ]);

        // Dispatch our new robust event
        AlertCreated::dispatch($alert);

        return response()->json($alert, 201);
    }

    public function destroy(Alert $alert)
    {
        $alertId = $alert->id;
        Log::info('Deleting alert ID: ' . $alertId);
        
        $alert->delete();

        // Dispatch our new event for deletion
        AlertDeleted::dispatch($alertId);
        
        return response()->json(null, 204);
    }
}