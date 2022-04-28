<?php

use App\Http\Controllers\API\ArchiveController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OutSourceController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\ReportsController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\StoreOrderController;
use App\Http\Controllers\API\DriverOrderController;
use App\Http\Controllers\API\ChargeController;
use App\Http\Controllers\API\TownController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UploadController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\DriverTempController;
use App\Http\Controllers\API\StoreController;
use App\Http\Controllers\API\TypeController;
use App\Http\Controllers\TestController;
use App\Models\User;
use App\Notifications\NewOrder;
use App\Services\GeoDistance;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

Route::post('/driver/login', [AuthController::class, 'driverLogin']);
Route::post('/driver/register', [AuthController::class, 'register']);
Route::post('/store/login', [AuthController::class, 'storeLogin']);
Route::post('/company/login', [AuthController::class, 'companyLogin']);


Route::post('change-locale', [UserController::class, 'changeLangLocale'])->name('change-lang');

Route::post('/upload/image', [UploadController::class, 'upload']);
Route::post('/upload/public-image', [UploadController::class, 'uploadOnlyFile']);
//     test controller -------------------
Route::get('/remove-number/{number}', [TestController::class,'removeNumber']);
Route::get('/get-user/{number}', [TestController::class,'userByNumber']);
Route::get('/get-me-token/{user}', [TestController::class,'getMeToken']);
Route::get('/test-orders-last', [TestController::class,'testOrders']);
Route::get('/test-send-sms/{number}', [TestController::class,'testSMS']);
Route::get('/get-all-status', [TestController::class,'getOrderStatus']);
Route::get('/get-current-lang', [TestController::class,'getCurrentLang']);
// -----------------------------------------


Route::resource('/posts', PostController::class);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

Route::get('/setting/general-config', [SettingController::class, 'general']);
//new apis
Route::get('/store-types', [StoreController::class,'types']);
Route::post('/owner/register', [AuthController::class,'ownerRegister']);
Route::post('/owner/verify', [AuthController::class,'ownerVerify']);
Route::post('/owner/resend-verify', [AuthController::class,'resendVerify']);


Route::get('/towns', [TownController::class,'index']);
//end


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-lang', [UserController::class,'changeLang']);
    Route::post('/password/change', [AuthController::class, 'changePassword']);
    Route::group(['middleware' => ['role:driver|owner']], function () {
        Route::post('/charge', [ChargeController::class,'charge'])->middleware('chargeAttempt');
        Route::post('/code/generate', [AuthController::class,'generateCode']);
        Route::post('/code/check', [AuthController::class,'checkCode'])->middleware('codeAttempt');
        Route::get('/packages', [PackageController::class,'index']);
        Route::get('/transaction/history/{type?}', [TransactionController::class,'history']);
        Route::post('/archive', [ArchiveController::class,'archiveAll']);
        Route::post('/archive/{order}', [ArchiveController::class,'archive']);
    });

    Route::group(['middleware' => ['role:outsource']], function () {
        Route::get('/company/data', [OutSourceController::class, 'data']);
        Route::resource('/company/stores', StoreController::class);
        Route::get('/types', [TypeController::class, 'index']);
    });


    Route::group(['middleware' => ['role:owner']], function () {
        Route::resource('store/order', StoreOrderController::class);
        Route::post('store/order/estimate', [StoreOrderController::class,'estimate']);
        Route::get('store/susponded', [StoreOrderController::class, 'indexSusponded']);
        Route::get('store/data', [StoreOrderController::class, 'data']);
        Route::get('store/statistics', [StoreOrderController::class, 'statistics']);
        Route::post('/store/order/rate/{order}', [StoreOrderController::class, 'rate']);
        Route::get('/store/reorder/{order}', [StoreOrderController::class, 'reorder']);
        Route::post('store/cancel/{order}', [StoreOrderController::class, 'cancel']);
        Route::post('/store/setting', [StoreController::class, 'saveSettings']);
        Route::get('/store/setting', [StoreController::class, 'settings']);
        Route::post('owner/update/my-info', [UserController::class,'updateOwnerInfo']);
        Route::get('owner/my-reports', [ReportsController::class,'ownerReport']);
    });

    Route::group(['middleware' => ['role:driver']], function () {
        Route::get('/driver/order', [DriverOrderController::class, 'index']);
        Route::get('/driver/order/new', [DriverOrderController::class, 'newOrder'])->middleware('IsDisabledAttempt');
        Route::get('/driver/data', [DriverOrderController::class, 'data']);
        Route::get('/driver/statistics', [DriverOrderController::class, 'statistics']);
        Route::get('/driver/order/{order}', [DriverOrderController::class, 'show'])->middleware('IsDisabledAttempt');
        Route::get('/driver/accept/{order}', [DriverOrderController::class, 'acceptOrder'])->middleware('IsDisabledAttempt');
        Route::post('/driver/delivery/{order}', [DriverOrderController::class, 'deliveryOrder'])->middleware('IsDisabledAttempt');
        Route::post('/driver/delivered/{order}', [DriverOrderController::class, 'deliveredOrder'])->middleware('IsDisabledAttempt');
        Route::post('/driver/change-availability', [UserController::class, 'changeAvailability']);
        Route::post('driver/update', [DriverTempController::class,'store']);
        Route::post('update/my/location', [AuthController::class,'updateMyLocation']);
        Route::post('driver/update/my-info', [UserController::class,'updateDriverInfo']);
        Route::post('driver/update/my-papers', [UserController::class,'updateDriverPapers']);
        Route::post('driver/confirm/new-phone', [UserController::class,'confirmNewPhone']);
        Route::post('driver/update/my-phone', [UserController::class,'changeMyPhone']);
        Route::post('driver/resend/code/new-phone', [UserController::class,'resendNewPhoneCode']);
        //reports controller
        Route::get('driver/my-reports', [ReportsController::class,'driverReport']);
        Route::post('driver/update-online-time', [UserController::class,'updateOnlineTime']);
        //online time
    });
});

Route::post('test_distance/{order}', function (\Illuminate\Http\Request $request, \App\Models\Order $order) {

    return getDistanceBetween($request->lat, $request->lng, $order->customer_lat, $order->customer_lng);
});
