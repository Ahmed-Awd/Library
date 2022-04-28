<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use PharIo\Version\Exception;
use PhpParser\Error;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            try{
                $user = User::create([
                    'name' => 'Smart Life',
                    'email' => 'admin@smartlife.ws',
                    'username' => 'smartlife',
                    'password' => bcrypt('123456'),
                ]);
                $user->assignRole('admin');
            }catch (\Exception $exception){
                //do nothing
            }

    }
}
