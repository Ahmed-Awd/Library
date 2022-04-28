<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $trans = [
            "statistics" => ['en' => 'statistics','sv' => 'Statistik'],
        ];
        DB::table('modules')->insertOrIgnore([
            ["key" => "statistics","name" => json_encode($trans["statistics"]),'value' => "true"],
        ]);
    }
}
