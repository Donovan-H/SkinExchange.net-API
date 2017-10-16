<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Items extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csgo_items', function (Blueprint $table) {
            $table->increments('item_id_pk');
            $table->integer('type_id_fk')->unsigned();
            $table->integer('weapon_id_fk')->unsigned();
            $table->integer('collection_id_fk')->unsigned();
            $table->integer('category_id_fk')->unsigned();
            $table->integer('quality_id_fk')->unsigned();
            $table->integer('exterior_id_fk')->unsigned();

            $table->bigInteger('class_id_fpk')->unsigned()->index();

            $table->string('name', 255);
            $table->string('market_name', 255)->index();
            $table->string('image', 255);
            $table->timestamps();
        });

        Schema::create('csgo_item_type', function (Blueprint $table) {
            $table->increments('type_id_pk');
            $table->string('type', 128);
        });

        Schema::create('csgo_item_weapon', function (Blueprint $table) {
            $table->increments('weapon_id_pk');
            //$table->integer('type_id_fk')->unsigned();//IMPLEMENT
            $table->string('weapon', 128);
        });

        Schema::create('csgo_item_collection', function (Blueprint $table) {
            $table->increments('collection_id_pk');
            $table->string('collection', 128);
        });

        Schema::create('csgo_item_category', function (Blueprint $table) {
            $table->increments('category_id_pk');
            $table->string('category', 128);
        });

        Schema::create('csgo_item_quality', function (Blueprint $table) {
            $table->increments('quality_id_pk');
            $table->string('quality', 128);
            $table->string('color', 8);
        });

        Schema::create('csgo_item_exterior', function (Blueprint $table) {
            $table->increments('exterior_id_pk');
            $table->string('exterior', 128);
        });

        Schema::table('csgo_items', function (Blueprint $table) {
            $table->foreign('type_id_fk')->references('type_id_pk')->on('csgo_item_type');
            $table->foreign('weapon_id_fk')->references('weapon_id_pk')->on('csgo_item_weapon');
            $table->foreign('collection_id_fk')->references('collection_id_pk')->on('csgo_item_collection');
            $table->foreign('category_id_fk')->references('category_id_pk')->on('csgo_item_category');
            $table->foreign('quality_id_fk')->references('quality_id_pk')->on('csgo_item_quality');
            $table->foreign('exterior_id_fk')->references('exterior_id_pk')->on('csgo_item_exterior');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('csgo_items');
        Schema::drop('csgo_item_type');
        Schema::drop('csgo_item_weapon');
        Schema::drop('csgo_item_collection');
        Schema::drop('csgo_item_category');
        Schema::drop('csgo_item_quality');
        Schema::drop('csgo_item_exterior');
    }
}
