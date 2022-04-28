<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('order_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Pending",
                    "sv" => "I väntan på"
                ])
            ]);
            DB::table('order_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Accepted",
                    "sv" => "laga mat"
                ])
            ]);
            DB::table('order_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Ready For Delivery",
                    "sv" => "Redo för leverans"
                ])
            ]);
            DB::table('order_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Accepted By Driver",
                    "sv" => "Godkänd av föraren"
                ])
            ]);

            DB::table('order_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "On The Way",
                    "sv" => "På väg"
                ])
            ]);
            DB::table('order_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Ready For Takeaway",
                    "sv" => "Redo för takeaway"
                ])
            ]);
            DB::table('order_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Delivered",
                    "sv" => "Levereras"
                ])
            ]);
            DB::table('order_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Completed",
                    "sv" => "Avslutad"
                ])
            ]);
            DB::table('order_statuses')->insertOrIgnore([
                "name" => json_encode([
                    "en" => "Rejected",
                    "sv" => "avvisade"
                ])
            ]);
    }
}
