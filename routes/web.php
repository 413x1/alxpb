<?php

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
