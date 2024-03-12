<?php

use App\Http\Controllers\CompanyCountController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\SearchCompanyController;
use Illuminate\Support\Facades\Route;

Route::get('/v1/queries/leaderboard', [LeaderboardController::class, 'get']);
Route::get('/v1/queries/search', [SearchCompanyController::class, 'index']);
Route::get('/v1/queries/count', [CompanyCountController::class, 'get']);
