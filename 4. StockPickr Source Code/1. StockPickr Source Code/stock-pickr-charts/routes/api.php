<?php

use App\Http\Controllers\ChartGroupController;
use Illuminate\Support\Facades\Route;

Route::get('/v1/charts/{ticker}/{group}', [ChartGroupController::class, 'get']);

Route::get('/v1/i-am-alive', function () {
    return response('ok', 200);
});
