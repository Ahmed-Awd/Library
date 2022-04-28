<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class activateOwnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::role('owner')->update(['email_verified_at' => Carbon::now()]);
        //"email_verified_at" => Carbon::now(),
    }
}
