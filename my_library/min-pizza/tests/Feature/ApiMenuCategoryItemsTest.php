<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Menu;
use App\Models\OptionCategory;
use App\Models\User;
use Tests\TestCase;
use App\Models\MenuCategory;
use App\Models\RestaurantStatus;
use App\Models\Tax;

class ApiMenuCategoryItemsTest extends TestCase
{

    public function test_add_items_of_category_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $tax = Tax::factory()->create();
        $user->assignRole('super_admin');
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'ga7747al200@gmail.com',
                 'phone' => '711888223442',
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
         ->post(
             '/api/menu/item/' . $menu_category->id,
             [
             'name' => 'salad',
             'description' => "good food",
             'alcohol_percentage' => 10,
             'is_available' => 1,
             'tax_id' => $tax->id,
             'price' => '10',
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_items_of_category_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $tax = Tax::factory()->create();
        $status = RestaurantStatus::first();
        $tax = Tax::factory()->create();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'gal83232664l200@gmail.com',
                 'phone' => '77788912345642',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $restauran = $user_restaurant->restaurant;
        Menu::factory()
        ->hasCategories(4)->create([
            'restaurant_id' => $restauran->id
        ]);
        $menu_category = MenuCategory::first();
        $menu_category->items()->create(
            [
            'name' => 'salad',
            'description' => "good food",
            'alcohol_percentage' => 10,
            'is_available' => 1,
            'tax_id' => $tax->id,
            'price' => '10',
            ]
        );
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
         ->get('/api/menu/category/items/' . $menu_category->id);
        $response->assertStatus(200);
    }
    public function test_update_items_of_category_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $tax = Tax::factory()->create();
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'galhhjkahjhl200@gmail.com',
                 'phone' => '7732123442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $restauran = $user_restaurant->restaurant;
        Menu::factory()
        ->hasCategories(4)->create([
            'restaurant_id' => $restauran->id
        ]);
        $menu_category = MenuCategory::first();
            $item = Item::factory()->create([
            'name' => 'salad',
            'description' => "good food",
            'alcohol_percentage' => 10,
            'is_available' => 1,
            'tax_id' => $tax->id,
            'category_id' => $menu_category->id,
            'price' => '10',
            ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->patch(
             '/api/menu/item/' . $item->id,
             [
             'name' => 'sodau',
             'description' => "good food",
             'alcohol_percentage' => 20,
             'is_available' => 1,
             'tax_id' => $tax->id,
             'category_id' => $menu_category->id,
             'price' => '20',
             ]
         );
        $response->assertStatus(200);
    }
    public function test_delete_items_of_category_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $tax = Tax::factory()->create();
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'gal6633a5uiiuihl200@gmail.com',
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
        $item = Item::factory()->create([
        'name' => 'salad',
        'description' => "good food",
        'alcohol_percentage' => 10,
        'is_available' => 1,
        'tax_id' => $tax->id,
        'category_id' => $menu_category->id,
        'price' => '10',
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/menu/item/' . $item->id);
        $response->assertStatus(200);
    }
    public function test_add_option_items_of_category_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $tax = Tax::factory()->create();
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'gal67633a54l200@gmail.com',
                 'phone' => '77853188442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $restauran = $user_restaurant->restaurant;
        $option = OptionCategory::factory()->create([
            'restaurant_id' => $restauran->id
        ]);
        Menu::factory()
        ->hasCategories(4)->create([
            'restaurant_id' => $restauran->id
        ]);
        $menu_category = MenuCategory::first();
        $item = Item::factory()->create([
        'name' => 'salad',
        'description' => "good food",
        'alcohol_percentage' => 10,
        'is_available' => 1,
        'tax_id' => $tax->id,
        'category_id' => $menu_category->id,
        'price' => '10',
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/menu/item/add-option/' . $item->id,
             [
             'option_id' => $option->id,
             ]
         );
        $response->assertStatus(200);
    }
    public function test_remove_option_items_of_category_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $tax = Tax::factory()->create();
        $status = RestaurantStatus::first();
        $user_restaurant = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create([
                 'email' => 'gal676654l200@gmail.com',
                 'phone' => '7786664442',
                 'country_code' => '967'
             ]);
        $user_restaurant->assignRole('owner');
        $restauran = $user_restaurant->restaurant;
        $option = OptionCategory::factory()->create([
            'restaurant_id' => $restauran->id
        ]);
        Menu::factory()
        ->hasCategories(4)->create([
            'restaurant_id' => $restauran->id
        ]);
        $menu_category = MenuCategory::first();
        $item = Item::factory()->create([
        'name' => 'salad',
        'description' => "good food",
        'alcohol_percentage' => 10,
        'is_available' => 1,
        'tax_id' => $tax->id,
        'category_id' => $menu_category->id,
        'price' => '10',
        ]);

        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/menu/item/remove-option/' . $item->id,
             [
             'option_id' => $option->id,
             ]
         );
        $response->assertStatus(200);
    }
}
