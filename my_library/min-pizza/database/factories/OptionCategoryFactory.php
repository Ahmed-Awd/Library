<?php

namespace Database\Factories;

use App\Models\OptionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OptionCategory::class;

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
            'name' => $name  ,
            'selection' => 'single',
            'is_primary' => 1,
            'max_selectable' => 1,
            'restaurant_id' => rand(1,10),

        ];
    }
}
