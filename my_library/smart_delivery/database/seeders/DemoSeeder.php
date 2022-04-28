<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            StoreSeeder::class,
            StoreSeeder::class,
            StoreSeeder::class,
        ]);
        Package::factory()->count(3)->create(['type' => 'owner']);
        Package::factory()->count(3)->create(['type' => 'driver']);

    }
}
