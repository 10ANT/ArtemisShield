<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\User;
use App\Models\StatusUpdate; // Make sure to import StatusUpdate model if it exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommandController extends Controller
{
    /**
     * Get alerts that the authenticated command user is currently located within.
     */
    public function getRelevantAlerts(Request $request)
    {
        /** @var \App\Models\User $commandUser */
        $commandUser = Auth::user();

        if (!$commandUser->latitude || !$commandUser->longitude) {
            // Return an empty array if the command user has no location
            return response()->json(['alerts' => []]);
        }

        $lat = $commandUser->latitude;
        $lon = $commandUser->longitude;

        // Haversine formula to find alerts containing the user's location.
        $alerts = Alert::whereRaw(
            "( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) < (radius / 1000)",
            [$lat, $lon, $lat]
        )->get();
        
        // **MODIFIED RESPONSE**
        // Return both the alerts and the command user's location for routing purposes
        return response()->json([
            'alerts' => $alerts,
            'command_user_location' => [
                'latitude' => $lat,
                'longitude' => $lon
            ]
        ]);
    }

    /**
     * Get all users located within a specific alert's radius, along with their latest status update.
     */
    public function getAffectedUsersInAlert(Alert $alert)
    {
        $lat = $alert->latitude;
        $lon = $alert->longitude;
        $radiusInKm = $alert->radius / 1000;

        $usersInZone = User::select('id', 'name', 'latitude', 'longitude')
            ->whereNotNull('users.latitude')
            ->whereNotNull('users.longitude')
            ->whereRaw(
                "( 6371 * acos( cos( radians(?) ) * cos( radians( users.latitude ) ) * cos( radians( users.longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( users.latitude ) ) ) ) < ?",
                [$lat, $lon, $lat, $radiusInKm]
            )->get();

        $userIds = $usersInZone->pluck('id');

        $latestUpdates = StatusUpdate::select('user_id', 'classification', 'message', 'created_at as last_update')
            ->whereIn('user_id', $userIds)
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('status_updates')
                    ->groupBy('user_id');
            })
            ->get()
            ->keyBy('user_id');

        $results = $usersInZone->map(function ($user) use ($latestUpdates) {
            $lastStatus = $latestUpdates->get($user->id);
            $user->status = $lastStatus->classification ?? 'awaiting_response';
            $user->message = $lastStatus->message ?? null;
            $user->last_update = $lastStatus->last_update ?? null;
            return $user;
        });

        return response()->json($results);
    }
}