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
/*
        DB::table('proxies')->insert([
            'ip_address' => '104.149.86.34',
            'username' => 'sn9obgspxxk',
            'password' => 'e98a7180a0833c9916d'
        ]);
        DB::table('proxies')->insert([
            'ip_address' => '108.175.52.186',
            'username' => 'sn9obgspxxk',
            'password' => 'e98a7180a0833c9916d'
        ]);
        DB::table('proxies')->insert([
            'ip_address' => '104.149.91.220',
            'username' => 'sn9obgspxxk',
            'password' => 'e98a7180a0833c9916d'
        ]);
        DB::table('proxies')->insert([
            'ip_address' => '108.175.52.22',
            'username' => 'sn9obgspxxk',
            'password' => 'e98a7180a0833c9916d'
        ]);
        DB::table('proxies')->insert([
            'ip_address' => '107.183.242.245',
            'username' => 'sn9obgspxxk',
            'password' => 'e98a7180a0833c9916d'
        ]);
        DB::table('proxies')->insert([
            'ip_address' => '109.73.79.176',
            'username' => 'sn9obgspxxk',
            'password' => 'e98a7180a0833c9916d'
        ]);
        DB::table('proxies')->insert([
            'ip_address' => '109.73.79.157',
            'username' => 'sn9obgspxxk',
            'password' => 'e98a7180a0833c9916d'
        ]);
        DB::table('proxies')->insert([
            'ip_address' => '103.197.169.53',
            'username' => 'sn9obgspxxk',
            'password' => 'e98a7180a0833c9916d'
        ]);
        DB::table('proxies')->insert([
            'ip_address' => '104.149.91.217',
            'username' => 'sn9obgspxxk',
            'password' => 'e98a7180a0833c9916d'
        ]);
        DB::table('proxies')->insert([
            'ip_address' => '104.149.91.194',
            'username' => 'sn9obgspxxk',
            'password' => 'e98a7180a0833c9916d'
        ]);*/
    }
}
