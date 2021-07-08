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

Route::get('/aws-ip-address-ranges', [\App\Http\Controllers\AwsIpAddressRanges::class, 'index'])->name('aws-ip-address-ranges.index');

Route::get('/base-64-encode-decode', [App\Http\Controllers\Base64EncodeDecode::class, 'index'])->name('base-64-encode-decode.index');

Route::get('/chmod-calculator', [\App\Http\Controllers\ChmodCalculator::class, 'index'])->name('chmod-calculator.index');

Route::get('/data-multiplier', [App\Http\Controllers\DataMultiplier::class, 'index'])->name('data-multiplier.index');
Route::post('/data-multiplier', [App\Http\Controllers\DataMultiplier::class, 'index'])->name('data-multiplier.index');

Route::get('/http-status-checker', [\App\Http\Controllers\HttpStatusChecker::class, 'index'])->name('http-status-checker.index');

Route::get('/ntp-tester', [\App\Http\Controllers\NtpTester::class, 'index'])->name('ntp-tester.index');
Route::post('/ntp-tester', [\App\Http\Controllers\NtpTester::class, 'index'])->name('ntp-tester.index');

Route::get('/string-tools', [App\Http\Controllers\StringTools::class, 'index'])->name('string-tools.index');
Route::post('/string-tools', [App\Http\Controllers\StringTools::class, 'index'])->name('string-tools.index');

Route::get('/site-file-checker', [App\Http\Controllers\SiteFileChecker::class, 'index'])->name('site-file-checker.index');

Route::get('/url-encode-decode', [\App\Http\Controllers\UrlEncodeDecode::class, 'index'])->name('url-encode-decode.index');

Route::get('/what-is-my-ip-address', [\App\Http\Controllers\WhatIsMyIPAddress::class, 'index'])->name('what-is-my-ip-address.index');

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

