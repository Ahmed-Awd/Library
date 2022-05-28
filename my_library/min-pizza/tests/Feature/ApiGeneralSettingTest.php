<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiGeneralSettingTest extends TestCase
{

    public function test_get_setting_with_data_valid()
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->get('/api/general-settings');
        $response->assertStatus(200);
    }
    public function test_update_setting_with_data_valid()
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/general-settings',
             [
             'settings' => [
             'app_name' => 'app_name',
             'app_logo' => 'app_logo' ,
             'short_description' => 'short_description',
             'contact_phone' => 'contact_phone',
             'contact_email' => 'contact_email',
             'facebook' => 'facebook',
             'twitter' => 'twitter',
             'instagram' => 'instagram',
             'no_action_taken_for_order' => 20,
             'cancel_the_no_action_order' => 10,
             'free_delivery_for_all' => true,
             'driver_max_time_for_receive' => '30',
             'technical_support_number' => '964546'
             ]
             ]
         );

        $response->assertStatus(200);
    }

    public function test_show_setting_with_data_valid()
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $setting = Setting::first();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
         ->get('/api/general-settings/' . $setting->id);
        $response->assertStatus(200);
    }
}
