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

     public function clearStatus(User $user)
    {
        Log::info("Command user " . Auth::id() . " is clearing status for user ID: " . $user->id);

        // Create a new status update to override any previous ones.
        // This preserves the history of their previous "needs_help" requests.
        $statusUpdate = StatusUpdate::create([
            'user_id' => $user->id,
            'classification' => 'im_safe',
            'message' => 'Status manually cleared by Command.',
            // You might want to copy lat/lng from their last known update if available
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
        ]);

        // You could dispatch an event here if other parts of the system need to know
        // event(new UserStatusCleared($user, $statusUpdate));

        return response()->json([
            'success' => true,
            'message' => "User {$user->name} has been marked as cleared.",
            'new_status' => $statusUpdate, // Send back the new status for UI updates
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