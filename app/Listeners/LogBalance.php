<?php

namespace App\Listeners;

use App\Events\BetStored;

class LogBalance
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
     * Logging current and before balances
     *
     * @param BetStored $event
     * @return void
     */
    public function handle(BetStored $event): void
    {
        $newBalance = $event->bet->player->balance - $event->bet->getTotalAmount();

        $event->bet->player->balanceTransactions()->create([
            'amount' => $newBalance,
            'amount_before' => $event->bet->player->balance
        ]);
    }
}
