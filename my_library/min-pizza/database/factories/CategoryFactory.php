<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

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
            'is_active' => 1 ,
        ];
    }
}
