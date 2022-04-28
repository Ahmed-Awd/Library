<?php

namespace Database\Seeders;

use App\Models\OptionCategory;
use App\Models\OptionValue;
use Illuminate\Database\Seeder;

class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name=array(
            'en' => "size",
            'sv' => "storlek",
        );
        $name = json_encode($name);
        $size = OptionCategory::create([
            "name" => $name,
            'selection' => "single"
        ]);
        OptionValue::create([
            "name" => "small",
            "option_category_id" => $size->id,
            "price" => 5.00
        ]);
        OptionValue::create([
            "name" => "medium",
            "option_category_id" => $size->id,
            "price" => 7.50
        ]);
        OptionValue::create([
            "name" => "large",
            "option_category_id" => $size->id,
            "price" => 9.00
        ]);
    }
}
