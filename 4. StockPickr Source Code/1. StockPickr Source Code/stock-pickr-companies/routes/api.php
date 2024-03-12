<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyLogController;
use App\Http\Controllers\FinancialStatementController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/companies')->group(function () {
    Route::get(
        '/{company}/financial-statements/{type}',
        [FinancialStatementController::class, 'get']
    );

    Route::post('/logs', [CompanyLogController::class, 'store']);
    Route::get('/{company}', [CompanyController::class, 'get']);
});

Route::get('/v1/i-am-alive', function () {
    return response('ok', 200);
});
