<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(5)->create();
        User::factory()->count(30)->create();
        Tax::factory()->count(3)->create();
        // $this->call([
        //     OptionsSeeder::class,
        // ]);
        for ($i=0; $i<35; $i++) {
            $this->call([
                RestaurantSeeder::class,
            ]);
        }
    }
}
