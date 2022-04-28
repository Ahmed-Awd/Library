<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
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
        $trans=["login_by_social_media" => [
            "en" => "login by social media",
            "sv" => "logga in via sociala medier",
        ]];
        $setting= Setting::where("key","login_by_social_media")->first();   
        $setting->name= json_encode($trans["login_by_social_media"]);
        $setting->save();
    }
}
