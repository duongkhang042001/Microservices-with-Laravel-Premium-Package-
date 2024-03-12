<?php

use App\Http\Controllers\AnalystController;
use App\Http\Controllers\ShareDataController;
use Illuminate\Support\Facades\Route;

Route::get('/v1/share-data/{ticker}', [ShareDataController::class, 'get']);
Route::get('/v1/analyst/{ticker}', [AnalystController::class, 'get']);

Route::get('/v1/i-am-alive', function () {
    return response('ok', 200);
});
