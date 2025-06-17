<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StatusUpdateFulfilled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $statusUpdateId;

    public function __construct(int $statusUpdateId)
    {
        $this->statusUpdateId = $statusUpdateId;
        Log::info('StatusUpdateFulfilled event constructed for ID: ' . $this->statusUpdateId);
    }

    public function broadcastOn(): array
    {
        // We broadcast this on the same channel so all officers/responders are notified.
        return [new PrivateChannel('officer-dashboard')];
    }
    
    public function broadcastAs(): string
    {
        return 'status.update.fulfilled';
    }
}