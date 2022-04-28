<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Database\Seeder;

class UpdateComissionOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::all();
        foreach ($orders as $order) {
            $rest=$order->restaurant;
            if($rest->setting)
            {
                if($order->service_info_type)
                    $order->comission_percentage=$rest->setting->taken_percentage_from_delivery;
                else
                   $order->comission_percentage=$rest->setting->taken_percentage_from_takeaway;
                
                $order->comission_amount=$order->total_amount*($order->comission_percentage/100);
                $order->save();
            }
        }
    }
}
