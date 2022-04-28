<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AdminOrderController;
use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\DiscountCodeController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\FeedBackController;
use App\Http\Controllers\API\FQAController;
use App\Http\Controllers\API\HolidaysController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\OffersController;
use App\Http\Controllers\API\OptionCategoryController;
use App\Http\Controllers\API\OptionValueController;
use App\Http\Controllers\API\PaymentMethodsController;
use App\Http\Controllers\API\ReasonsController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\SliderController;
use App\Http\Controllers\API\TaxesController;
use App\Http\Controllers\API\TermsController;
use App\Http\Controllers\API\RestaurantFollowController;
use App\Http\Controllers\API\UploadController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\CustomerOrderController;
use App\Http\Controllers\API\DriverOrderController;
use App\Http\Controllers\API\ModuleController;
use App\Http\Controllers\API\RestaurantController;
use App\Http\Controllers\API\RestaurantRatingController;
use App\Http\Controllers\API\WorktimeController;
use App\Http\Controllers\API\RestaurantSettingController;
use App\Http\Controllers\API\OptionTemplateController;
use App\Http\Controllers\API\OrderStatusController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\RestaurantOrderController;
use App\Models\TestCronJop;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
| staring phase 2
*/
Route::get('generate-pdf', [OrderStatusController::class, 'generatePDF']);
Route::get('/seed', [CountryController::class, 'seed']);
Route::get('/order-status', [OrderStatusController::class, 'index']);
Route::get('/admin-order-status', [OrderStatusController::class, 'getAdminStatus']);
Route::get('/countries', [CountryController::class, 'countries']);
Route::get('/cities/{country}', [CountryController::class, 'cities']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/social-login', [AuthController::class, 'socialLogin']);
Route::get('/chackout/bambora/{platform}', [CustomerOrderController::class,'paymentBambora'])->name('chackout.bambora');
Route::get('/check/bambora/{platform}', [CustomerOrderController::class,'checkCallBack'])->name('check.bambora');
Route::post('/check/swish/{platform}', [CustomerOrderController::class,'checkPaymentSwish'])->name('check.swish');
Route::get('/solve/payment', [CustomerOrderController::class,'solvePayment'])->name('solve.swish');
Route::get('/cancel/bambora/{order}', [CustomerOrderController::class,'cancelBambora'])->name('cancel.bambora');

Route::get('/contact-info', [SettingController::class,'contactInfo']);
Route::get('/mobile-versions', [SettingController::class,'mobileInfo']);
Route::get('/about-us', [TermsController::class,'getAboutUs']);
Route::get('/terms-and-conditions', [TermsController::class,'getTerms']);
Route::get('/all-taxes', [TaxesController::class,'all']);
Route::get('/FQA', [FQAController::class,'index']);
Route::get('/FQA/get/all', [FQAController::class,'all']);
Route::get('/FQA/{FQA}', [FQAController::class,'show']);

Route::post('/feedback/send', [FeedBackController::class,'send']);
Route::post('/module/{key}', [ModuleController::class,'getByKey']);

Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/sliders', [SliderController::class,'all']);
Route::get('/sliders/{slider}', [SliderController::class,'show']);

Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

Route::get('/menu/{restaurant}', [MenuController::class, 'index']);
Route::get('/restaurants/rating/{restaurant}', [RestaurantRatingController::class, 'index']);
Route::get('menu/item/{item}', [MenuController::class, 'showItem']);

Route::get('/restaurants', [RestaurantController::class,'all']);
Route::get('/restaurants/{restaurant}', [RestaurantController::class,'view']);
Route::get('/restaurants/all/lite', [RestaurantController::class,'lite']);

Route::get('/holidays/of-restaurant/{restaurant}', [HolidaysController::class,'index']);
Route::get('/holiday/{holiday}', [HolidaysController::class,'show']);

Route::get('/categories', [CategoryController::class,'all']);
Route::get('/categories/{category}', [CategoryController::class,'view']);
Route::get('/category/restaurants/{category}', [CategoryController::class,'getRestaurants']);
Route::get('/general-settings', [SettingController::class, 'index']);

Route::get('/roles/all', [UserController::class,'roles']);
Route::get('/permissions/all', [UserController::class,'permissions']);
Route::get('/payment-method', [UserController::class,'method']);
Route::post('orders/calc-order-item', [CustomerOrderController::class,'calcOrder']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('addresses', AddressController::class);
    Route::get('/addresses/of/{user}', [AddressController::class, 'ofUser'])->middleware('can:list-addresses');
    Route::get('/status', [RestaurantController::class, 'status']);
    Route::get('/days', [WorktimeController::class, 'days']);

    Route::post('/restaurants', [RestaurantController::class,'store']);
    Route::delete('/restaurants/{restaurant}', [RestaurantController::class,'destroy']);
    Route::patch('/restaurants/{restaurant}', [RestaurantController::class,'update']);
    Route::post('/restaurants/update-phones/{restaurant}', [RestaurantController::class,'updatePhones'])
        ->middleware('can:update,restaurant');
    Route::post('/restaurants/update-distances/{restaurant}', [RestaurantController::class,'updateDistances'])
        ->middleware('can:update,restaurant');
    Route::post('/restaurants/update-payments/{restaurant}', [RestaurantController::class,'updatePayments'])
        ->middleware('can:update,restaurant');

    Route::post('/FQA', [FQAController::class,'store'])->middleware('can:edit-questions');
    Route::patch('/FQA/{FQA}', [FQAController::class,'update'])->middleware('can:edit-questions');
    Route::delete('/FQA/{FQA}', [FQAController::class,'destroy'])->middleware('can:edit-questions');



    Route::apiResource('/contacts', ContactController::class)
    ->except(['store','update']);

    Route::prefix('restaurants')->group(function () {
        Route::post('/status/{restaurant}', [RestaurantController::class, 'updateStatus'])
            ->middleware('can:update-restaurant-status,restaurant');

        Route::get('/worktime/{restaurant}', [WorktimeController::class, 'show'])
            ->middleware('can:update-restaurant-setting,restaurant');
        Route::post('/worktime/{restaurant}', [WorktimeController::class, 'store'])
            ->middleware('can:update-restaurant-setting,restaurant');
        Route::get('/setting/{restaurant}', [RestaurantSettingController::class, 'show'])
            ->middleware('can:list-restaurant-settings,restaurant');
        Route::post('/setting/{restaurant}', [RestaurantSettingController::class, 'store'])
            ->middleware('can:update-restaurant-setting,restaurant');

        Route::post('/rating/{restaurant}', [RestaurantRatingController::class, 'store'])
        ->middleware('can:add-restaurant-rating');
        Route::get('/rating/{restaurant_rating}/show', [RestaurantRatingController::class, 'show']);
        Route::delete('/rating/{restaurant_rating}', [RestaurantRatingController::class, 'destroy'])
        ->middleware('can:delete-restaurant-rating,restaurant_rating');

        // restaurant follow
    });
    Route::group(['middleware' => ['role:super_admin'],'prefix' => 'admin'], function () {
        Route::apiResource('/user', AdminController::class);
        Route::post('user/add-permission/{user}', [AdminController::class,'attachPermission']);
        Route::post('user/remove-permission/{user}', [AdminController::class,'detachPermission']);
    });

    Route::group(['middleware' => ['role:admin|super_admin'],'prefix' => 'admin'], function () {
        Route::post('/upload-logo', [UploadController::class, 'uploadLogo']);
        Route::apiResource('/orders', AdminOrderController::class);
        Route::get('/orders/get/canceled', [AdminOrderController::class,'canceledOrders']);
        Route::post('orders/calc-order-item', [AdminOrderController::class,'calcOrder']);
        Route::post('orders/{order}/reorder', [AdminOrderController::class,'reorder']);
        Route::post('orders/{order}/calc-reorder-item', [AdminOrderController::class,'calcReorderWithOrder']);
        Route::post('orders/cancel/this/{order}', [AdminOrderController::class,'cancelOrder']);
        Route::post('orders/assign/driver/{order}', [AdminOrderController::class,'assignDriver']);
        Route::post('orders/change-status/of/{order}', [AdminOrderController::class,'changeStatus']);
        Route::get('orders/change-order-to-paid/{order}', [AdminOrderController::class,'changeOrderToPaid']);

        Route::post('report/orders', [ReportController::class,'index']);
        Route::post('report/customers', [ReportController::class,'customers']);
        Route::post('report/restaurants', [ReportController::class,'restaurants']);
        Route::post('report/export/customers', [ReportController::class,'exportCustomers']);
        Route::post('report/export/orders', [ReportController::class,'exportOrders']);
        Route::post('report/export/restaurants', [ReportController::class,'exportRestaurants']);
        Route::get('export/orders', [ReportController::class,'exportByOrders']);
        Route::get('/count-of/orders', [ReportController::class,'countOfOrders']);
        Route::get('get-all/available/drivers', [DriverController::class,'getAllAvl']);
        Route::post('orders/assign/driver/{order}', [AdminOrderController::class,'assignDriver']);
        Route::get('/payment-methods', [PaymentMethodsController::class,'get']);
        Route::get('/get-restaurants', [RestaurantController::class,'getForAdmin']);
    });

    Route::get('/orders/of/customer/{user}', [AdminOrderController::class,'customerOrders'])
        ->middleware('permission:view orders');

     // Route::post('/unfollow/{restaurant}', [RestaurantFollowController::class, 'destroy']);

    Route::post('/categories', [CategoryController::class,'store']);
    Route::get('/admin/categories', [CategoryController::class,'index']);
    Route::delete('/categories/{category}', [CategoryController::class,'destroy']);
    Route::patch('/categories/{category}', [CategoryController::class,'update']);

    Route::apiResource('/addresses', AddressController::class);
    Route::apiResource('/drivers', DriverController::class);

    Route::apiResource('/customers', CustomerController::class)
    ->except(['show','update','destroy']);
    Route::put('/customers/{user}', [CustomerController::class,'update'])
    ->name('customers.update')->middleware('can:update,user');
    Route::get('/customers/{user}', [CustomerController::class,'show'])
    ->name('customers.show')->middleware('can:view,user');
    Route::delete('/customers/{user}', [CustomerController::class,'destroy'])
    ->name('customers.destroy')->middleware('can:delete,user');
    Route::apiResource('/taxes', TaxesController::class);

    Route::group(['middleware' => ['role:owner']], function () {
        Route::apiResource('/option-template', OptionTemplateController::class);
        Route::apiResource('/option-categories', OptionCategoryController::class);
        Route::post('/change/restaurant-status/to/online', [RestaurantController::class,'switchToOnline']);
        Route::post('/change/restaurant-status/to/offline', [RestaurantController::class,'switchToOffline']);
        Route::prefix('/option-value')->group(function () {
            Route::get('/{option_value}', [OptionValueController::class,'show']);
            Route::patch('/{option_value}', [OptionValueController::class,'update']);
            Route::delete('/{option_value}', [OptionValueController::class,'destroy']);
            Route::post('', [OptionValueController::class,'store']);
            Route::get('/category/{optionCategory}', [OptionValueController::class,'index']);
        });
        Route::get('restaurant/get-new/orders/{restaurant?}', [RestaurantOrderController::class,'newOrders']);
        Route::get('restaurant/orders/{order}', [RestaurantOrderController::class,'show']);
        Route::get('restaurant/statistics', [RestaurantOrderController::class,'statistics']);
        Route::get('restaurant/orders/{restaurant?}', [RestaurantOrderController::class,'index']);
        Route::get('restaurant/orders/cancel', [RestaurantOrderController::class,'getCancelOrder']);
        Route::post('restaurant/orders/{order}/accept_order', [RestaurantOrderController::class,'acceptOrder']);
        Route::post('restaurant/orders/{order}/cancel_order', [RestaurantOrderController::class,'cancelOrder']);
        Route::post('restaurant/orders/{order}/delivered', [RestaurantOrderController::class,'delivered']);
        Route::post(
            'restaurant/orders/{order}/ready_to_delivered',
            [RestaurantOrderController::class,'readyToDelivered']
        );
    });


    Route::post('/holiday', [HolidaysController::class,'store'])->middleware('can:manage-holidays');
    Route::patch('/holiday/{holiday}', [HolidaysController::class,'update'])->middleware('can:manage-holidays');
    Route::delete('/holiday/{holiday}', [HolidaysController::class,'destroy'])->middleware('can:manage-holidays');

    Route::get('admin/orders/by-restaurant/{restaurant?}', [RestaurantOrderController::class,'index'])
        ->middleware('can:view-orders');
    Route::get('admin/orders/by-order/{order}', [RestaurantOrderController::class,'show'])
        ->middleware('can:view-orders');

    Route::post('/users/change-status/{user}', [UserController::class, 'changeStatus'])->middleware('can:disable-user');
    //menu categories and items CRUD
    Route::prefix('menu')->group(function () {
        Route::get('restaurant/categories/{restaurant}', [MenuController::class, 'categories']);
        Route::get('restaurant/categories/{restaurant}/items', [MenuController::class, 'allItems']);
        Route::prefix('category')->group(function () {
            Route::post('{restaurant}', [MenuController::class, 'storeCategory'])
                ->middleware('can:change-menu,restaurant');
            Route::get('{category}', [MenuController::class, 'showCategory']);
            Route::patch('{category}', [MenuController::class, 'updateCategory']);
            Route::delete('{category}', [MenuController::class, 'deleteCategory']);
            Route::get('items/{category}', [MenuController::class, 'items']);
        });

        Route::post('item/{category}', [MenuController::class, 'storeItem']);
        Route::patch('item/{item}', [MenuController::class, 'updateItem']);
        Route::delete('item/{item}', [MenuController::class, 'deleteItem']);

        Route::post('item/add-option/{item}', [MenuController::class,'attachItem']);
        Route::post('item/remove-option/{item}', [MenuController::class,'detachItem']);
        Route::post('item/change-availability/{item}', [MenuController::class,'changeItemStatus']);
    });


    Route::post('/general-settings', [SettingController::class, 'update'])->middleware('can:edit-general-settings');
    Route::get('/general-settings/{setting}', [SettingController::class, 'show']);

    Route::post('/module', [ModuleController::class,'update'])->middleware('can:edit-general-settings');
    Route::get('/module', [ModuleController::class,'index']);

    Route::post('/upload', [UploadController::class, 'upload'])->middleware('can:edit-general-settings');

    Route::get('/reasons', [ReasonsController::class, 'all']);
    Route::get('/reasons/{refuseReason}', [ReasonsController::class, 'show']);
    Route::post('/reasons', [ReasonsController::class, 'store'])->middleware('can:control-reasons');
    Route::patch('/reasons/{refuseReason}', [ReasonsController::class, 'update'])->middleware('can:control-reasons');
    Route::delete('/reasons/{refuseReason}', [ReasonsController::class, 'destroy'])->middleware('can:control-reasons');

    Route::post('/sliders', [SliderController::class,'store'])->middleware('can:control-slider');
    Route::get('/admin-sliders', [SliderController::class,'get'])->middleware('can:control-slider');
    Route::patch('/sliders/{slider}', [SliderController::class,'update'])->middleware('can:control-slider');
    Route::delete('/sliders/{slider}', [SliderController::class,'destroy'])->middleware('can:control-slider');
    Route::post('/sliders/switch/{slider}', [SliderController::class,'switchStatus'])->middleware('can:control-slider');

    Route::post('/get/user/token/{user}', [AuthController::class,'getUserToken'])->middleware('can:control-owner');



    Route::group(['middleware' => ['role:driver']], function () {
        Route::post('/driver/change-status', [DriverController::class, 'changeStatus']);
        Route::get('driver/orders', [DriverOrderController::class,'index']);
        Route::get('driver/my-orders', [DriverOrderController::class,'myOrder']);
        Route::post('orders/{order}/delivered', [DriverOrderController::class,'delivered']);
        Route::get('driver/orders/{order}', [DriverOrderController::class,'show']);
        Route::post('driver/orders/{order}/accept_order', [DriverOrderController::class,'acceptOrder']);
        Route::post('driver/orders/{order}/on_the_way', [DriverOrderController::class,'onTheWay']);
        Route::post('driver/orders/{order}/ready_for_take', [DriverOrderController::class,'readyForTakeaway']);
        Route::post('update/my/location', [DriverController::class,'updateMyLocation']);
    });


    Route::group(['middleware' => ['role:customer']], function () {
        //orders routes
        Route::apiResource('orders', CustomerOrderController::class, array("as" => "api"));
        Route::post('orders/current', [CustomerOrderController::class,'current']);
        Route::post('orders/previous', [CustomerOrderController::class,'previous']);
        Route::get('orders/{order}/cheackIsOrderPaid', [CustomerOrderController::class,'cheackIsOrderPaidAfterRetrunFromPaymentWay']);
//        Route::post('orders/{order}/delivered', [CustomerOrderController::class,'delivered']);
        Route::post('orders/{order}/reorder', [CustomerOrderController::class,'reorder']);
        Route::post('orders/{order}/calc-reorder-item', [CustomerOrderController::class,'calcReorderWithOrder']);
        //other routes
        Route::post('items/favourite/{item}', [MenuController::class, 'favouriteItem']);
        Route::get('my-favourite-items', [UserController::class,'myFavourites']);
        Route::post('set-default-address/{address}', [UserController::class,'setDefaultAddress']);
        Route::post('restaurants/follow/{restaurant}', [RestaurantFollowController::class, 'followOrUnfollow']);
        Route::get('my-followed-restaurants', [UserController::class,'myFollows']);
        Route::post('/rate-order/{order}', [CustomerOrderController::class, 'rate'])
            ->middleware('can:rate-order,order');
        Route::get('/already-rated/restaurant/{restaurant}', [RestaurantRatingController::class,'already']);
        Route::post('orders/notify/late/{order}', [CustomerOrderController::class,'notify']);
        Route::get('my/discount-codes', [DiscountCodeController::class, 'myCodes']);
        Route::get('my/code-info/{discountCode}', [DiscountCodeController::class, 'myCodeInfo']);
        Route::post('/delete-my-account', [AuthController::class,'deleteMyAccount']);
        Route::post('/switch-notifications', [AuthController::class,'switchNotification']);
    });
    Route::apiResource('discount-codes', DiscountCodeController::class);

    Route::post('/password/change', [AuthController::class, 'changePassword']);
    Route::post('/language/change', [AuthController::class, 'changeLanguage']);
    Route::post('/my-info', [UserController::class,'updateMyInfo']);

    Route::post('send/notification/general', [NotificationController::class,'sendGeneralNotification'])
        ->middleware('can:send-notification');
    Route::post('send/notification/special', [NotificationController::class,'sendSpecialNotification'])
        ->middleware('can:send-notification');
    Route::get('get/notification/{type}', [NotificationController::class,'get'])
        ->middleware('can:send-notification');
    Route::get('show/notification/{generalNotification}', [NotificationController::class,'show'])
        ->middleware('can:send-notification');

    Route::get('get-my/notifications', [NotificationController::class,'getMyNotifications']);
    Route::get('get-my/notifications/{generalNotification}', [NotificationController::class,'getMyNotification']);
    Route::get('get-new/notifications/count', [NotificationController::class,'newNotificationsCount']);

    Route::get('/my-info', [UserController::class,'showMyInfo']);
    Route::post('/about-us', [TermsController::class,'updateAboutUs'])->middleware('can:edit-general-settings');
    Route::post('/terms-and-conditions', [TermsController::class,'updateTerms'])
        ->middleware('can:edit-general-settings');

    Route::get('/users/list/all/{type?}', [UserController::class,'list']);

    Route::get('/feedback', [FeedBackController::class,'index'])->middleware('can:show-feedback');
    Route::get('/feedback/{feedback}', [FeedBackController::class,'show'])->middleware('can:show-feedback');
    Route::post('/feedback/replay/{feedback}', [FeedBackController::class,'replay'])
        ->middleware('can:replay-feedback,feedback');
    Route::delete('/feedback/{feedback}', [FeedBackController::class,'delete'])
        ->middleware('can:delete-feedback,feedback');

    Route::post('/rest-offers', [OffersController::class,'updateRestaurantOffer'])->middleware('can:control-offer');
    Route::get('/rest-offers', [OffersController::class,'getRestaurantOffers'])->middleware('can:control-offer');
    Route::delete('/rest-offers/{restaurantOffer}', [OffersController::class,'destroyRestaurantOffer'])
        ->middleware('can:control-offer');
    Route::get('/rest-offers/{restaurantOffer}', [OffersController::class,'showRestaurantOffer'])
        ->middleware('can:control-offer');
    Route::post('/item-offers', [OffersController::class,'updateItemOffer'])->middleware('can:control-offer');
    Route::get('/item-offers', [OffersController::class,'getItemOffers'])->middleware('can:control-offer');
    Route::get('/item-offers/{itemOffer}', [OffersController::class,'showItemOffer'])->middleware('can:control-offer');
    Route::delete('/item-offers/{itemOffer}', [OffersController::class,'destroyItemOffer'])
        ->middleware('can:control-offer');
});

//this are test routes will be removed in production
Route::get('testing/{user}', function ($user) {
    $to = [];
    $user = User::where('id', $user)->first();
    $to[] = $user;
    Notification::send($to, new \App\Notifications\TestMyNotifications());
    return config("app.admin_url")."   ".config("app.name");
});



Route::get('time-now', function () {
    $date = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
    return $date;//test
});
Route::get('TestCronJop', function () {
    return TestCronJop::get();
    
});

Route::get('get-me-token/{user}', function (\App\Models\User $user) {
    $token = $user->createToken('auth_token', ['*'], null)->plainTextToken; //nothing
    return $token;
});

Route::get('change-user-password/{user}', function (\App\Models\User $user) {
    $user->update([
        "password" => bcrypt("123456"),
    ]);
    return $user;
});

Route::get('test-sms-service/{number}', function ($number) {
    return sendSms("Hi mr ahmed awd", $number);
});
