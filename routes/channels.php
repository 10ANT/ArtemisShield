<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// This authorizes the officer dashboard to listen for status updates.
Broadcast::channel('officer-dashboard', function ($user) {
    // Check if the authenticated user has the 'Wildfire Management Officer' role
    $hasRole = $user->hasRole('Wildfire Management Officer');
    if ($hasRole) {
        Log::info("User {$user->id} successfully authorized for 'officer-dashboard' channel.");
    } else {
        Log::warning("User {$user->id} failed authorization for 'officer-dashboard' channel.");
    }
    return $hasRole;
});