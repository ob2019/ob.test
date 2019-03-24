<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BetSelection extends Model
{
    protected $fillable = [
        'selection_id', 'odds'
    ];

    public $timestamps = false;
}
