<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Tenants\AuditTrailController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

//WEB ROUTES
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function() {

    Route::group(['prefix' => 'admin/audit-trails'], function() {
        Route::get('/login', [AuditTrailController::class, "login"])->name('audit-trails.login');
        Route::get('/system', [AuditTrailController::class, "system"])->name('audit-trails.system');
    });

    Route::fallback(function() {
        return redirect('/admin/dashboards/main');
    });

    Route::get('/login', function() {
        return redirect('/admin/dashboards/main');
    })->name('login');
});

//API ROUTES
Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class
])->group(function() {
    Route::group(['prefix' => 'api/v1'], function() {
        Route::get('/customer/create', [CustomerApiController::class, "store"])->name('customer.create');
    });
});
