<?php

namespace App\Http\Controllers;

use App\Http\Requests\BetRequest;

class BetController extends Controller
{
    /**
     * Processes /api/bet request
     *
     * @param BetRequest $request
     */
    public function store(BetRequest $request)
    {
        print_r('ok');
    }
}
