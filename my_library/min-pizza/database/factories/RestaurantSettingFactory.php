<?php

namespace Database\Factories;

use App\Models\RestaurantSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RestaurantSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'restaurant_id'  => rand(1, 7),
            'taken_percentage_from_delivery'  => 0,
            'taken_percentage_from_takeaway' => 0 ,
            'max_delivery_distance' => 10
        ];
    }
}
