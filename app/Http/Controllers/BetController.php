<?php

namespace App\Http\Controllers;

use App\Events\BetStored;
use App\Http\Requests\BetRequest;
use Illuminate\Support\Facades\DB;
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
        //get player we will be working with
        $player = Player::find($request->player_id);

        //lock it - only one bet store request per time
        $player->lock();

        DB::transaction(function() use ($request, $player) {
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

            sleep(10);
        });

        //unlock player for next requests
        $player->unlock();

        return response()->json((object)[], Response::HTTP_CREATED);
    }
}
