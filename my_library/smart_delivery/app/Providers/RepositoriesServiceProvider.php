<?php

namespace App\Providers;

use App\Repositories\AttachmentRepository;
use App\Repositories\AttachmentRepositoryInterface;
use App\Repositories\DriverRepository;
use App\Repositories\DriverRepositoryInterface;
use App\Repositories\ReportRepository;
use App\Repositories\ReportRepositoryInterface;
use App\Repositories\SettingRepository;
use App\Repositories\SettingRepositoryInterface;
use App\Repositories\StoreRepository;
use App\Repositories\StoreRepositoryInterface;
use App\Repositories\TownRepository;
use App\Repositories\TownRepositoryInterface;
use App\Repositories\TypeRepository;
use App\Repositories\TypeRepositoryInterface;
use App\Repositories\UserRepository;
use Tests\Repositories\UserRepository as TestUserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\PackageRepository;
use App\Repositories\PackageRepositoryInterface;
use App\Repositories\PackageCodeRepository;
use App\Repositories\PackageCodeRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\PostTranslationRepository;
use App\Repositories\PostTranslationRepositoryInterface;
use App\Repositories\DriverTempRepository;
use App\Repositories\DriverTempRepositoryInterface;
use App\Repositories\OutSourceRepository;
use App\Repositories\OutSourceRepositoryInterface;
use App\Services\DependencyBinder;
use Illuminate\Support\ServiceProvider;
use Tests\Repositories\OrderRepository as TestOrderRepository;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StoreRepositoryInterface::class, StoreRepository::class);
        $this->app->bind(TypeRepositoryInterface::class, TypeRepository::class);
        $this->app->bind(DriverRepositoryInterface::class, DriverRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(PackageCodeRepositoryInterface::class, PackageCodeRepository::class);
        $this->app->bind(AttachmentRepositoryInterface::class, AttachmentRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PostTranslationRepositoryInterface::class, PostTranslationRepository::class);
        $this->app->bind(DriverTempRepositoryInterface::class, DriverTempRepository::class);
        $this->app->bind(OutSourceRepositoryInterface::class, OutSourceRepository::class);
        $this->app->bind(TownRepositoryInterface::class, TownRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);

        DependencyBinder::abstract(OrderRepositoryInterface::class)
            ->any(OrderRepository::class)
            ->testing(TestOrderRepository::class)
            ->bind();

        DependencyBinder::abstract(UserRepositoryInterface::class)
            ->any(UserRepository::class)
            ->testing(TestUserRepository::class)
            ->bind();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
