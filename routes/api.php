<?php

use App\Http\Controllers\PriceHistoryController;
use App\Http\Controllers\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::post('/subscribe', SubscribeController::class);
Route::get('/prices', PriceHistoryController::class);
