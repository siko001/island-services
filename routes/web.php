<?php

use App\Http\Controllers\CentralController;
use Illuminate\Support\Facades\Route;

foreach(config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function() {

        //home
        Route::get('/', function() {
            return redirect('/admin/get-companies');
        });

        //Redirect all undefined routes to main dashboard
        Route::fallback(function() {
            return redirect('/admin/get-companies');
        });

        //        Global
        Route::get('/admin/get-companies', [CentralController::class, 'index'])->name('central.index');

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
