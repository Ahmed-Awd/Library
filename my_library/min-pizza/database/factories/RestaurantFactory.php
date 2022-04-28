<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Restaurant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'      => $this->faker->name,
            'address' => $this->faker->address ,
            'min_order_price' => rand(100000, 999999) ,
            'company_name' => $this->faker->name ,
            'company_number' => rand(100000, 999999) ,
            'vat' => 20 ,
            'lat' => 40.689247 ,
            'lng' => -74.044502 ,
            'status_id' => 1 ,
            'prepare_order_time' => 20,
            'ZIP_code' => rand(100000, 999999)  ,
        ];
    }

    public function withOwner($owner_id)
    {
        $owner = User::findOrFail($owner_id);
        return $this->state([
            'owner_id'  => $owner->id,
        ]);
    }
}
