<?php

namespace Database\Factories;

use App\Models\RestaurantRating;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantRatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RestaurantRating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'restaurant_id'  => rand(1, 7),
            'user_id'  => rand(1, 99),
            'rate' => rand(1, 6) ,
            'comment' =>  $this->faker->paragraph()
        ];
    }
}
