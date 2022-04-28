<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class fixOrderRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::where('rate',0)->update(["rate" => null]);
    }
}
