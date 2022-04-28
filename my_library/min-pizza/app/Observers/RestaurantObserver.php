<?php

namespace App\Observers;

use App\Models\Restaurant;
use App\Models\RestaurantSetting;

class RestaurantObserver
{

    public function created(Restaurant $restaurant)
    {
        RestaurantSetting::factory()->create(['restaurant_id' => $restaurant->id]);
    }


    public function updated(Restaurant $restaurant)
    {
        //
    }


    public function deleted(Restaurant $restaurant)
    {
        //
    }


    public function restored(Restaurant $restaurant)
    {
        //
    }


    public function forceDeleted(Restaurant $restaurant)
    {
        //
    }
}
