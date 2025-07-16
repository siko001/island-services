<?php

use App\Http\Controllers\AuditTrailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//Audit Trails Routes
Route::group(['prefix' => 'audit-trails'], function () {
    Route::get('/login', [AuditTrailController::class, "login" ])->name('audit-trails.login');
    Route::get('/system',[AuditTrailController::class, "system" ])->name('audit-trails.system');
});
