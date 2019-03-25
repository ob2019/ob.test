<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;

class MaxWinAmountTest extends TestCase
{
    public function testMaxWinAmountWillSuccessWithCorrectData()
    {
        $rules = [
            'player_id' => 'max_win_amount:20000'
        ];

        $data = [
            'player_id' => "1",
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

        $v = $this->app['validator']->make($data, $rules);
        $this->assertTrue($v->passes());
    }

    public function testMaxWinAmountWillFailWithInorrectData()
    {
        $rules = [
            'player_id' => 'max_win_amount:20000'
        ];

        $data = [
            'player_id' => "1",
            'stake_amount' => '1000',
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
        request()->merge($data);

        $v = $this->app['validator']->make($data, $rules);
        $this->assertFalse($v->passes());
    }
}
