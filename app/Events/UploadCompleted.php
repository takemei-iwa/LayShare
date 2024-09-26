<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;

class UploadCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        Log::debug("UploadCompleted event");
    }

    // /**
    //  * Get the channels the event should broadcast on.
    //  *
    //  * @return array<int, \Illuminate\Broadcasting\Channel>
    //  */
    public function broadcastOn()
    {
        try {
            Log::debug("UploadCompleted broadcastOn");
            // return new PrivateChannel('user.' . $this->userId);
            return new Channel('chat');
        } catch (\Exception $e) {
            Log::error('Error in broadcastOn: ' . $e->getMessage());
        }
        
    }
}
