<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{

    // use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_if_email_or_password_wrong()
    {
        $response = $this->post('/api/login', [
            'email' => 'admin@smartlife.ws',
            'password' => 'the_wrong',
            'default_lang' => 'en',
            'role' => 'super_admin',
        ]);

        $response->assertStatus(401);
    }

    public function test_if_email_or_password_is_valid()
    {
        $user = User::factory()->create(
            ['phone' => 771772773,
            'country_code' => 967
            ]
        );
        $user->assignRole('customer');
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '123456',
            'default_lang' => 'en',
            'role' => 'customer',
        ]);

        $response->assertStatus(200);
    }
}
