<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_weapon')->insert([
            'weapon' => 'None'
        ]);

        DB::table('item_collection')->insert([
            'collection' => 'None'
        ]);

        DB::table('item_exterior')->insert([
            'exterior' => 'None'
        ]);
    }
}
