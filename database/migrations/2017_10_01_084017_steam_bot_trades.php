<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SteamBotTrades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_bot_trades', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('partnerSteamId')->unsigned()->index();
            $table->string('accessToken', 32);
            $table->string('tradeIdentifier', 32)->unique();
            $table->integer('status');
            $table->string('botSignature',64); //bot steamid that assigned trade
            $table->bigInteger('steamTradeId');
            $table->tinyInteger('isActive')->default(1);
            $table->timestamps();
        });

        Schema::create('steam_bot_trade_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bot_trade_id')->unsigned()->index();
            $table->bigInteger('appId')->unsigned();
            $table->bigInteger('assetId')->unsigned();
            $table->bigInteger('classId')->unsigned();
            $table->bigInteger('instanceId')->unsigned()->index();
            $table->integer('amount')->default(1);
            $table->timestamps();
        });

        Schema::table('steam_bot_trade_items', function (Blueprint $table) {
            $table->foreign('bot_trade_id')->references('id')->on('steam_bot_trades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('steam_bot_trades');
        Schema::drop('steam_bot_trade_items');
    }
}
