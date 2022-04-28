<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Khsing\World\World;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CountrySeeder extends Seeder
{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cities')->truncate();
        DB::table('countries')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $countries = World::Countries();
//        $swedish_tr = new GoogleTranslate('sv');
        foreach ($countries as $country) {
            $record = Country::create([
                "name" => translate($country->name),
                "code" => $country->code,
                "code_alpha3" => $country->code_alpha3,
                "calling_code" => $country->callingcode,
                "currency_code" => $country->currency_code,
                "currency_name" => $country->currency_name
            ]);
            $counter = 0;
            usleep(1000000);
            foreach ($country->children() as $city) {
                City::create([
                    "country_id" => $record->id,
                    "name" => translate($city->name),
                    "code" => $city->code,
                ]);
                $counter++;
                if ($counter == 10 && $record->id != 3) {
                    break;
                }
                usleep(500000);
            }
        }
    }
}
