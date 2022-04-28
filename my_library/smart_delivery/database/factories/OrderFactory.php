<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_name'      => $this->faker->name,
            'customer_lng'       => -74.044502,
            'customer_lat'       => 40.689247,
            'customer_address'   => Str::random(12),
            'customer_mobile'   => rand(100000, 999999),
            'total_price'   => rand(100, 999),
            'building_no'   => rand(100, 999),
            'qr_code'   => rand(100, 999),
            'delivery_price'   => rand(100, 999),
            'order_number'   => rand(100, 999),
            'apartment_no'   => rand(100, 999),
            'distance_store_order'   => rand(100, 999),
            'delivery_fee_payed_by_store'   => rand(100, 999),
            'status'   => 1,
            'expected_time'   => now(),
            'comment'   => Str::random(12),
            'created_at' => $this->faker->dateTimeBetween('-60 days', now())

        ];
    }

    public function withStore($store_id)
    {
        $store = Store::findOrFail($store_id);
        return $this->state([
            'store_id'  => $store->id,
            'status'   => rand(1,6),
            'driver_id' => User::role('driver')->inRandomOrder()->first()
        ]);
    }
}
