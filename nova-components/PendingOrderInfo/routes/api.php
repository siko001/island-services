<?php

use Illuminate\Support\Facades\Route;
use IslandServices\PendingOrderInfo\PendingOrderController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

Route::get('/', [PendingOrderController::class, 'index']);
Route::get('/get-custom-prods/{orderNumber}', [PendingOrderController::class, 'getOrderProducts']);
Route::post('/convert-offer/{id}', [PendingOrderController::class, 'convertOffer']);
