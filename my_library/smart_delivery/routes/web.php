<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TownsController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageCodeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ChargeStoreController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DriverTempController;
use App\Http\Controllers\OutSourceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $social = \App\Models\Setting::social();
    $apps   = \App\Models\Setting::apps();
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'social' => $social,
        'apps' => $apps,
    ]);
});

Route::get('current_lang', function () {
    return config('app.env');
});

Route::get('page/{post}', [PostController::class,'show']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/seeder', [DashboardController::class, 'seeder'])->name('seeder');
Route::group(['middleware' => ['role:admin']], function () {
    Route::resource('stores', StoreController::class);
    Route::resource('towns', TownsController::class);

    //reports
    Route::get('/reports/of/store', [ReportsController::class,'store'])->name('reports.store');
    Route::get('/reports/of/driver', [ReportsController::class,'driver'])->name('reports.driver');
    Route::get('/reports/of/all-stores', [ReportsController::class,'allStores'])->name('reports.all-stores');
    Route::get('/reports/of/all-drivers', [ReportsController::class,'allDrivers'])->name('reports.all-drivers');
    Route::get('/reports/of/the-app', [ReportsController::class,'general'])->name('reports.general');
    Route::get('top-online', [ReportsController::class, 'topOnlineDrivers'])->name('top-online');
    Route::get('orders-movement', [ReportsController::class, 'ordersMovement'])->name('orders-movement');
    Route::get('/reports/of/store-orders', [ReportsController::class, 'storeOrdersReport'])->name('reports.store-orders');
    Route::get('/reports/of/store-charge', [ReportsController::class, 'storeChargeReport'])->name('reports.store-charge');
    Route::get('/reports/of/orders-all', [ReportsController::class, 'ordersReport'])->name('reports.orders-all');
    //report apis
    Route::get('/reports/of/owner/{store}', [ReportsController::class,'ownerReportToAdmin']);
    Route::get('/reports/of/driver/{user}', [ReportsController::class,'driverReportToAdmin']);
    Route::get('/exports/of/driver/{user}', [ReportsController::class,'exportDriverReportToAdmin']);
    Route::get('/exports/of/owner/{store}', [ReportsController::class,'exportOwnerReportToAdmin']);
    Route::get('/reports/of/all/drivers', [ReportsController::class,'allDriversReport']);
    Route::get('/reports/of/all/stores', [ReportsController::class,'allStoresReport']);
    Route::get('/reports/of/all/app', [ReportsController::class,'generalReportApi']);
    Route::get('/exports/of/all/stores', [ReportsController::class,'exportAllStoresReport']);
    Route::get('/exports/of/all/drivers', [ReportsController::class,'exportAllDriversReport']);
    Route::get('/exports/of/all/app', [ReportsController::class,'exportGeneralReportApi']);
    Route::get('get-top-online', [ReportsController::class, 'getTopOnline']);
    Route::get('get-orders-movement', [ReportsController::class, 'getOrdersMovement']);

    Route::get('export/of/stores/charges', [ReportsController::class, 'exportStoresChargeReport']);
    Route::get('export/of/stores/orders', [ReportsController::class, 'exportStoreOrdersReport']);
    Route::get('export/of/general/orders', [ReportsController::class, 'ordersReportData']);


    Route::patch('towns/switch-status/{town}', [TownsController::class, 'switchStatus'])->name('towns.switch-status');
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::resource('outsources', OutSourceController::class);
    Route::get('orders/all', [OrderController::class,'all'])->name('orders.all');
    Route::patch('users/switch-status/{user}', [UserController::class, 'switchStatus'])->name('users.switch-status');
    Route::patch('users/enable-user/{user}', [UserController::class, 'enableUser'])->name('users.enable-user');
    Route::patch('users/disable-user/{user}', [UserController::class, 'disableUser'])->name('users.disable-user');

    Route::resource('drivers', DriverController::class);
    Route::get('get/available-drivers', [DriverController::class, 'getAvailableDrivers']);

    Route::post('drivers/change-status/{user}', [DriverController::class, 'changeStatus'])->name('drivers.changeStatus');
    Route::post('drivers/confirm-papers/{user}', [DriverController::class, 'confirmPapers'])->name('drivers.confirm_papers');
    Route::get('driver-temps/accept/{id}', [DriverTempController::class,'acceptUpdate'])->name('driver-temps.accept');
    Route::resource('driver-temps', DriverTempController::class);
    Route::get('drivers/transactions/{driver}', [DriverController::class,'history'])->name('driver.transactions');
    Route::resource('packages', PackageController::class);
    Route::resource('posts', PostController::class, ['only' => ['index', 'create', 'edit', 'store','update','destroy']]);
    Route::get('packages/{package}/codes', [PackageCodeController::class, 'index'])->name('packages.codes.index');
    Route::get('packages/{package}/create', [PackageCodeController::class, 'store'])->name('packages.codes.create');
    Route::get('packages/of/all', [PackageCodeController::class,'allCodes'])->name('packages.codes.all');
    Route::resource('stores.orders', OrderController::class, ['only' => ['index', 'create', 'edit', 'store']])->shallow();
    Route::get('/driver/new-papers/{user}', [DriverController::class, 'newPapers'])->name('drivers.newPapers');
    Route::get('driver/all/on-map/{town}', [DriverController::class,'generalMap']);
    Route::post('orders/change-driver/{order}', [OrderController::class, 'changeDriver'])->name('order.change-driver');
});

Route::group(['middleware' => ['role:admin|owner']], function () {
    Route::resource('orders', OrderController::class, ['only' => ['index', 'create', 'edit', 'store','update']]);
    Route::get('current-active/orders', [OrderController::class,'currentActive'])->name('orders.current-active');
    Route::get('orders/trace-this/{order}', [OrderController::class,'traceOrder'])->name('orders.trace-order');
    Route::get('orders/{serial}/by-serial', [OrderController::class, 'showSerial'])->where('serial', '\d+,\d+')->name('orders.by-serial');
    Route::resource('stores.orders', OrderController::class, ['except' => ['index', 'create', 'edit', 'store']])->shallow();
    Route::get('stores/details/{store}', [StoreController::class,'view'])->name('stores.info');
    Route::get('stores/settings/{store}', [StoreController::class,'settings'])->name('stores.settings');
    Route::post('stores/settings/{store}', [StoreController::class,'saveSettings'])->name('stores.saveSettings');
    Route::get('store/transactions/{store?}', [StoreController::class,'history'])->name('store.transactions');
    Route::get('stores/{store}/susponded-orders', [OrderController::class, 'indexSusponded'])->name('stores.orders.susponded.index');
    Route::get('/susponded-orders', [OrderController::class, 'indexSusponded'])->name('orders.susponded.index');
    Route::post('orders/{order}/rate', [OrderController::class, 'rate'])->name('order.rate');
    Route::post('orders/{order}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});


Route::group(['middleware' => ['role:owner']], function () {
     Route::post('stores/charge-balance', [ChargeStoreController::class, 'charge'])->middleware('chargeAttempt')->name('charge-balance');
});


Route::get('/set-lang/{lang}', [UserController::class, 'switchLang'])->name("set-lang");


Route::get('time-now', function () {
    $date = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
    return $date;//test now
});
Route::any('test')->name('test');
