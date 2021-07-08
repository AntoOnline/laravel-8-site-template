<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\CustomAuthController;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\XmlConfiguration\Group;

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

/*
 * General
 */

Route::get('/', [WebsiteController::class, 'index'])->name('home');

Route::get('/abous-us', [WebsiteController::class, 'aboutus'])->name('aboutus');

/**
 * Only for logged out users/guests
 */
Route::middleware('guest')->group(function () {
    Route::name('login')->get('login', [CustomAuthController::class, 'index']);
    Route::name('login')->post('login', [CustomAuthController::class, 'customLogin']);

    Route::name('register.')->group(function () {
        Route::name('user')->get('registration', [CustomAuthController::class, 'registration']);
        Route::name('user')->post('registration', [CustomAuthController::class, 'customRegistration']);
    });

    Route::name('forgot.')->group(function () {
        Route::name('password')->get('forgot-password', [CustomAuthController::class, 'forgotPasswordView']);
        Route::name('password')->post('forgot-password', [CustomAuthController::class, 'forgotPasswordPost']);
    });

    Route::name('registration.')->group(function () {
        Route::name('confirmation')->get('password-change/{apitoken}/', [CustomAuthController::class, 'customRegistrationConfirmation']);
        Route::name("confirmation")->post('password-change/', [CustomAuthController::class, 'customRegistrationConfirmed']);
    });
});

/**
 * Only or logged in users
 */
Route::middleware(['auth'])->group(function () {
    // Route::get('user-info', [CustomAuthController::class, 'userInfo'])->name("user-info");
    Route::name('welcome')->get('welcome', [CustomAuthController::class, 'welcome']);

    Route::name('logout')->get('logout', [CustomAuthController::class, 'logout']);

    Route::name('settings')->get('settings', [CustomAuthController::class, 'settings']);
    Route::name('save_settings')->post('save-settings', [CustomAuthController::class, 'saveSettings']);

    Route::name('account.')->group(function () {
        Route::name('delete')->get('delete-account', [CustomAuthController::class, 'deleteAccount']);
        Route::name('delete_confirmed')->post('delete-account-confirmed', [CustomAuthController::class, 'deleteAccountConfirmed']);
    });

    Route::name('password.')->group(function () {
        Route::name('change')->get('change-user-password', [CustomAuthController::class, 'changeUserPassword']);
        Route::name('change')->post('change-user-password', [CustomAuthController::class, 'changeUserPasswordConfirmed']);
    });

    Route::name('event_log')->get('event-log', [CustomAuthController::class, 'eventLogView']);
});

