<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CsgoItemsPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csgo_items_price', function (Blueprint $table) {
            $table->increments('price_id_pk');
            $table->string('market_name', 255)->index();
            $table->integer('mean');
            $table->integer('min');
            $table->integer('max');
            $table->integer('normalized_mean');
            $table->integer('normalized_min');
            $table->integer('normalized_max');
            $table->integer('std_dev');
            $table->date('sample_date');
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
        Schema::drop('csgo_items_price');
    }
}
