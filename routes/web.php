<?php

use App\Http\Controllers\CentralController;
use Illuminate\Support\Facades\Route;

//Audit Trails Routes
//Route::group(['prefix' => 'admin/audit-trails'], function() {
//    Route::get('/login', [AuditTrailController::class, "login"])->name('audit-trails.login');
//    Route::get('/system', [AuditTrailController::class, "system"])->name('audit-trails.system');
//});

//// Redirect root to main dashboard
///

foreach(config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function() {
        Route::get('/', function() {
            return redirect('/admin/get-companies');
        });

        //////Redirect all undefined routes to main dashboard
        Route::fallback(function() {
            return redirect('/admin/get-companies');
        });

        Route::get('/admin/get-companies', [CentralController::class, 'index'])->name('central.index');
        Route::post('/tenancy/add', [CentralController::class, 'store'])->name('tenancy.store');
        Route::post('/tenancy/delete/{id}', [CentralController::class, 'delete'])->name('tenancy.delete');
        Route::get('/tenancy/select/{id}', [CentralController::class, 'select'])->name('tenancy.select');

    });
}
