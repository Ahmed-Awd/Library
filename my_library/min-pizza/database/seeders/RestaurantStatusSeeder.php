<?php

namespace Database\Seeders;

use App\Models\RestaurantStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('restaurant_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "open",
                    "sv" => "Öppen"
                ])
            ]);
            DB::table('restaurant_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "closed",
                    "sv" => "stängd"
                ])
            ]);
            DB::table('restaurant_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "temporarily closed",
                    "sv" => "Tillfälligt stängt"
                ])
            ]);
    }
}
