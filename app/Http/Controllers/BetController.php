<?php

namespace App\Http\Controllers;

use App\Events\BetStored;
use App\Http\Requests\BetRequest;
use Symfony\Component\HttpFoundation\Response;

use App\Player;

class BetController extends Controller
{
    /**
     * Processes /api/bet request
     *
     * @param BetRequest $request
     *
     * @return Response
     */
    public function store(BetRequest $request): Response
    {
        $player = Player::find($request->player_id);

        $bet = $player->bets()->create([
            'stake_amount' => $request->stake_amount
        ]);

        foreach ($request->selections as $selection) {
            $bet->betSelections()->create([
                'selection_id' => $selection['id'],
                'odds' => $selection['odds']
            ]);
        }

        // trigger an event for bet post-storing actions
        event(new BetStored($bet));

        return response()->json((object)[], Response::HTTP_CREATED);
    }
}
