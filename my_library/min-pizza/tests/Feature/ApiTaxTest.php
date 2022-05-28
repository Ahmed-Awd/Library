<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Tax;

class ApiTaxTest extends TestCase
{

    public function test_add_taxes_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/taxes/',
             [
                    'name' => 'tax buy',
                    'percentage' => "10",
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_taxes_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        Tax::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
        ->get('/api/taxes/');
        $response->assertStatus(200);
    }
    public function test_update_taxes_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $tax = Tax::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->put(
             '/api/taxes/' . $tax->id,
             [
                    'name' => 'tax 2',
                    'is_active' => "5",
             ]
         );
        $response->assertStatus(200);
    }
    public function test_delete_taxes_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $tax = Tax::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/taxes/' . $tax->id);
        $response->assertStatus(200);
    }
}
