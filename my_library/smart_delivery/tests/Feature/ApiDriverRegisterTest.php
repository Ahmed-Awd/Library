<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiDriverRegisterTest extends TestCase
{
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_register_with_no_data()
    {
        $response = $this->json('post', '/api/driver/register', []);
        $response->assertStatus(422);
        $response->assertJson(['message' => "The given data was invalid."]);
    }

    public function test_register_with_data()
    {
        $response = $this->json('post', '/api/driver/register', [
            "username" => "thenew00",
             "password" => "123456",
             "password_confirmation" => "123456",
             "email" => "thefara@loba.com",
             "name" => "saieed"
        ]);
        $response->assertJson(['message' => "The given data was invalid."]);
    }

    public function test_register_with_no_password_not_match()
    {
        $response = $this->json('post', '/api/driver/register', [
            "username" => "thenew00",
            "password" => "123456",
            "password_confirmation" => "123458",
            "email" => "thefara@loba.com",
            "name" => "saieed"
        ]);
        $response->assertStatus(422);
    }
}
