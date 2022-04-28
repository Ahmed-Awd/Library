<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('payment_methods')->insertOrIgnore(
            ['id'=>1,'name' => 'cash','photo' => 'cash.png']
        );
        DB::table('payment_methods')->insertOrIgnore(
            ['id'=>2,'name' => 'swish','photo' => 'swish.png']
        );
        DB::table('payment_methods')->insertOrIgnore(
            ['id'=>3,'name' => 'Kort/faktura','photo' => 'bambora.png']
        );
    
    }
}
