<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use Tests\TestCase;

class ApiCustomerTest extends TestCase
{

    public function test_add_customers_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $city = City::first();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/customers/',
             [
             'name' => 'galal',
             'email' => 'gala787870@gmail.com',
             'phone' => '77241852733372',
             'country_code' => '967',
             'password' => '123456',
             'password_confirmation' => '123456',
             'city_id' => $city->id
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_customers_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/customers/');
        $response->assertStatus(200);
    }
    public function test_delete_customers_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $city = City::first();
        $user = User::factory()->create();
        $customer = User::factory()->create([
            'city_id' => $city->id
        ]);
        $user->assignRole('super_admin');
        $customer->assignRole('customer');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/customers/' . $customer->id);
        $response->assertStatus(200);
    }

    public function test_update_customers_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $city = City::first();
        $user = User::factory()->create();
        $customer = User::factory()->create([
            'city_id' => $city->id
        ]);
        $user->assignRole('super_admin');
        $customer->assignRole('customer');
        $token = $customer->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->put(
             '/api/customers/' . $customer->id,
             [
             'name' => 'galal',
             'phone' => '45489545454',
             'country_code' => '967',
             'city_id' => $city->id
             ]
         );
        $response->assertStatus(200);
    }
}
