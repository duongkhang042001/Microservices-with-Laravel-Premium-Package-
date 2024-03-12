<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductInventoryController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/inventory', [InventoryController::class, 'store']);
Route::get('/v1/inventory/products', [ProductInventoryController::class, 'index']);
Route::get('/v1/inventory/products/{product}', [ProductInventoryController::class, 'get']);
Route::get('/v1/warehouses', [WarehouseController::class, 'index']);
Route::post('/v1/warehouses', [WarehouseController::class, 'store']);
