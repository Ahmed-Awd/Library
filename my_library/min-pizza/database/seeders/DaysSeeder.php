<?php

namespace Database\Seeders;

use App\Models\RestaurantStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('days')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Saturday",
                    "sv" => "Lördag",
                ])
            ]);
            DB::table('days')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Sunday",
                    "sv" => "Söndag",
                ])
            ]);
            DB::table('days')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Monday",
                    "sv" => "Måndag",
                ])
            ]);
            DB::table('days')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Tuesday",
                    "sv" => "Tisdag",
                ])
            ]);
            DB::table('days')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Wednesday",
                    "sv" => "Onsdag",
                ])
            ]);
            DB::table('days')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Thursday",
                    "sv" => "Torsdag",
                ])
            ]);
            DB::table('days')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Friday",
                    "sv" => "fredag",
                ])
            ]);
    }
}
