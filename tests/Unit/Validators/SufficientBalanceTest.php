<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;

class SufficientBalanceTest extends TestCase
{
    private $rules = [
        'player_id' => 'sufficient_balance'
    ];

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSufficientBalanceValidatorWillWorkWithNotExistingPlayer()
    {
        $data = [
            'player_id' => "1",
        ];
        request()->merge($data);

        $v = $this->app['validator']->make($data, $this->rules);
        $this->assertTrue($v->passes());
    }

    public function testSufficientBalanceValidatorWillFailOnInsufficientBalance()
    {
        $data = [
            'player_id' => "100",
            'stake_amount' => '1000',
            'selections' => [
                [
                    'id' => 1,
                    'odds' => '10',
                ],
                [
                    'id' => 2,
                    'odds' => '0.5',
                ]
            ]
        ];
        request()->merge($data);

        $v = $this->app['validator']->make($data, $this->rules);
        $this->assertFalse($v->passes());
    }
}
