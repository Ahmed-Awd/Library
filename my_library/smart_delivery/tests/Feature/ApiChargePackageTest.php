<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\PackageCode;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiChargePackageTest extends TestCase
{
    public function test_charging_with_wrong_code()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('post', '/api/charge', [
                'code' => 'sad48sa378s'
            ]);
        $response->assertStatus(400);
        $response->assertJson(['message' => "please enter a valid code"]);
    }

    public function test_charging_with_already_used_code()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $package = Package::factory()->create();
        $code = PackageCode::factory()->create(["package_id" => $package->id,"user_id" => $user->id]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('post', '/api/charge', [
                'code' => $code->code
            ]);
        $response->assertStatus(400);
        $response->assertJson(['message' => "this code is already used,please enter another code"]);
    }

    public function test_charging_with_new_code()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $package = Package::factory()->create();
        $code = PackageCode::factory()->create(["package_id" => $package->id]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('post', '/api/charge', [
                'code' => $code->code
            ]);
        $response->assertStatus(200);
        $response->assertJson(['message' => "charging your balance success"]);
    }
}
