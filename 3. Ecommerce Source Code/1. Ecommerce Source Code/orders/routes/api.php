<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/orders', [OrderController::class, 'store']);
