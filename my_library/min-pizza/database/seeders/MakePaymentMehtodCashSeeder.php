<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MakePaymentMehtodCashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('restaurant_payment_methods')->delete();
        
       $restaurants=Restaurant::all();
        foreach($restaurants as $restaurant)
        {
        $restaurant->paymentMethods()->syncWithoutDetaching([1]);
        }

    }
}
