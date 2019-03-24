<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event): void
    {
        $newBalance = $event->bet->player->balance - $event->bet->getTotalAmount();
        $event->bet->player->update([
            'balance' => $newBalance,
        ]);
    }
}
