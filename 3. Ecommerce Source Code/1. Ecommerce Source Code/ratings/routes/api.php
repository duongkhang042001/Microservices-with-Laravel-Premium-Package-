<?php

use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/products/{product}/ratings', [RatingController::class, 'store']);
Route::get('/v1/products/{product}/ratings', [RatingController::class, 'get']);
Route::get('/v1/products/ratings', [RatingController::class, 'index']);
