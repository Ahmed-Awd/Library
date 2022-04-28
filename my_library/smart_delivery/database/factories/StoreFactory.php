<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;



    public function definition()
    {
        return [
            'name'      => $this->faker->name,
            'lng'        => -74.044502,
            'lat'        => 40.689247,
            'address'         => Str::random(12),
        ];
    }

    public function withOwner($owner_id): StoreFactory
    {
        $owner = User::findOrFail($owner_id);
        return $this->state([
            'owner_id'  => $owner->id,
        ]);
    }
}
