<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddsocialSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insertOrIgnore([
            ['name' => 'FaceBook link','key'=> 'facebook','value' => 'https://www.facebook.com/smartdeliverytr','default' => 'https://www.facebook.com/smartdeliverytr','is_price' => false],
            ['name' => 'Instagram link','key'=> 'instagram','value' => '','default' => '','is_price' => false],
            ['name' => 'Twitter link','key'=> 'twitter','value' => '','default' => '','is_price' => false],
            ['name' => 'YouTube link','key'=> 'youtube','value' => '','default' => '','is_price' => false],
            ['name' => 'linkedIn link','key'=> 'linkedIn','value' => '','default' => '','is_price' => false],
            ['name' => 'whats app','key'=> 'whats_app','value' => '','default' => '','is_price' => false],
            ['name' => 'App Store link','key'=> 'app_store','value' => '','default' => '','is_price' => false],
            ['name' => 'Google Store link','key'=> 'google_store','value' => '','default' => '','is_price' => false],
            ['name' => 'Huawei AppGallery link','key'=> 'huawei_app_gallery','value' => '','default' => '','is_price' => false],
            ['name' => 'email','key'=> 'email','value' => '','default' => '','is_price' => false],
        ]);
    }
}
