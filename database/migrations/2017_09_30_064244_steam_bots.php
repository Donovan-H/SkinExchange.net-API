<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SteamBots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_bots', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('steam_id')->unsigned()->index();
            $table->string('username', 64);
            $table->string('password', 64);
            $table->string('shared_secret', 64);
            $table->string('identity_secret', 64);
            $table->tinyInteger('isActive')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('steam_bots');  
    }
}
