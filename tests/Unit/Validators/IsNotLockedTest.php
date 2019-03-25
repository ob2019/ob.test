<?php

namespace Tests\Unit\Validators;

use App\Player;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IsNotLockedTest extends TestCase
{
    private $data = [
        'player_id' => "1",
    ];

    /**
     * @var Player
     */
    private $player = null;

    private $rules = [
        'player_id' => 'is_not_locked'
    ];

    public function setUp(): void
    {
        parent::setUp();

        // populating custom data over request
        request()->merge($this->data);

        // initializing player
        $this->player = Player::firstOrCreate(['id' => $this->data['player_id']]);
    }

    public function testIfIsNotLockedWillDenyToUseLockedUser(): void
    {
        $this->player->lock();

        $v = $this->app['validator']->make($this->data, $this->rules);
        $this->assertFalse($v->passes());
    }

    public function testIfIsNotLockedWillAllowToUseNonLockedUser(): void
    {
        $this->player->unlock();

        $v = $this->app['validator']->make($this->data, $this->rules);
        $this->assertTrue($v->passes());
    }
}
