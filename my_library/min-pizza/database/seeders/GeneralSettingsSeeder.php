<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $trans = [
            "app_name" => ['en' => 'app name','sv' => 'app-namn'],
            "app_logo" => ['en' => 'app logo','sv' => 'app -logotyp'],
            "short_description" => ['en' => 'short description','sv' => 'kort beskrivning'],
            "contact_phone" => ['en' => 'contact us via phone','sv' => 'kontakta oss via telefon'],
            "contact_email" => ['en' => 'contact us via email','sv' => 'kontakta oss via e -post'],
            "facebook" => ['en' => 'facebook','sv' => 'facebook'],
            "twitter" => ['en' => 'twitter','sv' => 'twitter'],
            "instagram" => ['en' => 'instagram','sv' => 'instagram'],
            "no_action_taken_for_order" => ['en' => 'The time when the admin is notified that this order got no action'
                ,'sv' => 'Den tidpunkt då administratören meddelas att denna order inte fick någon åtgärd'],
            "cancel_the_no_action_order" => ['en' => 'The time after which orders are canceled for restaurants and a notification reaches the admin and the customer'
                ,'sv' => 'Tiden efter vilken beställningar avbryts för restauranger och ett meddelande når administratören och kunden'],
            "free_delivery_for_all" => ['en' => 'cancellation of delivery charges for all restaurants'
            ,'sv' => 'avbokning av leveransavgifter för alla restauranger'],
            "driver_max_time_for_receive" => ['en' => 'Determine the maximum time for the driver to receive the order'
                ,'sv' => 'Bestäm maximal tid för föraren att ta emot beställningen'],
            "technical_support_number" => ['en' => 'technical support number','sv' => 'Tekniskt supportnummer'],
            "time_for_send_scheduling_order_notfication" => [
                'en' => 'Time to send a scheduled order reminder notice',
                'sv' => 'Dags att skicka en schemalagd orderpåminnelse'],
            "time_for_send_no_driver_notification" => [
                "en" => "Time to send notification if there is no driver accepted  order",
                "sv" => "Dags att skicka meddelande om det inte finns någon beställning som godkänts av föraren",
            ],
            "max_radius_of_restaurant" => [
                "en" => "maximum radius to search for restaurant in",
                "sv" => "maximal radie att söka efter restaurang i",
            ],
            "max_radius_of_driver_in_order" => [
                "en" => "maximum radius to search for driver around restaurant",
                "sv" => "maximal radie för att söka efter förare runt restaurangen",
            ],
            "login_by_social_media" => [
                "en" => "login by social media",
                "sv" => "logga in via sociala medier",
            ],
            "customer_app_android_version" => [
                "en" => "android version of customer app",
                "sv" => "Android-versionen av kundappen",
            ],
            "customer_app_IOS_version" => [
                "en" => "IOS version of customer app",
                "sv" => "IOS-version av kundapp",
            ],
            "driver_app_android_version" => [
                "en" => "android version of driver app",
                "sv" => "Android-versionen av drivrutinsappen",
            ],
            "driver_app_IOS_version" => [
                "en" => "IOS version of driver app",
                "sv" => "IOS-version av drivrutinsappen",
            ],
            "restaurant_app_android_version" => [
                "en" => "android version of owner app",
                "sv" => "Android-version av restaurangägare-appen",
            ],
            "restaurant_app_IOS_version" => [
                "en" => "IOS version of owner app",
                "sv" => "IOS-version av restaurangägare-appen",
            ],

        ];

        DB::table('settings')->insertOrIgnore([
            ["key" => "app_name","type" => "string","name" => json_encode($trans["app_name"]),'value' => ""],
            ["key" => "app_logo","type" => "string","name" => json_encode($trans["app_logo"]),'value' => ""],
            ["key" => "short_description","type" => "string","name" =>json_encode($trans["short_description"])
                ,'value' => "" ],
            ["key" => "contact_phone","type" => "string","name" =>json_encode($trans["contact_phone"])
                ,'value' => '012158882354'],
            ["key" => "contact_email","type" => "string","name" =>json_encode($trans["contact_email"])
                ,'value' => 'smart@life.com'],
            ["key" => "facebook","type" => "string","name" =>json_encode($trans["facebook"])
                ,"value" => "https://www.facebook.com/SmartlifeturkEN"],
            ["key" => "twitter","type" => "string","name" =>json_encode($trans["twitter"])
                ,"value" => "https://www.facebook.com/SmartlifeturkEN"],
            ["key" => "instagram","type" => "string","name" =>json_encode($trans["instagram"])
                ,"value" => "https://www.facebook.com/SmartlifeturkEN"],
            ["key" => "no_action_taken_for_order","type" => "int"
                ,"name" =>json_encode($trans["no_action_taken_for_order"]),'value' => "2"],
            ["key" => "cancel_the_no_action_order","type" => "int"
                ,"name" =>json_encode($trans["cancel_the_no_action_order"]),'value' => "2"],
            ["key" => "free_delivery_for_all","type" => "bool"
                ,"name" => json_encode($trans["free_delivery_for_all"]),"value" => "false"],
            ["key" => "driver_max_time_for_receive","type" => "int"
                ,"name" =>json_encode($trans["driver_max_time_for_receive"]),'value' => "2"],
            ["key" => "technical_support_number","type" => "string"
                ,"name" =>json_encode($trans["technical_support_number"]),'value' => "2"],
            ["key" => "time_for_send_scheduling_order_notfication","type" => "int"
                ,"name" =>json_encode($trans["time_for_send_scheduling_order_notfication"]),'value' => "30"],
            ["key" => "time_for_send_no_driver_notification","type" => "int"
                ,"name" =>json_encode($trans["time_for_send_no_driver_notification"]),'value' => "10"],
            ["key" => "max_radius_of_restaurant","type" => "int"
                ,"name" =>json_encode($trans["max_radius_of_restaurant"]),'value' => "5"],
            ["key" => "max_radius_of_driver_in_order","type" => "int"
                ,"name" =>json_encode($trans["max_radius_of_driver_in_order"]),'value' => "6"],
            ["key" => "login_by_social_media","type" => "bool"
                ,"name" => json_encode($trans["login_by_social_media"]),"value" => "true"],
            ["key" => "customer_app_android_version","type" => "string"
                ,"name" => json_encode($trans["customer_app_android_version"]),"value" => "0.0.0"],
            ["key" => "customer_app_IOS_version","type" => "string"
                ,"name" => json_encode($trans["customer_app_IOS_version"]),"value" => "0.0.0"],
            ["key" => "driver_app_android_version","type" => "string"
                ,"name" => json_encode($trans["driver_app_android_version"]),"value" => "0.0.0"],
            ["key" => "driver_app_IOS_version","type" => "string"
                ,"name" => json_encode($trans["driver_app_IOS_version"]),"value" => "0.0.0"],
            ["key" => "restaurant_app_android_version","type" => "string"
                ,"name" => json_encode($trans["restaurant_app_android_version"]),"value" => "0.0.0"],
            ["key" => "restaurant_app_IOS_version","type" => "string"
                ,"name" => json_encode($trans["restaurant_app_IOS_version"]),"value" => "0.0.0"],
        ]);
    }
}
