<?php

namespace App\Listeners;

use App\Events\BetStored;

class UpdatePlayerBalance
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Updating player balance
     *
     * @param  BetStored  $event
     * @return void
     */
    public function handle(BetStored $event): void
    {
        $newBalance = $event->bet->player->balance - $event->bet->getTotalAmount();
        $event->bet->player->update([
            'balance' => $newBalance,
        ]);
    }
}
