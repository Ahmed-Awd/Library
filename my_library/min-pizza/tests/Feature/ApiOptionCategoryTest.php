<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\OptionCategory;
use App\Models\RestaurantStatus;

class ApiOptionCategoryTest extends TestCase
{

    public function test_add_option_categories_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'gwwalwd200@gmail.com',
                 'phone' => '73322344212',
                 'country_code' => '967'
             ]);
        $user->assignRole('owner');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/option-categories/',
             [
             'name' => 'size'  ,
             'selection' => 'single',
             'is_primary' => 1,
             'max_selectable' => 1,
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_option_categories_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'galalwd200@gmail.com',
                 'phone' => '77522344212',
                 'country_code' => '967'
             ]);
        $user->assignRole('owner');
        OptionCategory::factory()->create([
            'restaurant_id' => $user->restaurant->id,
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
         ->get('/api/option-categories/');
        $response->assertStatus(200);
    }
    public function test_update_option_categories_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'galalwe3d200@gmail.com',
                 'phone' => '775223444212',
                 'country_code' => '967'
             ]);
        ;
        $user->assignRole('owner');
        $optionCategory = OptionCategory::factory()->create([
            'restaurant_id' => $user->restaurant->id,
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->put(
             '/api/option-categories/' . $optionCategory->id,
             [
             'name' => 'color'  ,
             'selection' => 'single',
             'is_primary' => 0,
             'max_selectable' => 2,
             ]
         );
        $response->assertStatus(200);
    }
    public function test_delete_option_categories_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'galalwd2yiuru0@gmail.com',
                 'phone' => '775223443212',
                 'country_code' => '967'
             ]);
        ;
        $user->assignRole('owner');
        $optionCategory = OptionCategory::factory()->create([
            'restaurant_id' => $user->restaurant->id,
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/option-categories/' . $optionCategory->id);
        $response->assertStatus(200);
    }
}
