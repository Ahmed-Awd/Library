<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            RestaurantStatusSeeder::class,
            DaysSeeder::class,
            TermsSeeder::class,
            GeneralSettingsSeeder::class,
            CountrySeeder::class,
            OrderStatusSeeder::class,
            newTownsSeeder::class,
            ModulesSeeder::class,
            CategoryFromJosnSeeder::class,
            PaymentMethodSeeder::class,
            TaxesFromJosnSeeder::class,
            UsersFromJosnSeeder::class,
//             RestaurantFromJosnSeeder::class,
//            Restaurant_1_FromJosnSeeder::class,
//            Restaurant_2_FromJosnSeeder::class,
//            Restaurant_3_FromJosnSeeder::class,
//            Restaurant_4_FromJosnSeeder::class,
        ]);
    }
}
