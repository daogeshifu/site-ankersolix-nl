<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusUpdated
{
    // use Dispatchable, InteractsWithSockets, SerializesModels;

    // /**
    //  * Create a new event instance.
    //  */
    // public function __construct()
    // {
    //     //
    // }

    // /**
    //  * Get the channels the event should broadcast on.
    //  *
    //  * @return array<int, \Illuminate\Broadcasting\Channel>
    //  */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $taskId;
    public $status;

    public function __construct($taskId, $status)
    {
        $this->taskId = $taskId;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        return new Channel("task.{$this->taskId}");
    }

    public function broadcastAs()
    {
        return 'status.updated';
    }
}
