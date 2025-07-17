<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardBannerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardDeviceController;
use App\Http\Controllers\DashboardOrderController;
use App\Http\Controllers\DashboardProductBannerController;
use App\Http\Controllers\DashboardProductController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\DashboardVoucherController;
use App\Http\Controllers\Datatable\VoucherController;
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

// Route::get('/', function () {
//    return view('pages.index');
// });

Route::get('/device/login', [DeviceAuthenticateController::class, 'index'])->name('device.login');
Route::group(['middleware' => ['check.device']], function () {
    Route::post('/device/auth', [DeviceAuthenticateController::class, 'authtenticate'])->name('device.auth');

    Route::get('/', [HomepageController::class, 'index'])->name('home');
    Route::resource('order', OrderController::class)->only(['index', 'store']);
    Route::post('check-voucher', [OrderController::class, 'checkVoucher'])->name('order.check-voucher');
    Route::put('update-order-status', [OrderController::class, 'updateOrderStatus'])->name('order.update-status');
});

Route::get('login', [AuthController::class, 'index'])->name('login.index');
Route::post('authenticate', [AuthController::class, 'authenticate'])->name('login.auth');

Route::group(['middleware' => ['check.auth']], function () {
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::resources([
            'banners' => DashboardBannerController::class,
            'devices' => DashboardDeviceController::class,
            'users' => DashboardUserController::class,
            'vouchers' => DashboardVoucherController::class,
            'product.banners' => DashboardProductBannerController::class,
            'orders' => DashboardOrderController::class,
        ]);

        Route::group(['prefix' => 'datatable', 'as' => 'datatable.'], function () {
            Route::get('vouchers', VoucherController::class)->name('vouchers');
            Route::get('orders', \App\Http\Controllers\Datatable\OrderController::class)->name('orders');
        });

        Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
            Route::get('/', [DashboardProductController::class, 'index'])->name('index');
            Route::get('edit', [DashboardProductController::class, 'edit'])->name('edit');
            Route::put('update', [DashboardProductController::class, 'update'])->name('update');
        });
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('login.logout');
});
