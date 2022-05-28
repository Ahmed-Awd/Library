<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiCustomerRegisterTest extends TestCase
{
    // use RefreshDatabase;

    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_register_with_no_data()
    {
        $response = $this->json('post', '/api/register', []);
        $response->assertStatus(422);
        $response->assertJson(['message' => "The given data was invalid."]);
    }

    public function test_register_with_data()
    {
        $response = $this->json('post', '/api/register', [
             "password" => "123456",
             "password_confirmation" => "123456",
             "email" => "thefggara@loba.com",
             "name" => "saieed",
             "city_id" => 1,
             "phone" => "771772776",
             "country_code" => "967"
        ]);
        $response->assertStatus(200);
        $response->assertJson(['message' => "Email verification link sent on your email address"]);
    }

    public function test_register_with_no_password_not_match()
    {
        $response = $this->json('post', '/api/register', [
            "password" => "123456",
            "password_confirmation" => "123458",
            "email" => "thefaddra@loba.com",
            "name" => "saieed",
            "city_id" => 1,
            "phone" => "771772776",
            "country_code" => "967"
        ]);
        $response->assertStatus(422);
    }
}
