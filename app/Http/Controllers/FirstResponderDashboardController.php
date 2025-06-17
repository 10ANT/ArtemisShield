<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusUpdate; // Use the StatusUpdate model
use Illuminate\Support\Facades\Log;

class FirstResponderDashboardController extends Controller
{
    /**
     * Display the first responder dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch only the status updates where classification is 'needs_help'.
        // We order by the newest first and eager load the 'user' relationship.
        // THE CHANGE IS HERE: Added whereNull('fulfilled_at')
        $assistanceRequests = StatusUpdate::with('user')
            ->where('classification', 'needs_help')
            ->whereNull('fulfilled_at') // Only show unfulfilled requests
            ->latest()
            ->paginate(20);

        return view('first-responder.dashboard', [
            'assistanceRequests' => $assistanceRequests,
        ]);
    }
}