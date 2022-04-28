<?php

namespace App\Providers;

use App\Mail\VerifyMail;
use App\Models\Address;
use App\Models\DiscountCode;
use App\Models\Driver;
use App\Models\FeedBack;
use App\Models\OptionCategory;
use App\Models\OptionValue;
use App\Models\Setting;
use App\Models\Tax;
use App\Models\User;
use App\Policies\AddressPolicy;
use App\Models\Category;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\RestaurantRating;
use App\Policies\CategoryPolicy;
use App\Policies\DiscountCodePolicy;
use App\Policies\DriverPolicy;
use App\Policies\OptionCategoryPolicy;
use App\Policies\OptionValuePolicy;
use App\Policies\RestaurantPolicy;
use App\Policies\SettingPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\OrderPolicy;
use App\Policies\TaxPolicy;
use App\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Address::class => AddressPolicy::class,
        Category::class => CategoryPolicy::class,
        Restaurant::class => RestaurantPolicy::class,
        Driver::class => DriverPolicy::class,
        Tax::class => TaxPolicy::class,
        User::class => CustomerPolicy::class,
        OptionCategory::class => OptionCategoryPolicy::class,
        OptionValue::class => OptionValuePolicy::class,
        Order::class => OrderPolicy::class,
        DiscountCode::class => DiscountCodePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();


        Gate::define('list-addresses', function (User $user) {
            return $user->hasPermissionTo('list addresses');
        });
        Gate::define('list-worktimes', function (User $user, Restaurant $restaurant) {
            return ($user->hasPermissionTo('list worktimes') or $restaurant->owner->id == $user->id);
        });
        Gate::define('update-worktime', function (User $user, Restaurant $restaurant) {
            return ($user->hasPermissionTo('update worktime') or $restaurant->owner->id == $user->id);
        });
        Gate::define('rate-order', function (User $user, Order $order) {
            return $order->user_id == $user->id;
        });
        Gate::define('list-restaurant-settings', function (User $user, Restaurant $restaurant) {
            return ($user->hasPermissionTo('control restaurants') or $restaurant->owner->id == $user->id);
        });
        Gate::define('update-restaurant-setting', function (User $user, Restaurant $restaurant) {
            return ($user->hasPermissionTo('control restaurants') or $restaurant->owner->id == $user->id);
        });
        Gate::define('update-restaurant-status', function (User $user, Restaurant $restaurant) {
            return ($user->hasPermissionTo('update restaurant status') or $restaurant->owner->id == $user->id) ;
        });
        Gate::define('change-menu', function (User $user, Restaurant $restaurant) {
            return ($user->hasPermissionTo('change menu') or $restaurant->owner->id == $user->id);
        });
        Gate::define('edit-general-settings', function (User $user) {
            return $user->hasPermissionTo('edit settings');
        });

        Gate::define('send-notification', function (User $user) {
            return $user->hasPermissionTo('send notification');
        });

        Gate::define('see-notification', function (User $user) {
            return $user->hasPermissionTo('see notification');
        });


        Gate::define('disable-user', function (User $user) {
            return $user->hasPermissionTo('suspend user');
        });

        Gate::define('add-restaurant-rating', function (User $user) {
            return $user->hasPermissionTo('add restaurant rating') ;
        });
        Gate::define('edit-restaurant-rating', function (User $user, RestaurantRating $restaurantRating) {
            return ($user->hasPermissionTo('edit restaurant rating') or $user->id === $restaurantRating->user_id);
        });
        Gate::define('delete-restaurant-rating', function (User $user, RestaurantRating $restaurantRating) {
            return ($user->hasPermissionTo('delete restaurant rating') or $user->id === $restaurantRating->user_id);
        });

        Gate::define('edit-questions', function (User $user) {
            return $user->hasPermissionTo('edit questions');
        });


        Gate::define('show-feedback', function (User $user) {
            return $user->hasPermissionTo('control feedbacks');
        });

        Gate::define('replay-feedback', function (User $user, FeedBack $feedBack) {
            return $user->hasPermissionTo('control feedbacks');
        });

        Gate::define('delete-feedback', function (User $user, FeedBack $feedBack) {
            return $user->hasPermissionTo('control feedbacks');
        });

        Gate::define('view-orders', function (User $user) {
            return $user->hasPermissionTo('view orders');
        });

        Gate::define('control-reasons', function (User $user) {
            return $user->hasPermissionTo('control reasons');
        });

        Gate::define('control-slider', function (User $user) {
            return $user->hasPermissionTo('control sliders');
        });

        Gate::define('control-owner', function (User $user) {
            return $user->hasPermissionTo('control owner');
        });

        Gate::define('control-offer', function (User $user) {
            return $user->hasPermissionTo('control offers');
        });

        Gate::define('manage-holidays', function (User $user) {
            return $user->hasPermissionTo('manage holidays');
        });
    }
}
