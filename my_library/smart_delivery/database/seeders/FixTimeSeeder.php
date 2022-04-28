<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\PackageCode;
use Illuminate\Database\Seeder;

use function PHPUnit\Framework\isEmpty;

class FixTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::where('id', '<', 8674)->get();
        foreach ($orders as $order) {
            $order->sent_at = $order->sent_at ? $order->sent_at->addHours(3) : $order->sent_at;
            $order->updated_at = $order->updated_at ? $order->updated_at->addHours(3) : $order->updated_at;
            $order->created_at = $order->created_at->addHours(3);
            $order->save();
            $this->command->info("finished order nu $order->id");
        }

        $codes = PackageCode::all();
        foreach ($codes as $code) {
            if($code->purchased_at != null){
                $code->purchased_at = $code->purchased_at->addHours(3);
            }
            $code->created_at = $code->created_at->addHours(3);
            $code->save();
            $this->command->info("finished code nu $code->id");
        }

    }
}
