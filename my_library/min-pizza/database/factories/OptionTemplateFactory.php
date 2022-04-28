<?php

namespace Database\Factories;

use App\Models\OptionTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionTemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OptionTemplate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name=array(
            'ar' => $this->faker->name,
            'en' => $this->faker->name,
            'sv' => $this->faker->name,
        );
        $name = json_encode($name);
        return [
            'name'      => $name,
            'primary_option_id' => rand(1,10),
            'restaurant_id' => rand(1,10) ,
        ];
    }
}
