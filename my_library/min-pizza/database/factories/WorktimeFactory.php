<?php

namespace Database\Factories;

use App\Models\Worktime;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorktimeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Worktime::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'day_id'  => rand(1, 7),
            'restaurant_id'  => rand(1, 7),
            'open_from' => '8:00:00' ,
            'open_to' => '8:00:00' ,
            'takeaway' => '1' ,
            'delivery' => '0' ,
            'status' => '1' ,
        ];
    }
}
