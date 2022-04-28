<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Seeder;

class MakeDriversSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers= User::whereHas("roles", function ($q) {
            $q->where("name", "driver");
        })->get();

        foreach ($drivers as $driver) {
            Driver::create([
                'type'=>"app",
                'user_id'=>$driver->id,
                'restaurant_id'=>null,
                'is_active' =>  1
            ]);
        }
    }
}
