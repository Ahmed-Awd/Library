<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use Tests\TestCase;
use App\Models\Driver;
use App\Models\RestaurantStatus;

class ApiDriverTest extends TestCase
{

    public function test_add_drivers_restaurant_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $city = City::first();
        $status = RestaurantStatus::first();
        $user = User::factory()->create();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user_restaurant->assignRole('owner');
        $restaurant = $user_restaurant->restaurant;
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/drivers/',
             [
             'name' => 'galal',
             'email' => 'ga4la7855470@gmail.com',
             'phone' => '7752241733372',
             'country_code' => '967',
             'password' => '123456',
             'password_confirmation' => '123456',
             'restaurant_id' => $restaurant->id,
             'type' => 'app',
             'city_id' => $city->id
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_drivers_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/drivers/');
        $response->assertStatus(200);
    }
    public function test_update_drivers_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $city = City::first();
        $status = RestaurantStatus::first();
        $user = User::factory()->create();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user_restaurant->assignRole('owner');
        $restaurant = $user_restaurant->restaurant;
        $user_driver = User::factory()
        ->hasDriver(1, [
            'restaurant_id' => $restaurant->id,
            'type' => 'app',
        ])->create([
            'city_id' => $city->id
        ]);
        $user->assignRole('super_admin');
        $driver = $user_driver->driver;
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->put(
             '/api/drivers/' . $driver->id,
             [
             'name' => 'galal',
             'phone' => '454641545454',
             'country_code' => '967',
             'restaurant_id' => $restaurant->id,
             'type' => 'app',
             'city_id' => $city->id
             ]
         );
        $response->assertStatus(200);
    }
    public function test_delete_drivers_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $city = City::first();
        $user = User::factory()->create();
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user_restaurant->assignRole('owner');
        $restaurant = $user_restaurant->restaurant;
        $user_driver = User::factory()
        ->hasDriver(1, [
            'restaurant_id' => $restaurant->id,
            'type' => 'app',
        ])->create([
            'city_id' => $city->id
        ]);
        $user->assignRole('super_admin');
        $driver = $user_driver->driver;
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/drivers/' . $driver->id);
        $response->assertStatus(200);
    }
    public function test_change_status_drivers_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $city = City::first();
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user_restaurant->assignRole('owner');
        $restaurant = $user_restaurant->restaurant;
        $user = User::factory()
        ->hasDriver(1, [
            'restaurant_id' => $restaurant->id,
            'type' => 'app',
        ])->create([
            'city_id' => $city->id
        ]);
        $user->assignRole('driver');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post('/api/driver/change-status/');
        $response->assertStatus(200);
    }
}
