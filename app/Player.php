<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'id', 'balance'
    ];

    public $timestamps = false;


    public function balanceTransactions()
    {
        return $this->hasMany(BalanceTransaction::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}
