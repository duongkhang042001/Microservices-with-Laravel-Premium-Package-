<?php

use App\Http\Controllers\AnalystController;
use App\Http\Controllers\AvailableTickerController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ShareDataController;
use Illuminate\Support\Facades\Route;

Route::get('/v1/company-provider/companies/{ticker}', [CompanyController::class, 'get']);
Route::get('/v1/company-provider/companies/{ticker}/share-data', [ShareDataController::class, 'get']);
Route::get('/v1/company-provider/companies/{ticker}/analyst', [AnalystController::class, 'get']);
Route::get('/v1/company-provider/available-tickers', [AvailableTickerController::class, 'index']);

Route::get('/v1/i-am-alive', function () {
    return response('ok', 200);
});
