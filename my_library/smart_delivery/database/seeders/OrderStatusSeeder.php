<?php

namespace Database\Seeders;

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

        DB::table('order_statuses')->delete();
        DB::table('order_statuses')->insertOrIgnore([
            ['id'=>1, 'name' => 'Pending','value' => json_encode(["en" => "pending","tr" => "Beklemede","ar" => "قيد الانتظار"])],
            ['id'=>2, 'name' => 'On the way','value' => json_encode(["en" => "On the way","tr" => "Kurye Yolda","ar" => "فى الطريق أليك"])],
            ['id'=>3, 'name' => 'Delivering','value' => json_encode(["en" => "Delivering","tr" => "Kuryeye Teslim Edildi","ar" => "جارى التوصيل"])],
            ['id'=>4, 'name' => 'Delivered','value' => json_encode(["en" => "Delivered","tr" => "Müşteriye Teslim Edildi","ar" => "تم التوصيل"])],
            ['id'=>5, 'name' => 'Canceled','value' => json_encode(["en" => "Canceled","tr" => "İptal Edildi","ar" => "تم الإلغاء"])],
            ['id'=>6, 'name' => 'Suspended','value' => json_encode(["en" => "Suspended","tr" => "Askıya Alındı","ar" => "معلقة"])],
        ]);
    }
}
