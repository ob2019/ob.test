<?php

namespace Tests\Feature\Feature;

use Tests\TestCase;

use App\Player;
use App\Helpers\CustomErrors;

class MinimumStakeAmountTest extends TestCase
{
    private $rules = [
        'stake_amount' => 'required|numeric|min:0.3'
    ];

    private $data = [
        'player_id' => "1",
        'stake_amount' => '0.1',
        'selections' => [
            [
                'id' => 1,
                'odds' => '10',
            ],
            [
                'id' => 2,
                'odds' => '5',
            ]
        ]
    ];

    private $jsonPayload = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->jsonPayload = CustomErrors::getForJsonTesting(2, ":min", "0.3");

        $player = Player::firstOrCreate(['id' => $this->data['player_id']]);
        $player->unlock();
    }

    public function testIfMinimumStakeErrorWillBeRaisedForTooLowStakeAmount(): void
    {
        $this->json('PUT', '/api/bet', $this->data)
            ->assertStatus(400)
            ->assertJsonFragment($this->jsonPayload);
    }

    public function testIfMinimumStakeErrorWillNotBeRaisedForAllowedStakeAmount(): void
    {
        $data = $this->data;
        $data['stake_amount'] = "0.3";

        $this->json('PUT', '/api/bet', $data)
            ->assertStatus(201)
            ->assertJsonMissing($this->jsonPayload);
    }
}
