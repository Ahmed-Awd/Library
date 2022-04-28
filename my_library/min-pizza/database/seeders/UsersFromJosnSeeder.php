<?php

namespace Database\Seeders;

use App\Models\Address;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersFromJosnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ملاحظات
       /* 1- نحتاج اضافة رقم المطاعم ضمن بيانات users
        * 2- يوجد اكثر من بريد الكتروني متكرر ,ايضا فاضي
        * 3-كلمة سر فاضيه
        * 4- عنوان الaddress يوجد قيم فاضية
        */

        $json_user = file_get_contents(url('database/newest/User.json'));
        $users_olds = json_decode($json_user);
        // $cities=City::all();
        foreach ($users_olds as $users_old) {
            $checkuser=User::where('email', $users_old->Email)->first();
            $db_user_id=$this->objectToArray($users_old->_id);
            if ($checkuser) {
                $checkuser->update([
                    'name' => $users_old->FullName??'customer',
                    'phone' =>  ltrim($users_old->PhoneNumber, '0\+'),
                    'country_code' => ltrim($users_old->CountryCode, '0\+') ,
                    'is_disabled' =>  0,
                    'email_verified_at' => now(),
                    'password' => $users_old->Password??bcrypt('123456'),
                    'remember_token' => Str::random(10),
                    'mongo_db_restaurant_id' => $users_old->RestaurantId,
                    'mongo_db_id' => $db_user_id['$oid'],
                    // 'city_id' => $city? $city->id:null
                ]);
            } elseif (is_null($users_old->Email)) {
            } else {
                $user = User::create([
                'name' => $users_old->FullName??'customer',
                'email' =>  $users_old->Email,
                'phone' =>  ltrim($users_old->PhoneNumber, '0\+'),
                'country_code' => ltrim($users_old->CountryCode, '0\+') ,
                'is_disabled' =>  0,
                'email_verified_at' => now(),
                'password' => $users_old->Password??bcrypt('123456'),
                'remember_token' => Str::random(10),
                'mongo_db_restaurant_id' => $users_old->RestaurantId,
                'mongo_db_id' => $db_user_id['$oid'],
                // 'city_id' => $city? $city->id:null
                ]);

                if ($users_old->Role=='SuperAdmin') {
                    $user->assignRole('super_admin');
                } elseif ($users_old->Role=='Restaurant') {
                    $user->assignRole('owner');
                } elseif ($users_old->Role=='Rider') {
                    $user->assignRole('driver');
                } else {
                    $user->assignRole('customer');
                }

                foreach ($users_old->Addresses as $address) {
                    Address::create([
                    'title' => $address->Label??'home',
                    'lat' => $address->Latitude,
                    'lng' => $address->Longitude,
                    'user_id' =>$user->id ,
                    'ZIP_code' => $address->ZipCode  ,
                    'area'      => $address->Area,
                    'Apartment'      => $address->Apartment,
                    'description' => $address->FullAddress ,
                    ]);
                }
            }
        }
    }

    function objectToArray(&$object)
    {
        return @json_decode(json_encode($object), true);
    }

    /*
    الايميلات المكررة :
    arams@minpizza.online
    Ceders@minpizza.online
    Vigarda@minpizza.online
    eric.bager@hotmail.com
    stegro@hotmail.com
    ahmad.aboughoufa@gmail.com
    Mattahatta98@gmail.com
    jens.enqvist@hotmail.com
    Macruff@hotmail.com
    helen.nielsen@vasteras.se
    dig_mig50@hotmail.com
    Enqvist87@hotmail.com
    umeraslam81@gmail.com
    mujahid_iq@hotmail.com
    Teresasuomela@hotmail.com
    stefan.skold87@gmail.com
    bonita_mayra@hotmail.com
    simon_linder_@hotmail.com
    juliaedin01@hotmail.com
    bawaqnehmaram@gmail.com
    paulapozo03@gmail.com
    lindaehrlin@hotmail.com
    fredrik_elvgren@msn.com
    pkjellstrom@yahoo.com
    reallove@live.se
    andreasgust@hotmail.com
    lisa.barkskog@gmail.com
    Fippe01@gmail.com
    anettehaggstrom@hotmail.com
    Tobias.Patrik.andersson@hotmail.com
    joachim@heinemann.se
    Peterlundquist9@Hotmail.com
    fredsvan75@gmail.com
    danielalarsson@hotmail.com
    Love_sara90@hotmail.com
    pihlaiinen@hotmail.com
    rupen.s.cilingir@gmail.com
    marthinussen.ruben@outlook.com
    j-p0oljak@hotmail.com
    Eric.sjors.hulten@gmail.com
    sannasandii@hotmail.com
    dahlberg20@hotmail.com
    zein.oubari@gmail.com
    alfredcort99@gmail.com
    angelica.eklund-taavo@hotmail.com
    sophieljustell@hotmail.com
    jasmine_jungeblond@hotmail.com
    jonatanlarsson8@hotmail.se
    fofo93m@hotmail.com
    matmamma@hotmail.com
    robin__30@hotmail.com
    train_pro_84@hotmail.com
    westerlunds@live.se
    adam.jylanki@gmail.com
    katariina.andersson@hotmail.com
    hannes-pontelius@hotmail.com
    makeupby_jiji@hotmail.com
    marita.hammarberg@bredband.net
    hakansson2@icloud.com
    linabyberg@hotmail.com
    sussie_larsson70@hotmail.com
    lineforsen@hotmail.com
    Jesper.tomasson@gmail.com
    eliinthunberg@hotmail.com
    fares.lelo@icloud.com
    Akram.denno.123@gmail.com
    dellaofredrik@hotmail.com
    maryan_omar12@hotmail.com
    olsson.john02@gmail.com
    zannak78@hotmail.com
    matte.johansson86@gmail.com
    sofieankarswed@hotmail.com
    peterlundquist9@hotmail.com
    ivan_k89@hotmail.com
    bbabab@live.se
    joel.bergkvist123@gmail.com
    rvnjoel@gmail.com
    boegenbob@hotmail.com
    bojohanj93@hotmail.com
    lappen_693@hotmail.com
    */
    /*
    الايميلات الفاضية :
    5fd8538423469432ac75d66a
    5fe0a7da6a60263478a33ff1
    5fe239106a60263478a34214
    5fe5f51d6a60263478a34462
    5fe5f51d6a60263478a34462
    5fe67a036a60263478a345a9
    5fe76e886a60263478a3475b
    5ff093986a60263478a355c6
    5ff095056a60263478a355e6
    5ff0f6e76a60263478a35724
    5ff2f8b6d3e73d252009f496
    5ff615bed3e73d252009fa66
    60037b30f2786c5b7ae69c38
    600466c7f2786c5b7ae69d8a
    6005afbbf2786c5b7ae69f15
    600d4bf8f2786c5b7ae6a7c0
    601424bef2786c5b7ae6b22e
    60146263f2786c5b7ae6b39c
    6018205bf2786c5b7ae6ba09
    60184246a47ad01cad90da78
    601aaa5df2786c5b7ae6bcc4
    601fe642bf3604bbf54c2231
    60210b5ba47ad01cad90f7c4
    602d33f2313f7cc9e4e0af31
    602e9fe7313f7cc9e4e0b3ee
    602e9fe7313f7cc9e4e0b3ee
    6031657f313f7cc9e4e0bff9
    6033c7ee313f7cc9e4e0c666
    60354e1fe73e0cb5322dbfea
    6055ea60da68191322ab46fc
    605f2cce411ff88d48d35434
    606c749aee56c12c5acd70aa
    60819f39627ff1d0edb17cf2
    6083fa4e627ff1d0edb183c1
    60885f2571108bb90ec90047
    6091656f627ff1d0edb1a68c
    */
}
