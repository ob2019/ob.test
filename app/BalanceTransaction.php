<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    protected $fillable = [
        'amount', 'amount_before'
    ];

    public $timestamps = false;
}
