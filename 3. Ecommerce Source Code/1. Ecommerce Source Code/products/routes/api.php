<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/products', [ProductController::class, 'store']);
Route::get('/v1/products', [ProductController::class, 'index']);
Route::get('/v1/products/{product}', [ProductController::class, 'get']);
Route::get('/v1/categories', [CategoryController::class, 'index']);
Route::post('/v1/categories', [CategoryController::class, 'store']);
