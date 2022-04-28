<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name' => "sally ".Str::random(4),
            'email' => "test".rand(10,99).'@gmail.com',
            'username' => "TheImaginary".rand(10,99),
            'id_number' => "1231235567".rand(10,99),
            'is_suspended' => 0,
            'category_id' => 1,
            'branch_id' => 1,
            'nationality' => "egyptian",
            'phone' => "01115913".rand(100,800),
            'address' => Str::random(40),
            'status' => 1,
            'password' => Hash::make('123456'),
        ];
        $current = User::create($data);
        $current->assignRole("student");
    }
}
