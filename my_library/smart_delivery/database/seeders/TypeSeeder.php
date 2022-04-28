<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arabic_tr = new GoogleTranslate('ar');
        $turkish_tr = new GoogleTranslate('tr');
        $types = Type::all();
        foreach ($types as $type){
//            if($type->stores()->count() == 0){
//                $type->forceDelete();
//            }
            $name = [
                "ar" => $arabic_tr->translate($type->name),
                "en" => $type->name,
                "tr" => $turkish_tr->translate($type->name)
            ];
            $type->update([
                "trans_name" => json_encode($name)
            ]);
            sleep(2);
        }
    }
}
