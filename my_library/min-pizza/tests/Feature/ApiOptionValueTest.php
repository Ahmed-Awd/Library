<?php

namespace Tests\Feature;

use App\Models\OptionCategory;
use App\Models\User;
use Tests\TestCase;
use App\Models\OptionValue;

class ApiOptionValueTest extends TestCase
{

    public function test_add_option_category_values_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('owner');
        $optionCategory = OptionCategory::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/option-value/',
             [
             'name' => 'red',
             'option_category_id' => $optionCategory->id,
             'price' => rand(10, 99),
             'min_count' => rand(1, 9),
             'max_count' => rand(1, 9),
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_option_category_values_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('owner');
        $optionCategory = OptionCategory::factory()
        ->hasOptionValues(1, [
            'price' => rand(10, 99),
            'name' => 'middle',
        ])->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
         ->get('/api/option-value/category/' . $optionCategory->id);
        $response->assertStatus(200);
    }
    public function test_update_option_category_values_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('owner');
        $optionCategory = OptionCategory::factory()
        ->hasOptionValues(1, [
            'price' => rand(10, 99),
            'name' => 'middle',
        ])->create();
        $optionValue = OptionValue::first();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->patch(
             '/api/option-value/' . $optionValue->id,
             [
             'price' => rand(10, 99),
             'name' => 'middle',
             'option_category_id' => $optionCategory->id,
             'min_count' => rand(1, 9),
             'max_count' => rand(1, 9),
             ]
         );
        $response->assertStatus(200);
    }
    public function test_delete_option_category_values_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('owner');
        $optionCategory = OptionCategory::factory()
        ->hasOptionValues(1, [
            'price' => rand(10, 99),
            'name' => 'middle',
        ])->create();
        $optionValue = OptionValue::first();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/option-value/' . $optionValue->id);
        $response->assertStatus(200);
    }
}
