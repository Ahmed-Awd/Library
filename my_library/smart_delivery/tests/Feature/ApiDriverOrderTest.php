<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Store;
use App\Models\Type;
use App\Models\Order;
use Tests\TestCase;

class ApiDriverOrderTest extends TestCase
{
    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_order_if_user_login_valid()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        store::factory()->for(User::factory()->create(), 'owner')->hasOrders(3)->make([
            'type_id' => $type->id,
        ]);
        $user = User::factory()->create();
        $user->assignRole('driver');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/driver/order');
        $response->assertStatus(200);
    }
    public function test_get_new_order()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $post = store::factory()->for(User::factory()->create(), 'owner')->hasOrders(3)->make([
            'type_id' => $type->id,
        ]);
        $user = User::factory()->create();
        $user->assignRole('driver');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/driver/order/new');
        $response->assertStatus(200);
    }

    public function test_get_order()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $post = store::factory()->for(User::factory()->create(), 'owner')->hasOrders(3)->make([
            'type_id' => $type->id,
        ]);
        $user = User::factory()->create();
        $user->assignRole('driver');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/driver/order');
        $response->assertStatus(200);
    }

    public function test_accept_order_found()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $store = store::factory()->for(User::factory()->create(), 'owner')->hasOrders(3)->create([
            'type_id' => $type->id,
        ]);
        $user = User::factory()->create(['balance' => 300]);
        $user->assignRole('driver');
        settings()->set('driver_percentage_taken_from_delivery', 25);
        $order = $store->orders->first();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/driver/accept/' . $order->id);
        $response->assertStatus(200);
    }
    public function test_delivery_order_have_qr_code_for_anther_driver()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $store = store::factory()->for(User::factory()->create(), 'owner')->hasOrders(3)->create([
            'type_id' => $type->id,
        ]);
        $order = Order::factory()->create(
            [
                'status' => 2,
                'store_id' => $store->id,
                'qr_code' => 1122,
            ]
        );
        $user = User::factory()->create();
        $user->assignRole('driver');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/driver/delivery/' . $order->id,
             [
             'qr_code' => 1122,
             ]
         );
        $response->assertStatus(401);
    }
    public function test_delivery_order_have_qr_code()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $store = store::factory()->for(User::factory()->create(), 'owner')->hasOrders(3)->create([
            'type_id' => $type->id,
        ]);
        $user = User::factory()->create();
        $user->assignRole('driver');
        $order = Order::factory()->create(
            [
                'status' => 2,
                'store_id' => $store->id,
                'qr_code' => 1122,
                'driver_id' => $user->id,
            ]
        );

        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/driver/delivery/' . $order->id,
             [
             'qr_code' => 1122,
             ]
         );
        $response->assertStatus(200);
    }
    public function test_delivered_order()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $store = store::factory()->for(User::factory()->create(), 'owner')->hasOrders(3)->create([
            'type_id' => $type->id,
        ]);
        $user = User::factory()->create();
        $user->assignRole('driver');
        $order = Order::factory()->create(
            [
                'status' => 3,
                'store_id' => $store->id,
                'qr_code' => 1122,
                'driver_id' => $user->id,
            ]
        );

        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/driver/delivered/' . $order->id);
        $response->assertStatus(200);
    }
    public function test_delivered_order_before_delivery()
    {
        $this->withoutExceptionHandling();
        $type = Type::first();
        $store = store::factory()->for(User::factory()->create(), 'owner')->hasOrders(3)->create([
            'type_id' => $type->id,
        ]);
        $user = User::factory()->create();
        $user->assignRole('driver');
        $order = Order::factory()->create(
            [
                'status' => 2,
                'store_id' => $store->id,
                'qr_code' => 1122,
                'driver_id' => $user->id,
            ]
        );

        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/driver/delivered/' . $order->id);
        $response->assertStatus(401);
    }
}
