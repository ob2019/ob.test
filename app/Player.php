<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Locking;

class Player extends Model
{
    use Locking;

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
