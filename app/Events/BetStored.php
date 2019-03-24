<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Bet;

class BetStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Bet
     */
    public $ber = null;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Bet $bet)
    {
        $this->bet = $bet;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
