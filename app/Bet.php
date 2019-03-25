<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stake_amount'
    ];

    /**
     * Calculating total sum according to bet payload
     *
     * @return float
     */
    public function getTotalAmount(): float
    {
        $selections = $this->betSelections;

        $sum = $this->stake_amount;
        foreach ($selections as $selection) {
            $sum *= $selection['odds'];
        }

        return $sum;
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function betSelections()
    {
        return $this->hasMany(BetSelection::class);
    }
}
