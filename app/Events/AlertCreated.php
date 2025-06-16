<?php

namespace App\Events;

use App\Models\Alert;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AlertCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $payload;

    public function __construct(Alert $alert)
    {
        // Build a simple, clean array to prevent any serialization errors.
        $this->payload = [
            'id' => $alert->id,
            'message' => $alert->message,
            'latitude' => $alert->latitude,
            'longitude' => $alert->longitude,
            'radius' => $alert->radius,
            'created_at' => $alert->created_at->toIso8601String(),
        ];
        Log::info('AlertCreated event constructed with custom payload for alert ID: ' . $alert->id);
    }

    public function broadcastOn(): array
    {
        // This is a public channel that anyone can listen to.
        return [new Channel('public-alerts')];
    }

    /**
     * Define the event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'alert.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        // Send the payload directly.
        return $this->payload;
    }
}