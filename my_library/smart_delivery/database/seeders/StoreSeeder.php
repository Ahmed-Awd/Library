<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Store;
use App\Models\StoreSetting;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => Str::random(25),
            'email' => Str::random(30)."@gmail.com",
            'username' => Str::random(12),
            'email_verified_at' => now(),
            'balance' => 1000000,
            'password' => bcrypt("123456"),
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole('owner');
        User::factory()->count(2)->create();
        $type = Type::first();
        $store = Store::factory()->withOwner($user->id)->create(['type_id' => $type->id]);
        Order::factory()->withStore($store->id)->count(50)->create(["status" => "1"]);
        Order::factory()->withStore($store->id)->count(40)->create(["status" => "2"]);
        Order::factory()->withStore($store->id)->count(30)->create(["status" => "3"]);
        Order::factory()->withStore($store->id)->count(20)->create(["status" => "4"]);
        Order::factory()->withStore($store->id)->count(10)->create(["status" => "5"]);
        Order::factory()->withStore($store->id)->count(10)->create(["status" => "6"]);
        StoreSetting::factory()->create(["store_id"=>$store->id]);

    }
}
