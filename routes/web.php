<?php

use App\Http\Controllers\Central\CentralController;
use App\Http\Controllers\Tenants\AuditTrailController;
use Illuminate\Support\Facades\Route;

foreach(config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function() {

        //home
        Route::get('/', function() {
            return redirect('/');
        });

        //Redirect all undefined routes to main dashboard
        Route::fallback(function() {
            return redirect('/');
        });

        //View Routes
        Route::get('/', [CentralController::class, 'index'])->name('central.index');

        Route::get('/login', [CentralController::class, 'showLoginForm'])->name('central.login');
        Route::get('/logout', [CentralController::class, 'logout'])->name('central.logout');
        Route::get('/register', [CentralController::class, 'showRegistrationForm'])->name('central.register');
        Route::get('/forgot-password', [CentralController::class, 'showForgotPasswordForm'])->name('central.forgot-password');
        Route::get('/reset-password', [CentralController::class, 'showResetPasswordForm'])->name('central.reset-password');
        Route::get('/user/{id}/settings/', [CentralController::class, 'showAccountSettings'])->name('central.account-settings');
        Route::get('/login-trail', [AuditTrailController::class, 'centralLogin'])->name('central.login-trail');

        //Functional Routes
        Route::post('/login', [CentralController::class, 'loginUser'])->name('user.login');
        Route::post('/logout', [CentralController::class, 'logout'])->name('user.logout');
        Route::post('/register', [CentralController::class, 'registerUser'])->name('user.register');
        Route::post('/forgot-password', [CentralController::class, 'sendForgotPasswordToken'])->name('user.forgot-password');
        Route::post('/reset-password', [CentralController::class, 'resetPassword'])->name('password.reset');
        Route::post('/user/{id}/settings/', [CentralController::class, 'updateAccountSettings'])->name('user.update');

        //Tenant Management
        Route::group(['prefix' => "/tenancy"], function() {
            Route::post('/add', [CentralController::class, 'store'])->name('tenancy.store');
            Route::get('/edit/{id}', [CentralController::class, 'edit'])->name('tenancy.edit');
            Route::put('/update/{id}', [CentralController::class, 'update'])->name('tenancy.update');
            Route::post('/delete/{id}', [CentralController::class, 'delete'])->name('tenancy.delete');
            Route::get('/select/{id}', [CentralController::class, 'select'])->name('tenancy.select');
        });

    });
}
