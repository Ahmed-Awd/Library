<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class StoreSettingSeeder extends Seeder
{

    public function run()
    {
        $stores = Store::all();
        foreach ($stores as $one){
            StoreSetting::create([
                'delivery_perice_percentage' => 0,
                'taken_percentage_from_store' => null ,
                "store_id" => $one->id,
                ]);
        }
    }
}
