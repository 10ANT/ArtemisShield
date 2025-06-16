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

    /**
     * The clean data payload to broadcast.
     * @var array
     */
    public array $payload;

    /**
     * Create a new event instance.
     */
    public function __construct(Alert $alert)
    {
        // Build a simple array to prevent serialization errors.
        $this->payload = [
            'id' => $alert->id,
            'message' => $alert->message,
            'latitude' => $alert->latitude,
            'longitude' => $alert->longitude,
            'radius' => $alert->radius,
        ];

        Log::info('AlertCreated event constructed with custom payload for alert ID: ' . $alert->id);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        Log::info('Broadcasting AlertCreated on public-alerts channel.');
        // This is a public channel, so no authentication is needed.
        return [
            new Channel('public-alerts'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        // We nest the payload under an 'alert' key to match the original JS logic.
        return ['alert' => $this->payload];
    }
}