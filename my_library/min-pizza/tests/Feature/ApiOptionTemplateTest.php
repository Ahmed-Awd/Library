<?php

namespace Tests\Feature;

use App\Models\OptionCategory;
use App\Models\User;
use Tests\TestCase;
use App\Models\OptionValue;
use App\Models\OptionTemplate;
use App\Models\OptionSecondary;
use App\Models\RestaurantStatus;

class ApiOptionTemplateTest extends TestCase
{

    public function test_add_option_template_with_data_valid()
    {
        // $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user->assignRole('owner');
        $restaurant = $user->restaurant;
        $optionCategoryPrimary = OptionCategory::factory()
        ->hasOptionValues(1, [
            'price' => rand(10, 99),
            'name' => 'middle',
        ])->create([
            'is_primary' => 1,
            'restaurant_id' => $restaurant->id
        ]);
        $optionCategorySecondary = OptionCategory::factory()
        ->hasOptionValues(1, [
            'price' => rand(10, 99),
            'name' => 'middle',
        ])->create([
            'is_primary' => 0,
            'restaurant_id' => $restaurant->id
        ]);
        $i = 0;
        foreach ($optionCategoryPrimary->optionValues as $primary) {
            foreach ($optionCategorySecondary->optionValues as $secondary) {
                $option_secondaries[$i]['secondary_option_id'] = $secondary->option_category_id;
                $option_secondaries[$i]['primary_option_value_id'] = $primary->id;
                $option_secondaries[$i]['secondary_option_value_id'] = $secondary->id;
                $option_secondaries[$i]['price'] = 0;
                $option_secondaries[$i]['use_default'] = 1;
            }
            $i++;
        }
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->post(
             '/api/option-template/',
             [
             'name' => 'new template',
             'primary_option_id' => $optionCategoryPrimary->id,
             'option_secondaries' => $option_secondaries
             ]
         );
        $response->assertStatus(200);
    }
    public function test_get_option_template_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user->assignRole('owner');
        $restaurant = $user->restaurant;
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->withHeader('Accept-Language', 'en')
         ->get('/api/option-template');
        $response->assertStatus(200);
    }
    public function test_update_option_template_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user->assignRole('owner');
        $restaurant = $user->restaurant;
        $optionCategoryPrimary = OptionCategory::factory()
        ->hasOptionValues(3, [
            'price' => rand(10, 99),
            'name' => 'middle',
        ])->create([
            'is_primary' => 1,
            'restaurant_id' => $restaurant->id
        ]);
        $optionCategorySecondary = OptionCategory::factory()
        ->hasOptionValues(3, [
            'price' => rand(10, 99),
            'name' => 'middle',
        ])->create([
            'is_primary' => 0,
            'restaurant_id' => $restaurant->id
        ]);
        $i = 0;
        foreach ($optionCategoryPrimary->optionValues as $primary) {
            foreach ($optionCategorySecondary->optionValues as $secondary) {
                $option_secondaries[$i]['secondary_option_id'] = $secondary->option_category_id;
                $option_secondaries[$i]['primary_option_value_id'] = $primary->id;
                $option_secondaries[$i]['secondary_option_value_id'] = $secondary->id;
                $option_secondaries[$i]['price'] = 0;
                $option_secondaries[$i]['use_default'] = 1;
            }
            $i++;
        }
        $option_template = OptionTemplate::first();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->patch(
             '/api/option-template/' . $option_template->id,
             [
             'name' => 'middle',
             'primary_option_id' => $optionCategoryPrimary->id,
             'option_secondaries' => $option_secondaries
             ]
         );
        $response->assertStatus(200);
    }
    public function test_delete_option_category_values_with_data_valid()
    {
        $this->withoutExceptionHandling();
        $status = RestaurantStatus::first();
        $user = User::factory()
        ->hasRestaurant(1, [
                'status_id' => $status->id,
                ])
             ->create();
        $user->assignRole('owner');
        $restaurant = $user->restaurant;
        $option_template = OptionTemplate::first();
        $token = $user->createToken('auth_token', ['*'], $data['notification_token'] ?? null)->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
         ->delete('/api/option-template/' . $option_template->id);
        $response->assertStatus(200);
    }
}
