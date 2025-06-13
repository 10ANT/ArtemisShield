<?php

namespace App\Http\Controllers;

// In app/Http/Controllers/NotificationController.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
{
    // Get the raw notifications from the database, still eager-loading for performance
    $raw_notifications = Notification::where('user_id', Auth::id())
        ->with('reporter:id,name') // Eager load reporter's name
        ->latest()
        ->take(20)
        ->get();

    // Now, manually transform the collection to ensure it's safe to send as JSON.
    // This prevents errors if a reporter is null.
    $notifications = $raw_notifications->map(function ($notification) {
        // We add a 'reporter_name' field to our data.
        // We check if the 'reporter' relationship exists. If it does, use its name.
        // If not, provide a safe fallback like 'System' or 'Unknown'.
        $notification->reporter_name = $notification->reporter ? $notification->reporter->name : 'System';
        return $notification;
    });

    // Get the unread count separately
    $unreadCount = Notification::where('user_id', Auth::id())->whereNull('read_at')->count();

    return response()->json([
        'notifications' => $notifications,
        'unread_count' => $unreadCount
    ]);
}

    public function markAsRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Notifications marked as read.']);
    }
}