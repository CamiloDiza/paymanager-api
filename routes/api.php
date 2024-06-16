<?php

use App\Http\Controllers\DistributionController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\PayerController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\ReceiverController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('receiver', ReceiverController::class);
Route::apiResource('payer', PayerController::class);
Route::apiResource('platform', PlatformController::class);
Route::apiResource('period', PeriodController::class);
Route::apiResource('income', IncomeController::class);
Route::apiResource('distribution', DistributionController::class);
