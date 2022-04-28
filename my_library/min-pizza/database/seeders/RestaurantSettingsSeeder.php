<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\RestaurantSetting;
use Illuminate\Database\Seeder;

class RestaurantSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RestaurantSetting::truncate();
        $restaurants = Restaurant::select('id', 'name')->get();
        foreach ($restaurants as $restaurant) {
            RestaurantSetting::factory()->create(['restaurant_id' => $restaurant->id]);
        }
    }
}
