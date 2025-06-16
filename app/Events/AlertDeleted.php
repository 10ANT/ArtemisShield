<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AlertDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The ID of the alert that was deleted.
     * @var int
     */
    public int $alertId;

    /**
     * Create a new event instance.
     */
    public function __construct(int $alertId)
    {
        $this->alertId = $alertId;
        Log::info('AlertDeleted event constructed for alert ID: ' . $alertId);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        Log::info('Broadcasting AlertDeleted on public-alerts channel.');
        return [
            new Channel('public-alerts'),
        ];
    }
}