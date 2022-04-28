<?php

namespace Database\Factories;

use App\Models\OptionSecondary;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionSecondaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OptionSecondary::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       
        return [
            'secondary_option_id' => rand(1,10),
            'primary_option_value_id' => rand(1,10) ,
            'secondary_option_value_id' => rand(1,10) ,
            'option_template_id' => rand(1,10) ,
            'price' => 0,
            'use_default' => 1,
        ];
    }
}
