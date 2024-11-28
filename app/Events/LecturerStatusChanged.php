<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LecturerStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lecturerId;
    public $isOnline;

    public function __construct($lecturerId, $isOnline)
    {
        $this->lecturerId = $lecturerId;
        $this->isOnline = $isOnline;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('lecturer-status');
    }
}
