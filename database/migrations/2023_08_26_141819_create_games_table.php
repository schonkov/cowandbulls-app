<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            // $table->integer('user_id')->unsigned()->nullable();
            $table->unsignedBigInteger('user_id');
            $table->integer('answer')->unsigned();
            $table->boolean('ended')->default(0);
            $table->boolean('success')->default(0);
            $table->timestamps();
            
        });

        Schema::table('games', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
