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
        $notifications = Notification::where('user_id', Auth::id())
            ->with('reporter:id,name') // Eager load reporter's name
            ->latest() // Order by most recent
            ->take(20) // Limit to the last 20
            ->get();

        // Separate unread count for the UI badge
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