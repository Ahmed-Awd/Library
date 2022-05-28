<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Tests\TestCase;
use App\Models\RestaurantStatus;
use App\Models\RestaurantRating;

class ApiRestaurantRatingsTest extends TestCase
{

    public function test_add_rating_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()->create();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user_restaurant->assignRole('owner');
        $user->assignRole('customer');
        $restaurant = $user_restaurant->restaurant;
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/restaurants/rating/' . $restaurant->id,
             [
                    'rate' => 6,
                    'comment' => "good",
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_rating_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()->create();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user_restaurant->assignRole('owner');
        $user->assignRole('customer');
        $restaurant = $user_restaurant->restaurant;
        RestaurantRating::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/restaurants/rating/' . $restaurant->id);
        $response->assertStatus(200);
    }
    public function test_delete_rating_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()->create();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user_restaurant->assignRole('owner');
        $user->assignRole('super_admin');
        $restaurant = $user_restaurant->restaurant;
        $restaurant_rate = RestaurantRating::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/restaurants/rating/' . $restaurant_rate->id);
        $response->assertStatus(200);
    }
}
