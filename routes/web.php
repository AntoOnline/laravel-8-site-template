<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\CustomAuthController;
use Illuminate\Support\Facades\DB;

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

/*
 * Auth
 */
// Route::get('user-info', [CustomAuthController::class, 'userInfo'])->middleware('auth')->name("user-info");
Route::get('login-welcome', [CustomAuthController::class, 'loginWelcome'])->name('loginWelcome')->middleware('auth');;
Route::get('login', [CustomAuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom')->middleware('guest');

Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout')->middleware('auth');

Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user')->middleware('guest');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom')->middleware('guest');

Route::get('forgot-password', [CustomAuthController::class, 'forgotPasswordView'])->name('forgot.password')->middleware('guest');
Route::post('forgot-password', [CustomAuthController::class, 'forgotPasswordPost']);

Route::get('password-change/{apitoken}/', [CustomAuthController::class, 'customRegistrationConfirmation'])->name('registration.confirmation');

Route::post('change-password', [CustomAuthController::class, 'passwordChangePost'])->name("change.password")->middleware('guest');

Route::get('settings', [CustomAuthController::class, 'settings'])->name('settings')->middleware('auth');
Route::post('save-settings', [CustomAuthController::class, 'saveSettings'])->name('save.settings')->middleware('auth');

Route::post('delete-account', [CustomAuthController::class, 'deleteAccount'])->name('delete-account')->middleware('auth');
Route::post('delete-account-confirmed', [CustomAuthController::class, 'deleteAccountConfirmed'])->name('delete-account-confirmed')->middleware('auth');

Route::get('change-user-password', [CustomAuthController::class, 'changeUserPassword'])->name('change-user-password')->middleware('auth');
Route::post('change-user-password-confirmed', [CustomAuthController::class, 'changeUserPasswordConfirmed'])->name('change-user-password-confirmed')->middleware('auth');


Route::post('event-log', [CustomAuthController::class, 'eventLogView'])->name('event-log')->middleware('auth');

/*
 * Cron
 */
Route::get("sendEmailNotifications", [CustomAuthController::class, 'sendEmailNotifications']);
