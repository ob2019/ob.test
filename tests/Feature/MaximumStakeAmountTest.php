<?php

namespace Tests\Feature\Feature;

use Tests\TestCase;

use App\Player;
use App\Helpers\CustomErrors;

class MaximumStakeAmountTest extends TestCase
{
    private $rules = [
        'stake_amount' => 'required|numeric|max:10000'
    ];

    private $data = [
        'player_id' => "1",
        'stake_amount' => '11000',
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

        $this->jsonPayload = CustomErrors::getForJsonTesting(3, ":max", "10000");

        $player = Player::firstOrCreate(['id' => $this->data['player_id']]);
        $player->unlock();
    }

    public function testIfMaximumStakeErrorWillBeRaisedForTooHighStakeAmount(): void
    {
        $this->json('PUT', '/api/bet', $this->data)
            ->assertStatus(400)
            ->assertJsonFragment($this->jsonPayload);
    }

    public function testIfMaximumStakeErrorWillNotBeRaisedForAllowedStakeAmount(): void
    {
        $data = $this->data;
        $data['stake_amount'] = "10000";

        $r = $this->json('PUT', '/api/bet', $data)
            ->assertJsonMissing($this->jsonPayload);
    }
}
