<?php

namespace App\Events;

use App\Models\StatusUpdate;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // THIS IS THE KEY
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

// By implementing ShouldBroadcast, you are telling Laravel:
// "After this event is created, you MUST attempt to broadcast it."
class StatusUpdateReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $broadcastPayload;

    /**
     * Create a new event instance.
     */
    public function __construct(StatusUpdate $statusUpdate)
    {
        // We build a simple array immediately to avoid any errors in the queue.
        $this->broadcastPayload = [
            'id'             => $statusUpdate->id,
            'message'        => $statusUpdate->message,
            'classification' => $statusUpdate->classification,
            'latitude'       => $statusUpdate->latitude,
            'longitude'      => $statusUpdate->longitude,
            'contact_number' => $statusUpdate->contact_number,
            'created_at'     => $statusUpdate->created_at->toIso8601String(),
            'user'           => $statusUpdate->user ? [
                'name'  => $statusUpdate->user->name,
                'email' => $statusUpdate->user->email,
            ] : null,
        ];

        Log::info('StatusUpdateReceived Event Constructed with custom payload for update ID: ' . $statusUpdate->id);
    }

    /**
     * This method is ONLY called if the event implements ShouldBroadcast.
     */
    public function broadcastOn(): array
    {
        Log::info('StatusUpdateReceived Broadcasting On: Channel officer-dashboard');
        return [
            new PrivateChannel('officer-dashboard'),
        ];
    }

    /**
     * Get the name of the event to broadcast.
     */
    public function broadcastAs(): string
    {
        return 'status.update.received';
    }

    /**
     * Get the explicit data array to broadcast.
     */
    public function broadcastWith(): array
    {
        return $this->broadcastPayload;
    }
}