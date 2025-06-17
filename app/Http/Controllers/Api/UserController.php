<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Make sure to import the User model
use App\Models\StatusUpdate; // Import the StatusUpdate model

class UserController extends Controller
{
    /**
     * Update the authenticated user's location.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function clearStatus($userId, $alertId) // **MODIFIED**: Accept $alertId
    {
        $user = User::find($userId);

        if (!$user) {
            Log::warning("Attempt to clear status for non-existent user ID: " . $userId);
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        // **MODIFIED**: Log includes the alert context
        Log::info("Command user " . Auth::id() . " is clearing status for user ID: " . $user->id . " within alert context ID: " . $alertId);

        // This action overrides their status globally by creating the latest record.
        // The frontend will re-poll and see this new "im_safe" status as the latest one.
        $statusUpdate = StatusUpdate::create([
            'user_id' => $user->id,
            'classification' => 'im_safe',
            'message' => 'Status manually cleared by Command for Alert #' . $alertId, // Add context to message
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => "User {$user->name} has been marked as cleared.",
            'new_status' => $statusUpdate,
        ]);
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();

            if ($user) {
                // Using update() is cleaner if the fields are mass-assignable
                $user->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                return response()->json(['message' => 'Location updated successfully.']);
            }
            
            // This case should not be hit because of auth:sanctum middleware, but it's good practice
            return response()->json(['message' => 'User not authenticated.'], 401);

        } catch (\Exception $e) {
            Log::error('Error updating user location for user ID ' . (Auth::id() ?? 'N/A') . ': ' . $e->getMessage());
            return response()->json(['message' => 'An internal error occurred while updating location.'], 500);
        }
    }
}