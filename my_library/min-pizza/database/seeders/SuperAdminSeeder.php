<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $user = User::create([
                'name' => 'super pizza',
                'email' => 'super_admin@smartlife.ws',
                'password' => bcrypt('123456'),
                'email_verified_at' => Carbon::now(),
            ]);
            $user->assignRole('super_admin');
        } catch (\Exception $exception) {
            //do nothing
        }
    }
}
