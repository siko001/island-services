<?php

declare(strict_types=1);

use App\Http\Controllers\CentralController;
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

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function() {
    Route::fallback(function() {
        return redirect('/admin/dashboards/main');
    });

    Route::get('/login', function() {
        return redirect('/admin/dashboards/main');
    })->name('login');

    Route::get('/admin/get-companies', [CentralController::class, 'index'])->name('central.index');
    Route::post('/tenancy/add', [CentralController::class, 'store'])->name('tenancy.store');
    Route::post('/tenancy/delete/{id}', [CentralController::class, 'delete'])->name('tenancy.delete');
    Route::get('/tenancy/select/{id}', [CentralController::class, 'select'])->name('tenancy.select');

});
