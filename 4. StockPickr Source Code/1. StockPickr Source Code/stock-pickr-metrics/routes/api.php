<?php

use App\Http\Controllers\MetricController;
use App\Http\Controllers\MetricMedianController;
use App\Http\Controllers\MetricMedianSectorController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\SectorScoreController;
use Illuminate\Support\Facades\Route;

Route::get('/v1/metrics/medians', [MetricMedianController::class, 'index']);
Route::get('/v1/metrics/medians/{company}', [MetricMedianController::class, 'get']);
Route::get('/v1/metrics/medians/sector/{sector}', [MetricMedianSectorController::class, 'get']);
Route::get('/v1/metrics/{company}', [MetricController::class, 'get']);

Route::get('/v1/scores/{company}/sector', [SectorScoreController::class, 'get']);
Route::get('/v1/scores/{company}', [ScoreController::class, 'get']);

Route::get('/v1/i-am-alive', function () {
    return response('ok', 200);
});

