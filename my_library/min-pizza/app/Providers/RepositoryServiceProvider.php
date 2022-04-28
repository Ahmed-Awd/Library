<?php

namespace App\Providers;

use App\Repositories\AddressRepository;
use App\Repositories\AddressRepositoryInterface;
use App\Repositories\CountryRepository;
use App\Repositories\CountryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ContactRepository;
use App\Repositories\ContactRepositoryInterface;
use App\Repositories\DiscountCodeRepository;
use App\Repositories\DiscountCodeRepositoryInterface;
use App\Repositories\DriverRepository;
use App\Repositories\DriverRepositoryInterface;
use App\Repositories\DayRepository;
use App\Repositories\DayRepositoryInterface;
use App\Repositories\DeliveryPriceOptionRepositoryInterface;
use App\Repositories\DeliveryPriceOptionRepository;
use App\Repositories\FeedBackRepository;
use App\Repositories\FeedBackRepositoryInterface;
use App\Repositories\FQARepository;
use App\Repositories\FQARepositoryInterface;
use App\Repositories\GeneralNotificationRepository;
use App\Repositories\GeneralNotificationRepositoryInterface;
use App\Repositories\ItemOfferRepository;
use App\Repositories\ItemOfferRepositoryInterface;
use App\Repositories\MenuRepository;
use App\Repositories\MenuRepositoryInterface;
use App\Repositories\ModuleRepository;
use App\Repositories\ModuleRepositoryInterface;
use App\Repositories\OptionCategoryRepository;
use App\Repositories\OptionCategoryRepositoryInterface;
use App\Repositories\OptionSecondaryRepository;
use App\Repositories\OptionSecondaryRepositoryInterface;
use App\Repositories\OptionTemplateRepository;
use App\Repositories\OptionTemplateRepositoryInterface;
use App\Repositories\OptionValueRepository;
use App\Repositories\OptionValueRepositoryInterface;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderItemRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\PaymentMethodsRepository;
use App\Repositories\PaymentMethodsRepositoryInterface;
use App\Repositories\PaymentRepository;
use App\Repositories\PaymentRepositoryInterface;
use App\Repositories\ReasonRepository;
use App\Repositories\ReasonRepositoryInterface;
use App\Repositories\RestaurantCategoryRepository;
use App\Repositories\RestaurantCategoryRepositoryInterface;
use App\Repositories\RestaurantDistanceRepository;
use App\Repositories\RestaurantDistanceRepositoryInterface;
use App\Repositories\RestaurantOfferRepository;
use App\Repositories\RestaurantOfferRepositoryInterface;
use App\Repositories\RestaurantPhoneRepository;
use App\Repositories\RestaurantPhoneRepositoryInterface;
use App\Repositories\RestaurantRatingRepository;
use App\Repositories\RestaurantRatingRepositoryInterface;
use App\Repositories\RestaurantRepository;
use App\Repositories\RestaurantRepositoryInterface;
use App\Repositories\SettingRepository;
use App\Repositories\SettingRepositoryInterface;
use App\Repositories\SliderRepository;
use App\Repositories\SliderRepositoryInterface;
use App\Repositories\TermsRepository;
use App\Repositories\TermsRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\RestaurantSettingRepository;
use App\Repositories\RestaurantSettingRepositoryInterface;
use App\Repositories\RestaurantStatusRepository;
use App\Repositories\RestaurantStatusRepositoryInterface;
use App\Repositories\WorktimeRepository;
use App\Repositories\WorktimeRepositoryInterface;
use App\Repositories\TaxRepository;
use App\Repositories\TaxRepositoryInterface;
use App\Repositories\RestaurantFollowRepository;
use App\Repositories\RestaurantFollowRepositoryInterface;
use App\Repositories\RestaurantHolidayRepository;
use App\Repositories\RestaurantHolidayRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(RestaurantRepositoryInterface::class, RestaurantRepository::class);
        $this->app->bind(RestaurantCategoryRepositoryInterface::class, RestaurantCategoryRepository::class);
        $this->app->bind(RestaurantPhoneRepositoryInterface::class, RestaurantPhoneRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DriverRepositoryInterface::class, DriverRepository::class);
        $this->app->bind(RestaurantStatusRepositoryInterface::class, RestaurantStatusRepository::class);
        $this->app->bind(DayRepositoryInterface::class, DayRepository::class);
        $this->app->bind(WorktimeRepositoryInterface::class, WorktimeRepository::class);
        $this->app->bind(DeliveryPriceOptionRepositoryInterface::class, DeliveryPriceOptionRepository::class);
        $this->app->bind(RestaurantSettingRepositoryInterface::class, RestaurantSettingRepository::class);
        $this->app->bind(TaxRepositoryInterface::class, TaxRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(OptionCategoryRepositoryInterface::class, OptionCategoryRepository::class);
        $this->app->bind(OptionValueRepositoryInterface::class, OptionValueRepository::class);
        $this->app->bind(RestaurantRatingRepositoryInterface::class, RestaurantRatingRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(OptionTemplateRepositoryInterface::class, OptionTemplateRepository::class);
        $this->app->bind(OptionSecondaryRepositoryInterface::class, OptionSecondaryRepository::class);
        $this->app->bind(TermsRepositoryInterface::class, TermsRepository::class);
        $this->app->bind(RestaurantFollowRepositoryInterface::class, RestaurantFollowRepository::class);
        $this->app->bind(FQARepositoryInterface::class, FQARepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderItemRepositoryInterface::class, OrderItemRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(FeedBackRepositoryInterface::class, FeedBackRepository::class);
        $this->app->bind(GeneralNotificationRepositoryInterface::class, GeneralNotificationRepository::class);
        $this->app->bind(ReasonRepositoryInterface::class, ReasonRepository::class);
        $this->app->bind(SliderRepositoryInterface::class, SliderRepository::class);
        $this->app->bind(DiscountCodeRepositoryInterface::class, DiscountCodeRepository::class);
        $this->app->bind(RestaurantOfferRepositoryInterface::class, RestaurantOfferRepository::class);
        $this->app->bind(ItemOfferRepositoryInterface::class, ItemOfferRepository::class);
        $this->app->bind(ModuleRepositoryInterface::class, ModuleRepository::class);
        $this->app->bind(RestaurantHolidayRepositoryInterface::class, RestaurantHolidayRepository::class);
        $this->app->bind(RestaurantDistanceRepositoryInterface::class, RestaurantDistanceRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(PaymentMethodsRepositoryInterface::class, PaymentMethodsRepository::class);
    }

    public function boot()
    {
        //
    }
}
