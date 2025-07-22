<?php

use App\Http\Controllers\AuditTrailController;
use Illuminate\Support\Facades\Route;

//Audit Trails Routes
Route::group(['prefix' => 'admin/audit-trails'], function() {
    Route::get('/login', [AuditTrailController::class, "login"])->name('audit-trails.login');
    Route::get('/system', [AuditTrailController::class, "system"])->name('audit-trails.system');
});

//Redirect all undefined routes to main dashboard
Route::fallback(function() {
    return redirect('/admin/dashboards/main');
});

// Redirect root to main dashboard
Route::get('/', function() {
    return redirect('/admin/dashboards/main');
});
