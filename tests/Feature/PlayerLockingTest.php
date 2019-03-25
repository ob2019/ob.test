<?php

namespace Tests\Feature;

use App\Helpers\CustomErrors;
use App\Player;
use Tests\TestCase;

class PlayerLocking extends TestCase
{
    private $data = [
        'player_id' => 1
    ];

    private $jsonPayload = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->jsonPayload = CustomErrors::getForJsonTesting(10);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfUnlockedPlayerWillNotGetFalsePositiveLockedPlayerError(): void
    {
        $player = Player::find($this->data['player_id']);
        $player->unlock();

        $this->json('put', '/api/bet', $this->data)
            ->assertJsonMissing($this->jsonPayload);
    }

    public function testIfLockedPlayerWillGetLockedPlayerError(): void
    {
        $player = Player::find($this->data['player_id']);
        $player->lock();

        $this->json('put', '/api/bet', $this->data)
            ->assertJsonFragment($this->jsonPayload);
    }
}
