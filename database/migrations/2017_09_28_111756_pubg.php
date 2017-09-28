<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pubg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pugb_items', function (Blueprint $table) {
            $table->increments('item_id_pk');
            $table->bigInteger('class_id_fpk')->unsigned()->index();

            $table->string('name', 255);
            $table->string('market_name', 255)->index();

            $table->string('image', 255);
            $table->string('image_large', 255);

            $table->string('name_color', 255);
            $table->string('background_color', 255);

            $table->string('type', 255);

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
        Schema::drop('pubg_items');        
    }
}
