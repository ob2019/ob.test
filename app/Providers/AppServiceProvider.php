<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use App\Player;

class AppServiceProvider extends ServiceProvider
{
    /**
     * An array with all request parameters
     *
     * @var array
     */
    private $request = [];

    /**
     * Max win amount according to passed data
     *
     * @var float
     */
    private $winAmount = 0.00;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // fix for MySQL < 5.7.7
        Schema::defaultStringLength(191);

        // parsing request data which will be needed for further operations
        $this->prepareRequestData();

        // adding all custom validators we may need
        $this->addCustomValidators();
    }

    public function addCustomValidators()
    {
        $this->addMaxWinAmountValidator();

        $this->addSufficientBalanceValidator();
    }

    /**
     * Extends validators with "max_win_amount" validator, which ensures that maximum win amount will be lower than
     * value given as an argument
     */
    private function addMaxWinAmountValidator(): void
    {
        Validator::extend('max_win_amount', function ($attribute, $value, $parameters, $validator) {
            $validator->addReplacer('max_win_amount', function ($message, $attribute, $rule, $parameters) {
                return str_replace([':max'], $parameters, $message);
            });

            return $this->winAmount < $parameters[0];
        });
    }

    /**
     * Extends validators with "sufficient_balance" validator, which ensures that player balance is big enough for
     * making this bet
     */
    private function addSufficientBalanceValidator(): void
    {
        Validator::extend('sufficient_balance', function ($attribute, $value, $parameters, $validator) {
            $player_id = $this->request['player_id'] ?? 0;
            if ($player_id === 0) {
                return false;
            }

            /** @var Player $player */
            $player = Player::firstOrCreate(['id' => $player_id]);

            //refresh balance value in case we have created a new player
            $player->refresh();

            return $this->winAmount < $player->balance;
        });
    }

    /**
     * Gets total multiplier of odds frm all selections
     *
     * @param array $selections
     * @return float
     */
    private function getSelectionsMultiplier(): float
    {
        $multiplier = 1.00;

        $selection = $this->request['selections'] ?? [];

        if (!empty($selection)) {
            foreach ($this->request['selections'] as $selection) {
                $multiplier *= $selection['odds'] ?? 0;
            }
        }

        return $multiplier;
    }

    /**
     * Calculated max win amount according to request data
     *
     * @return float
     */
    private function getWinAmount(): float
    {
        $stakeAmount = $this->request['stake_amount'] ?? 0;
        $selectionsMultiplier = $this->getSelectionsMultiplier();

        return $stakeAmount * $selectionsMultiplier;
    }

    /**
     * Prepared data needed inside custom validators
     */
    private function prepareRequestData(): void
    {
        $this->request = request()->all();
        $this->winAmount = $this->getWinAmount();
    }
}