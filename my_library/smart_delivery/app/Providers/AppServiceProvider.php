<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Services\DependencyBinder;
use App\Services\GeoDistance;
use App\Services\GoogleDistance;
use App\Services\SettingsService;
use App\Services\SmsServices;
use App\Services\TurkeySms;
use App\Services\TestSms;
use App\Services\SettingsServiceInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Tests\Services\SettingsTestService;
use Tests\Services\TestDistance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $isTesting = config('app.env') == 'testing';
        $this->app->bind(GeoDistance::class, $isTesting ? TestDistance::class : GoogleDistance::class);
        $this->app->bind(SmsServices::class, $isTesting ? TestSms::class : TurkeySms::class);
        DependencyBinder::abstract(SettingsServiceInterface::class)
            ->any(SettingsService::class)
            ->testing(SettingsTestService::class)
            ->bind();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
