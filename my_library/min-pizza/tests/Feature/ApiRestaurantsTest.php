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

class ApiRestaurantsTest extends TestCase
{

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_restaurant_if_user_login_valid()
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
         ->get('/api/restaurants');
        $response->assertStatus(200);
    }

    public function test_add_restaurant_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $city = City::first();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post('/api/restaurants', [
            'owner_name' => 'galal',
            'owner_email' => 'galal@gmail.com',
            'phone' => '775224132',
            'country_code' => '967',
            'address' => 'in the same place',
            'min_order_price' => '10',
            'location' => ['lat' => 40.689247,'lng' => -74.044502 ],
            'restaurant_name' => 'BackR',
            'company_name' => 'NCM',
            'company_number' => '200',
            'prepare_order_time' => 20,
            'vat' => '20',
            'status_id' => $status->id,
            'city_id' => $city->id,
            'ZIP_code' => 0000,
            'delivery_type' => 'free',
            'delivery_value' => '',
            'delivery_kilometer' => '',
            'phones' => [
                [
                    'phone' => '775224562',
                    'country_code' => '967'
                ],
                [
                    'phone' => '771775754',
                    'country_code' => '967'
                ],
            ],
            'categories' => [
                [
                    'id' => $category->id,
                ]
            ],

        ]);
        $response->assertStatus(200);
    }
    public function test_add_restaurant_with_data_invalid()
    {
        // $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $city = City::first();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post('/api/restaurants', [
            'owner_name' => 'galal',
            'owner_email' => 'galal@gmail.com',
            'phone' => '775224132',
            'country_code' => '967',
            'address' => 'in the same place',
            'min_order_price' => '10',
            'location' => ['lat' => 40.689247,'lng' => -74.044502 ],
            'restaurant_name' => 'BackR',
            'company_name' => 'NCM',
            'company_number' => '200',
            'prepare_order_time' => 20,
            'vat' => '20',
            'status_id' => 10,
            'city_id' => $city->id,
            'ZIP_code' => 0000,
            'delivery_type' => 'free',
            'delivery_value' => '',
            'delivery_kilometer' => '',
            'phones' => [
                [
                    'phone' => '775224562',
                    'country_code' => '967'
                ],
                [
                    'phone' => '771775754',
                    'country_code' => '967'
                ],
            ],
            'categories' => [
                [
                    'id' => $category->id,
                ]
            ],

        ]);
        $response->assertStatus(302);
    }
    public function test_update_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $city = City::first();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'galal200@gmail.com',
                 'phone' => '775223442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $user->assignRole('super_admin');
        $restaurant = $user_restaurant->restaurant;
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->patch('/api/restaurants/' . $restaurant->id, [
            'owner_name' => 'galal',
            'owner_email' => 'galal200@gmail.com',
            'phone' => '775223442',
            'country_code' => '967',
            'address' => 'in the same place',
            'min_order_price' => '10',
            'location' => ['lat' => 40.689247,'lng' => -74.044502 ],
            'restaurant_name' => 'BackR',
            'company_name' => 'NCM',
            'company_number' => '200',
            'vat' => '20',
            'prepare_order_time' => 20,
            'status_id' => $status->id,
            'city_id' => $city->id,
            'ZIP_code' => 0000,
            'delivery_type' => 'free',
            'updated_user_id' => $user_restaurant->id,
            'delivery_value' => '',
            'delivery_kilometer' => '',
            'phones' => [
                [
                    'phone' => '775224562',
                    'country_code' => '967'
                ],
                [
                    'phone' => '771775754',
                    'country_code' => '967'
                ],
            ],
            'categories' => [
                [
                    'id' => $category->id,
                ]
            ],

        ]);
        $response->assertStatus(200);
    }
    public function test_update_status_restaurant_with_data_valid()
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
         ->post('/api/restaurants/status/' . $restaurant->id, [
            'status_id' => $status->id
         ]);
        $response->assertStatus(200);
    }
    public function test_update_restaurant_by_email_found()
    {
        $status = RestaurantStatus::first();
        $city = City::first();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'galal20@gmail.com',
                 'phone' => '775223442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $user->assignRole('super_admin');
        $restaurant = $user_restaurant->restaurant;
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->patch('/api/restaurants/' . $restaurant->id, [
            'owner_name' => 'galal',
            'owner_email' => 'galal@gmail.com',
            'phone' => '775223442',
            'country_code' => '967',
            'address' => 'in the same place',
            'min_order_price' => '10',
            'location' => ['lat' => 40.689247,'lng' => -74.044502 ],
            'restaurant_name' => 'BackR',
            'company_name' => 'NCM',
            'company_number' => '200',
            'vat' => '20',
            'status_id' => $status->id,
            'city_id' => $city->id,
            'prepare_order_time' => 20,
            'ZIP_code' => 0000,
            'delivery_type' => 'free',
            'updated_user_id' => $user_restaurant->id,
            'delivery_value' => '',
            'delivery_kilometer' => '',
            'phones' => [
                [
                    'phone' => '775224562',
                    'country_code' => '967'
                ],
                [
                    'phone' => '771775754',
                    'country_code' => '967'
                ],
            ],
            'categories' => [
                [
                    'id' => $category->id,
                ]
            ],

        ]);
        $response->assertStatus(302);
    }
    public function test_delete_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::first();
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
         ->delete('/api/restaurants/' . $restaurant->id);
        $response->assertStatus(200);
    }
}
