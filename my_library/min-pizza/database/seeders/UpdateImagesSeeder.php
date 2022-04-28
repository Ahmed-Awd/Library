<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE categories ALTER image SET DEFAULT 'min_pizza.png';");
        DB::statement("ALTER TABLE items ALTER image SET DEFAULT 'min_pizza.png';");
        DB::statement("ALTER TABLE sliders ALTER image SET DEFAULT 'min_pizza.png';");
        DB::statement("ALTER TABLE restaurants ALTER image SET DEFAULT 'min_pizza.png';");
        DB::statement("ALTER TABLE restaurants ALTER logo SET DEFAULT 'min_pizza.png';");

        DB::table('categories')->update(["image" => "min_pizza.png"]);
        DB::table('items')->update(["image" => "min_pizza.png"]);
        DB::table('sliders')->update(["image" => "min_pizza.png"]);
        DB::table('restaurants')->update(["image" => "min_pizza.png","logo" => "min_pizza.png"]);
        DB::table('users')->update(["profile_photo_path" => "min_pizza.png"]);
    }
}
