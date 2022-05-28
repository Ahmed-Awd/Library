<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Tests\TestCase;
use App\Models\Store;
use App\Models\Type;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\RestaurantStatus;
use App\Models\Worktime;

class ApiRestaurantWorktimesTest extends TestCase
{

    public function test_add_worktimes_restaurant_with_data_valid()
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
         ->post('/api/restaurants/worktime/' . $restaurant->id, [
            'Worktimes' => [
                [
                    'day_id' => '1',
                    'open_from' => '8:00:00',
                    'open_to' => '11:00:00',
                    'takeaway' => '0',
                    'delivery' => '1',
                    'status' => '1'
                ],
                [
                    'day_id' => '2',
                    'open_from' => '8:00:00',
                    'open_to' => '11:00:00',
                    'takeaway' => '0',
                    'delivery' => '1',
                    'status' => '1'
                ],
                [
                    'day_id' => '3',
                    'open_from' => '8:00:00',
                    'open_to' => '11:00:00',
                    'takeaway' => '0',
                    'delivery' => '1',
                    'status' => '1'
                ]
            ]

        ]);
        $response->assertStatus(200);
    }
    public function test_get_worktimes_restaurant_with_data_valid()
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
        Worktime::factory()->create([
            'restaurant_id' => $restaurant->id
            ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
        ->get('/api/restaurants/worktime/' . $restaurant->id);
        $response->assertStatus(200);
    }
}
