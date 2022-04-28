<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = Driver::all();
        foreach ($drivers as $driver) {
            if ($driver->type=="app") {
                $driver->update(['restaurant_id'=>null]);
            }
        }
    }
}
