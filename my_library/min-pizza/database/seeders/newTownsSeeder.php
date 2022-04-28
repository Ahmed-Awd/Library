<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class newTownsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::create([
            "country_id" => "1",
            "name" => translate("Vasteras"),
            "code" => "vst",
        ]);
    }
}
