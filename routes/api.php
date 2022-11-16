<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth:api'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout')->name('logout');
        Route::post('authorized-user', 'user')->name('auth.user');
    });
    Route::controller(MovieController::class)->group(function () {
        Route::post('movies', 'store')->name('movie.store');
        Route::get('movies', 'show')->name('movie.show');
        Route::get('movies-all', 'index')->name('movie.index');
        Route::get('movies/{movie}', 'selectMovie')->name('movie.select');
        Route::delete('movies/{movie}', 'destroy')->name('movie.destroy');
        Route::patch('movies/{movie}', 'update')->name('movie.update');
    });
    Route::controller(QuoteController::class)->group(function () {
        Route::get('post', 'getPost')->name('view.post');
        Route::get('search-post', 'searchPost')->name('search.post');
        Route::get('quote/{quote}', 'show')->name('show.post');
        Route::post('quote', 'store')->name('store.quote');
        Route::patch('quote/{quote}', 'update')->name('quote.update');
        Route::delete('quote/{quote}', 'destroy')->name('quote.destroy');
    });
    Route::post('likes', [LikeController::class, 'store'])->name('like.store');
    Route::delete('likes/{like}', [LikeController::class, 'destroy'])->name('like.destroy');
    Route::post('comment', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('comment/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');

    Route::get('categories', [CategoryController::class, 'index'])->name('view.category');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notiification.index');
    Route::patch('notifications', [NotificationController::class, 'update'])->name('notiification.update');

    Route::controller(UserController::class)->group(function () {
        Route::patch('name', 'updateName')->name('name.update');
        Route::patch('password', 'updatePassword')->name('password.update');
        Route::post('add-email', 'addEmail')->name('add.email');
        Route::get('secondary-email', 'getSecondaryEmail')->name('secondary.email');
        Route::post('make-primary', 'makePrimary')->name('make.primary');
        Route::delete('email', 'destroyEmail')->name('destroy.email');
        Route::post('profile-image', 'storeProfileImage')->name('profile.image');
    });
});

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('google-login', 'googleLogin')->name('google.login');
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::get('/email/verify/{id}/{hash}', 'secondaryVerify')->name('secondary.verify');
});

Route::get('quote', [QuoteController::class, 'index'])->name('view.quote');
Route::controller(PasswordResetController::class)->group(function () {
    Route::post('/forgot-password', 'forgotPassword')->name('password.email');
    Route::post('/reset-password', 'passwordUpdate')->name('password.update');
});
