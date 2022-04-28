<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'package_name' => $this->faker->name(),
            'value' => rand(10,50) * 100,
            'price' => rand(10,20) * 100,
            'type' => 'driver'
        ];
    }
}
