<?php

namespace App\Providers;

use App\Services\GeoDistance;
use App\Services\GoogleDistance;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
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

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
