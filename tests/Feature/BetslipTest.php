<?php

namespace Tests\Feature\Feature;

use App\Player;
use Illuminate\Support\Arr;
use Tests\TestCase;
use App\Helpers\CustomErrors;

class BetslipTest extends TestCase
{
    private $data = [
        'player_id' => "1",
        'stake_amount' => '10',
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

        $this->jsonPayload = CustomErrors::getForJsonTesting(1);

        $player = Player::firstOrCreate(['id' => $this->data['player_id']]);
        $player->unlock();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfMissingPlayerIdWillCauseBetlipStructureMismatchError(): void
    {
        $this->json('PUT', '/api/bet', Arr::except($this->data, "player_id"))
            ->assertStatus(400)
            ->assertJsonFragment($this->jsonPayload);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfMissingStakeAmountWillCauseBetlipStructureMismatchError(): void
    {
        $this->json('PUT', '/api/bet', Arr::except($this->data, "stake_amount"))
            ->assertStatus(400)
            ->assertJsonFragment($this->jsonPayload);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfMissingSelectionsWillCauseBetlipStructureMismatchError(): void
    {
        $this->json('PUT', '/api/bet', Arr::except($this->data, "selections"))
            ->assertStatus(400)
            ->assertJsonFragment($this->jsonPayload);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfIncorrectSelectionsTypeWillCauseBetlipStructureMismatchError(): void
    {
        $data = $this->data;
        $data['selections'] = "";

        $this->json('PUT', '/api/bet', $data)
            ->assertStatus(400)
            ->assertJsonFragment($this->jsonPayload);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfSelectionIdWillCauseBetlipStructureMismatchError(): void
    {
        $data = $this->data;
        $data['selections'][0] = Arr::except($data['selections'][0], "id");

        $this->json('PUT', '/api/bet', $data)
            ->assertStatus(400)
            ->assertJsonFragment($this->jsonPayload);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfSelectionOddsWillCauseBetlipStructureMismatchError(): void
    {
        $data = $this->data;
        $data['selections'][0] = Arr::except($data['selections'][0], "odds");

        $this->json('PUT', '/api/bet', $data)
            ->assertStatus(400)
            ->assertJsonFragment($this->jsonPayload);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testThereWillBeNoBetslipErrorsWithCorrectRequestData(): void
    {
        $this->json('PUT', '/api/bet', $this->data)
            ->assertStatus(201)
            ->assertJsonMissing($this->jsonPayload);
    }
}
