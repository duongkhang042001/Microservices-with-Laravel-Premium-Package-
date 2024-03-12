<?php

use App\Http\Controllers\CatalogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/v1/catalog', [CatalogController::class, 'index']);
