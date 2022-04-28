<?php

namespace Database\Seeders;

use App\Models\DeliveryPriceOption;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantPriceOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurants = Restaurant::all();
        foreach ($restaurants as $restaurant) {
            DeliveryPriceOption::factory()->create(['restaurant_id' => $restaurant->id]);
        }
    }
}
