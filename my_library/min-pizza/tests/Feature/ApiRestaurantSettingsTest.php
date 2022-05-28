<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Tests\TestCase;
use App\Models\RestaurantStatus;
use App\Models\RestaurantSetting;

class ApiRestaurantSettingsTest extends TestCase
{

    public function test_add_setting_restaurant_with_data_valid()
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
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/restaurants/setting/' . $restaurant->id,
             [
                    'taken_percentage_from_delivery' => '10',
                    'taken_percentage_from_takeaway' => '20',
                    'max_delivery_distance' => '120',
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_setting_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user_restaurant->assignRole('owner');
        $user->assignRole('super_admin');
        $restaurant = $user_restaurant->restaurant;
        RestaurantSetting::factory()->create([
            'restaurant_id' => $restaurant->id
            ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
        ->get('/api/restaurants/setting/' . $restaurant->id);
        $response->assertStatus(200);
    }
}
