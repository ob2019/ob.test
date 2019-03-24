<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetSelectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_selections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('bet_id')->foreign('bet_id')->references('id')->on('bets');
            $table->integer('selection_id');
            $table->decimal('odds', 8, 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bet_selections');
    }
}
