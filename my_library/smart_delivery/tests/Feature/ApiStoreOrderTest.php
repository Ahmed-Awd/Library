<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Store;
use App\Models\Type;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiStoreOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_order_if_user_login_valid()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/driver/order');
        $response->assertStatus(200);
    }
    public function test_add_order_if_user_have_store_and_have_not_balance()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $user = User::factory()
            ->hasStore(1, [
                'type_id' => $type->id,
            ])
            ->create();
        $user->assignRole('owner');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        settings()->set('initial_price', 5);
        settings()->set('additional_price', 3);
        settings()->set('taken_percentage_from_delivery', 25);
        settings()->set('initial_distance', 2);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post('/api/store/order', [
            'customer_name' => 'galal',
            'location' => ['lat' => 40.689247,'lng' => -74.044502 ],
            'customer_address' => 'here in the same place',
            'total_price' => '200',
            'building_no' => '20',
            'apartment_no' => '20',
            'customer_mobile' => '771772771',
            'expected_time' => now()->addHour(),
            'comment' => 'ok',
        ]);
        $response->assertStatus(400);
    }
    public function test_add_order_if_user_have_store_and_have_balance()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $user = User::factory()
            ->hasStore(1, [
                'type_id' => $type->id,
            ])
            ->create(['balance' => 2000]);
        $user->assignRole('owner');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        settings()->set('initial_price', 5);
        settings()->set('additional_price', 3);
        settings()->set('taken_percentage_from_delivery', 25);
        settings()->set('initial_distance', 2);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post('/api/store/order', [
            'customer_name' => 'galal',
            'location' => ['lat' => 40.689247,'lng' => -74.044502 ],
            'customer_address' => 'here in the same place',
            'total_price' => '200',
            'building_no' => '20',
            'apartment_no' => '20',
            'customer_mobile' => '771772771',
            'expected_time' => now()->addHour(),
            'comment' => 'ok',
        ]);
        $response->assertStatus(200);
    }
    public function test_add_order_if_user_not_have_store()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('owner');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post('/api/store/order', [
            'customer_name' => 'galal',
            'location' => ['lat' => 40.689247,'lng' => -74.044502 ],
            'customer_address' => 'here in the same place',
            'total_price' => '200',
            'building_no' => '20',
            'apartment_no' => '20',
            'customer_mobile' => '771772771',
            'expected_time' => now()->addHour(),
            'comment' => 'ok',
        ]);
        $response->assertStatus(401);
    }

    public function test_rate_order()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $user = User::factory()->create();
        $user->assignRole('owner');
        $store = store::factory()->for($user, 'owner')->hasOrders(3)->create([
            'type_id' => $type->id,
        ]);
        $order = Order::factory()->create(
            [
                'store_id' => $store->id,
            ]
        );
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post('/api/store/order/rate/' . $order->id, [
            'rate' => 5,
        ]);
        $response->assertStatus(200);
    }
    public function test_store_reorder_have_not_balance()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $user = User::factory()->create();
        $user->assignRole('owner');
        $store = store::factory()->for($user, 'owner')->hasOrders(3)->create([
            'type_id' => $type->id,
        ]);
        $order = Order::factory()->create(
            [
                'store_id' => $store->id,
                'status' => 6,
            ]
        );
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/store/reorder/' . $order->id);
        $response->assertStatus(400);
    }
}
