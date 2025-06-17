<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StatusUpdate;
use App\Events\StatusUpdateReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // Import the Rule class

class StatusUpdateController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'contact_number' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            // Add validation for the new classification field
            'classification' => [
                'required',
                'string',
                Rule::in(['im_safe', 'threat_report', 'needs_help']),
            ],
        ]);

        Log::info('Storing new status update from user ID: ' . Auth::id(), $validated);

        $statusUpdate = StatusUpdate::create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'classification' => $validated['classification'],
            'contact_number' => $validated['contact_number'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);
        
        broadcast(new StatusUpdateReceived($statusUpdate))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Status update received.'
        ], 201);
    }


      public function poll(Request $request)
    {
        // Validate that the 'since' parameter is a number.
        $request->validate(['since' => 'required|integer']);

        // Find all updates with an ID greater than the last one we saw.
        // We eager load the 'user' to include their name.
        $updates = StatusUpdate::with('user')
                               ->where('id', '>', $request->query('since'))
                               ->orderBy('id', 'asc') // Order by ID to process them correctly
                               ->get();

        return response()->json($updates);
    }
}