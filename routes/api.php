<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout');
    Route::post('refresh-token', 'refresh');
    Route::post('authorized-user', 'user');
});

Route::controller(PasswordResetController::class)->group(function () {
    Route::post('/forgot-password', 'forgotPassword')->name('password.email');
    Route::get('/reset-password/{token}', 'passwordReset')->name('password.reset');
    Route::post('/reset-password', 'passwordUpdate')->name('password.update');
});
