<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();
        DB::table('settings')->insertOrIgnore([
            ['name' => 'Store percentage taken from the delivery price','key'=> 'taken_percentage_from_delivery','value' => '25','default' => '25','is_price' => false],
            ['name' => 'Initial delivery price','key'=> 'initial_price','value' => '500','default' => '500','is_price' => true],
            ['name' => 'Initial distance','key'=> 'initial_distance','value' => '2','default' => '2','is_price' => false],
            ['name' => 'Delivery price per additional kilometer','key'=> 'additional_price','value' => '300','default' => '300','is_price' => true],
            ['name' => 'The period during which the admin will be notified if the request is not assigned to any driver','key'=> 'admin_notified_if_request_not_assigned','value' => '10','default' => '10','is_price' => false],
            ['name' => 'The period during which the admin will be notified if tthe order status remains waiting for the driver to arrive at the restaurant','key'=> 'admin_notified_if_driver_not_arrive_at_the_restaurant','value' => '10','default' => '10','is_price' => false],
            ['name' => 'The period during which the request will be canceled if the request is not assigned to any driver','key'=> 'request_canceled_if_not_assigned','value' => '10','default' => '10','is_price' => false],
            ['name' => 'The period during which the order will be converted to “delivered” automatically','key'=> 'order_convert_automatically','value' => '30','default' => '30','is_price' => false],
            ['name' => 'Driver percentage taken from the delivery price','key'=> 'driver_percentage_taken_from_delivery','value' => '25','default' => '25','is_price' => false],
        ]);
    }
}
