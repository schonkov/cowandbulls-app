<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guesses', function (Blueprint $table) {
            $table->id();
            // $table->integer('user_id')->unsigned();
            // $table->integer('game_id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('game_id');

            $table->integer('user_guess')->unsigned();
            $table->integer('cows')->unsigned();
            $table->integer('bulls')->unsigned();
            $table->integer('score')->unsigned();
            $table->string('response_sentence');

            $table->timestamps();
            
        });

        Schema::table('guesses', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('game_id')->references('id')->on('games');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guesses');
    }
}
