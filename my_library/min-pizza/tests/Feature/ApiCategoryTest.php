<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Category;

class ApiCategoryTest extends TestCase
{

    public function test_add_categories_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/categories/',
             [
                    'name' => '{"ar":"الفطائر","en":"pancakes","sv":"pannkakor"}',
                    'is_active' => "1",
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_categories_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        Category::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
         ->get('/api/categories/');
        $response->assertStatus(200);
    }
    public function test_update_categories_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $category = Category::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->PATCH(
             '/api/categories/' . $category->id,
             [
                    'name' => '{"ar":"الفطائر","en":"pancakes","sv":"pannkakor"}',
                    'is_active' => "1",
             ]
         );
        $response->assertStatus(200);
    }
    public function test_delete_categories_restaurant_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $category = Category::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/categories/' . $category->id);
        $response->assertStatus(200);
    }
}
