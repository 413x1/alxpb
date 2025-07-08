<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardBannerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardDeviceController;
use App\Http\Controllers\DashboardProductController;
use App\Http\Controllers\DashboardVoucherController;
use App\Http\Controllers\DeviceAuthenticateController;
use App\Http\Controllers\Pages\HomepageController;
use App\Http\Controllers\Pages\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('pages.index');
//});


Route::get('/device/login', [DeviceAuthenticateController::class, 'index'])->name('device.login');
Route::group(['middleware' => ['check.device']], function () {
    Route::post('/device/auth', [DeviceAuthenticateController::class, 'authtenticate'])->name('device.auth');

    Route::get('/', [HomepageController::class, 'index'])->name('home');
    Route::get('/order', [OrderController::class, 'index'])->name('order');
});

Route::get('login', [AuthController::class, 'index'])->name('login.index');
Route::post('authenticate', [AuthController::class, 'authenticate'])->name('login.auth');

Route::group(['middleware' => ['check.auth']], function () {
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::group(['prefix' => 'banners', 'as' => 'banner.'], function () {
            Route::get('/', [DashboardBannerController::class, 'index'])->name('index');
        });

        Route::group(['prefix' => 'devices', 'as' => 'device.'], function () {
            Route::get('/', [DashboardDeviceController::class, 'index'])->name('index');
        });

        Route::group(['prefix' => 'vouchers', 'as' => 'voucher.'], function () {
            Route::get('/', [DashboardVoucherController::class, 'index'])->name('index');
            Route::get('/datatble', [DashboardVoucherController::class, 'getVoucherData'])->name('datatable');
        });

        Route::group(['prefix' => 'products', 'as' => 'product.'], function () {
            Route::get('/', [DashboardProductController::class, 'index'])->name('index');
        });
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('login.logout');
});
