<?php

namespace Database\Seeders;

use App\Models\DeliveryPriceOption;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantPrepareOrderTimeSeeder extends Seeder
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
            $restaurant->update(['prepare_order_time' => 20]);
        }
    }
}
