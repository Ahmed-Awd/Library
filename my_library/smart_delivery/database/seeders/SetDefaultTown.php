<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetDefaultTown extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->update(['town_id' => 1]);
        DB::table('stores')->update(['town_id' => 1]);
        DB::table('orders')->update(['town_id' => 1]);
    }
}
