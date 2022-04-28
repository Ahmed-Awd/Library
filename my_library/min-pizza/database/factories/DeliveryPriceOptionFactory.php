<?php

namespace Database\Factories;

use App\Models\DeliveryPriceOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryPriceOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryPriceOption::class;
    protected $types = ['percent','value','free'];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'restaurant_id'  => rand(1, 7),
//            'type'  => $this->types[array_rand($this->types)],
            'type'  => "free",
            'value' => 0 ,
            'kilometer' => 0
        ];
    }
}
