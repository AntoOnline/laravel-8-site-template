<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

// /*
//   |--------------------------------------------------------------------------
//   | Web Routes
//   |--------------------------------------------------------------------------
//   |
//   | Here is where you can register web routes for your application. These
//   | routes are loaded by the RouteServiceProvider within a group which
//   | contains the "web" middleware group. Now create something great!
//   |
//  */

// /*
//  * General
//  */

// /**
//  * Only for guests/logged out users
//  */
// Route::middleware('guest')->group(function () {
//     Route::name('home')->get('/', [CustomAuthController::class, 'index']);
//     Route::name('login')->get('login', [CustomAuthController::class, 'index']);
//     Route::name('login')->post('login', [CustomAuthController::class, 'customLogin']);

//     Route::name('register.')->group(function () {
//         Route::name('user')->get('registration', [CustomAuthController::class, 'registration']);
//         Route::name('user')->post('registration', [CustomAuthController::class, 'customRegistration']);

//         Route::name('confirm')->get('password-change/{apitoken}/', [CustomAuthController::class, 'customRegistrationConfirmation']);
//         Route::name("confirm")->post('password-change/', [CustomAuthController::class, 'customRegistrationConfirmed']);
//     });

//     Route::name('forgot.')->group(function () {
//         Route::name('password')->get('forgot-password', [CustomAuthController::class, 'forgotPasswordView']);
//         Route::name('password')->post('forgot-password', [CustomAuthController::class, 'forgotPasswordPost']);
//     });
// });

// /**
//  * Only or logged in users
//  */
// Route::middleware(['auth'])->group(function () {
//     // Route::get('user-info', [CustomAuthController::class, 'userInfo'])->name("user-info");

//     Route::name('home')->get('/', [CustomAuthController::class, 'in

//     Route::name('logout')->get('logout', [CustomAuthController::class, 'logout']);

//     Route::name('settings')->get('settings', [CustomAuthController::class, 'settings']);
//     Route::name('save_settings')->post('save-settings', [CustomAuthController::class, 'saveSettings']);

//     Route::name('account.')->group(function () {
//         Route::name('delete')->get('delete-account', [CustomAuthController::class, 'deleteAccount']);
//         Route::name('delete')->post('delete-account', [CustomAuthController::class, 'deleteAccountConfirmed']);
//     });

//     Route::name('password.')->group(function () {
//         Route::name('change')->get('change-user-password', [CustomAuthController::class, 'changeUserPassword']);
//         Route::name('change')->post('change-user-password', [CustomAuthController::class, 'changeUserPasswordConfirmed']);
//     });

//     Route::name('event_log')->get('event-log', [CustomAuthController::class, 'eventLogView']);
// });


/**
 * New, Reorganised routes
 */

// All routes to webpages will be under web prefix. This will seperate web and API routes.
Route::name('web.')->group(function(){

    Route::get('/', [WebController::class, 'index']);
    Route::get('/home', [WebController::class, 'index'])->name('home');

    //All the functionality that only logged out users can access
    Route::middleware('guest')->group(function(){
        // using view for shorthand, but if you want to use get, use WebController for methods
        Route::get('/login', [WebController::class, 'login'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);

        Route::get('/register', [WebController::class, 'register'])->name('register');
        Route::post('/register', [UserController::class, 'register']);

        Route::get('/set-password', [WebController::class, 'setPassword'])->name('set_password'); // Get
        Route::post('/set-password', [UserController::class, 'setPassword']);

        Route::get('/forgot-password', [WebController::class, 'forgotPassword'])->name('forgot_password');
        Route::post('/forgot-password',  [UserController::class, 'forgotPassword']);

    });

    // All the functionality only logged in users can access
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        //TODO Tasks: View are all done, only need to set the functions right in the UserController and make mailables
        Route::prefix('/admin')->name('admin.')->group(function(){
            Route::get('/', [DashboardController::class, 'index'])->name('index');

            Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
            Route::post('/settings', [UserController::class, 'settings'] );

            Route::get('/delete-account', [DashboardController::class, 'deleteAccount'])->name('delete_account');
            Route::post('/delete-account', [UserController::class, 'deleteAccount']);

            Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('change_password');
            Route::post('/change-password', [UserController::class, 'changePassword']);

            Route::get('/event-log', [DashboardController::class, 'eventLog'])->name('event_log');
        });
    });
});

