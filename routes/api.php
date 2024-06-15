<?php

use App\Http\Controllers\PayerController;
use App\Http\Controllers\ReceiverController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('receiver', ReceiverController::class);
Route::apiResource('payer', PayerController::class);
