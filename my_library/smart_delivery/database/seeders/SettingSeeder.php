<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insertOrIgnore([
            ['name' => 'Store percentage taken from the delivery price','key'=> 'taken_percentage_from_delivery','value' => '25','default' => '25','is_price' => false],
            ['name' => 'Initial delivery price','key'=> 'initial_price','value' => '500','default' => '500','is_price' => true],
            ['name' => 'Delivery price per additional kilometer','key'=> 'additional_price','value' => '300','default' => '300','is_price' => true],
            ['name' => 'The period during which the admin will be notified if the request is not assigned to any driver','key'=> 'admin_notified_if_request_not_assigned','value' => '10','default' => '10','is_price' => false],
            ['name' => 'The period during which the request will be canceled if the request is not assigned to any driver','key'=> 'request_canceled_if_not_assigned','value' => '10','default' => '10','is_price' => false],
            ['name' => 'The period during which the order will be converted to “delivered” automatically','key'=> 'order_convert_automatically','value' => '30','default' => '30','is_price' => false],
            ['name' => 'Driver percentage taken from the delivery price','key'=> 'driver_percentage_taken_from_delivery','value' => '25','default' => '25','is_price' => false],
            ['name' => 'activation code expires in','key'=> 'activation_code_expire','value' => '5','default' => '5','is_price' => false],
            ['name' => 'the current version of driver android','key'=> 'driver_app_android_ver','value' => '0.0','default' => '0','is_price' => false],
            ['name' => 'the current version of driver IOS','key'=> 'driver_app_IOS_ver','value' => '0.0','default' => '0','is_price' => false],
            ['name' => 'the current version of owner android','key'=> 'owner_app_android_ver','value' => '0.0','default' => '0','is_price' => false],
            ['name' => 'the current version of owner IOS','key'=> 'owner_app_IOS_ver','value' => '0.0','default' => '0','is_price' => false],
            ['name' => 'the radius of order in KM','key'=> 'radius_of_order','value' => '5','default' => '0','is_price' => false],
            ['name' => 'the radius of end delivery in Meter','key'=> 'radius_of_deliver','value' => '100','default' => '100','is_price' => false],
            ['name' => 'support number','key'=> 'support_number','value' => '00905528711793','default' => '00905528711793','is_price' => false],
        ]);
    }
}
