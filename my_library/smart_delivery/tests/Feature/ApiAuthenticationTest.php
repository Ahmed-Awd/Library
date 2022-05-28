<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;

class ApiAuthenticationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_if_phone_or_password_wrong()
    {
        $response = $this->post('/api/driver/login', [
            'phone' => 771772773,
            'country_code' => 967,
            'password' => 'the_wrong',
        ]);

        $response->assertStatus(401);
    }

    public function test_if_phone_or_password_is_valid()
    {
        $user = User::factory()->create(
            ['phone' => 771772773,
            'country_code' => 967
            ]
        );
        $user->assignRole('driver');
        $response = $this->post('/api/driver/login', [
            'phone' => $user->phone,
            'country_code' => $user->country_code,
            'password' => '123456',
        ]);

        $response->assertStatus(200);
    }
}
