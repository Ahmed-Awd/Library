<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Laravel\Jetstream\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            TypeSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            OrderStatusSeeder::class,
            UpdateSettingSeeder::class,
        ]);
    }
}
