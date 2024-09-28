<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BidPlaced implements shouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $bid;

    /**
     * Create a new event instance.
     */
    public function __construct($bid)
    {
        $this->bid = $bid;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new Channel('bidsChannel');
    }

}
