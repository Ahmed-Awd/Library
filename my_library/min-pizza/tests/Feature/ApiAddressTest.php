<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Address;

class ApiAddressTest extends TestCase
{

    public function test_add_addresses_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/addresses/',
             [
             'lat' => 45.3565456,
             'lng' => 55.4564654,
             'area' => '50 m',
             'description' => 'near Ibb',
             'ZIP_code' => "1",
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_addresses_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        Address::factory()->create([
            'user_id' => $user->id
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/addresses/');
        $response->assertStatus(200);
    }
    public function test_update_addresses_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $address = Address::factory()->create([
            'user_id' => $user->id
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->put(
             '/api/addresses/' . $address->id,
             [
             'lat' => 45.3565456,
             'lng' => 55.4564654,
             'area' => '50 m',
             'description' => 'near Ibb',
             'ZIP_code' => "1",
             ]
         );
        $response->assertStatus(200);
    }
    public function test_delete_addresses_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $address = Address::factory()->create([
            'user_id' => $user->id
        ]);
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/addresses/' . $address->id);
        $response->assertStatus(200);
    }
}
