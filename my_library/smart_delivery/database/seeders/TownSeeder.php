<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TownSeeder extends Seeder
{

    public function run()
    {
        DB::table('towns')->insertOrIgnore([
            ['id'=>1, 'name' => translate('Istanbul'),'is_active' => true],
            ['id'=>2, 'name' => translate('Mersin'),'is_active' => true],
        ]);
    }
}
