<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SwitchCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->update(["city_id" => 11,"country_code" => "46"]);
        DB::table('restaurants')->update(["city_id" => 11]);
    }
}
