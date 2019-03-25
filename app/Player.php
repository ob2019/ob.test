<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Locking;

class Player extends Model
{
    use Locking;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'balance'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
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
