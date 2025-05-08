<?php

use App\Http\Controllers\Api\WbApiController;
use Illuminate\Support\Facades\Route;

Route::get('/sales', [WbApiController::class, 'sales']);
Route::get('/orders', [WbApiController::class, 'orders']);
Route::get('/stocks', [WbApiController::class, 'stocks']);
Route::get('/incomes', [WbApiController::class, 'incomes']);
Route::get('/all', [WbApiController::class, 'allData']);
