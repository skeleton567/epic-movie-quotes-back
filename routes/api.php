<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth:api'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout')->name('logout');
        Route::post('authorized-user', 'user')->name('auth.user');
    });
    Route::post('likes', [LikeController::class, 'store'])->name('like.store');
    Route::delete('likes', [LikeController::class, 'destroy'])->name('like.destroy');
    Route::post('comment', [CommentController::class, 'store'])->name('comment.store');
    Route::get('post', [QuoteController::class, 'getPost'])->name('view.post');

    Route::controller(UserController::class)->group(function () {
        Route::patch('update-name', 'updateName')->name('name.update');
        Route::post('add-email', 'addEmail')->name('add.email');
        Route::get('secondary-email', 'getSecondaryEmail')->name('secondary.email');
        Route::post('make-primary', 'makePrimary')->name('make.primary');
    });
});

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('refresh-token', 'refresh')->name('refresh.token');
    Route::post('google-login', 'googleLogin')->name('google.login');
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
});

Route::get('quote', [QuoteController::class, 'index'])->name('view.quote');
Route::controller(PasswordResetController::class)->group(function () {
    Route::post('/forgot-password', 'forgotPassword')->name('password.email');
    Route::post('/reset-password', 'passwordUpdate')->name('password.update');
});
