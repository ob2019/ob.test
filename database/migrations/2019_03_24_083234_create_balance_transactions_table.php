<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('player_id')->foreign('player_id')->references('id')->on('players');
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_before', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balance_transactions');
    }
}

