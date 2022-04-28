<?php

namespace Database\Factories;

use App\Models\RestaurantPhone;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantPhoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RestaurantPhone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'restaurant_id'  => rand(1, 7),
            'country_code' => "03",
            "phone" => "0111555".(string)rand(1000, 9888)
        ];
    }
}
