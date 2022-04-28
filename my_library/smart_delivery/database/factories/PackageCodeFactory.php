<?php

namespace Database\Factories;

use App\Models\PackageCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PackageCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PackageCode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "package_id" => 0,
            "code" => Str::random(10),
            "user_id" => null,
            "purchased_at" => null
        ];
    }
}
