<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Player;

class SuccessfulRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfCorrectRequestWillReturnEmptyObjectResponse201()
    {
        $playerId = 1;

        $data = [
            'player_id' => "{$playerId}",
            'stake_amount' => '1',
            'selections' => [
                [
                    'id' => 1,
                    'odds' => '10',
                ],
                [
                    'id' => 2,
                    'odds' => '1',
                ]
            ]
        ];

        $player = Player::firstOrCreate(['id' => $playerId]);
        $player->unlock();

        $r = $this->json('put', '/api/bet', $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertSeeText("{}");
    }
}
