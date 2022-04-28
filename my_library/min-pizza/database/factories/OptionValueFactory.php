<?php

namespace Database\Factories;

use App\Models\OptionValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionValueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OptionValue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'name' => $this->faker->name  ,
            'option_category_id' => rand(10, 99),
            'price' => rand(10, 99),
            'min_count' => rand(1, 99),
            'max_count' => rand(1, 99),
        ];
    }
}
