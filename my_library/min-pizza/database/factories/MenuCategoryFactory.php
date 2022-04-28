<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MenuCategory::class;

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
            'name'      =>  $name,
            'description' => $this->faker->paragraph,
            'menu_id' => rand(1, 9)
        ];
    }
}
