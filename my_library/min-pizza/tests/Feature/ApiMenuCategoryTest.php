<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\OptionCategory;
use App\Models\User;
use Tests\TestCase;
use App\Models\MenuCategory;
use App\Models\RestaurantStatus;

class ApiMenuCategoryTest extends TestCase
{

    public function test_add_menu_category_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'gal887al200@gmail.com',
                 'phone' => '77888223442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $restauran = $user_restaurant->restaurant;
        Menu::factory()->create([
            'restaurant_id' => $restauran->id
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/menu/category/' . $restauran->id,
             [
             'name' => 'food',
             'description' => "good food",
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_all_menu_of_restaurant_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'gal855al200@gmail.com',
                 'phone' => '7788874223442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $restauran = $user_restaurant->restaurant;
        Menu::factory()
        ->hasCategories(4)->create([
            'restaurant_id' => $restauran->id
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
         ->get('/api/menu/' . $restauran->id);
        $response->assertStatus(200);
    }
    public function test_get_menu_category_of_restaurant_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'gal855a54l200@gmail.com',
                 'phone' => '77823123442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $restauran = $user_restaurant->restaurant;
        Menu::factory()
        ->hasCategories(4)->create([
            'restaurant_id' => $restauran->id
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
         ->get('/api/menu/restaurant/categories/' . $restauran->id);
        $response->assertStatus(200);
    }

    public function test_get_category_of_restaurant_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'gal83232154l200@gmail.com',
                 'phone' => '777889123442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $restauran = $user_restaurant->restaurant;
        Menu::factory()
        ->hasCategories(4)->create([
            'restaurant_id' => $restauran->id
        ]);
        $menu_category = MenuCategory::first();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
         ->get('/api/menu/category/' . $menu_category->id);
        $response->assertStatus(200);
    }
    public function test_update_category_of_restaurant_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'galhhjka54l200@gmail.com',
                 'phone' => '7732123442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $restauran = $user_restaurant->restaurant;
        $menu = Menu::factory()
        ->hasCategories(4)->create([
            'restaurant_id' => $restauran->id
        ]);
        $menu_category = MenuCategory::first();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->patch(
             '/api/menu/category/' . $menu_category->id,
             [
             'name' => 'new category menu',
             'description' => 'new category description',
             'menu_id' => $menu->id,
             ]
         );
        $response->assertStatus(200);
    }
    public function test_delete_category_of_restaurant_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'gal6633a54l200@gmail.com',
                 'phone' => '77823188442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $restauran = $user_restaurant->restaurant;
        Menu::factory()
        ->hasCategories(4)->create([
            'restaurant_id' => $restauran->id
        ]);
        $menu_category = MenuCategory::first();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/menu/category/' . $menu_category->id);
        $response->assertStatus(200);
    }
}
