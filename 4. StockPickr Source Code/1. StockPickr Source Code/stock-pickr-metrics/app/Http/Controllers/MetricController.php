<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\MetricService;
use Illuminate\Http\JsonResponse;
use StockPickr\Common\Services\CacheService;

class MetricController extends Controller
{
    public function __construct(
        private MetricService $metricService,
        private CacheService $cacheService
    ) {}

    public function get(Company $company): JsonResponse
    {
        return $this->cacheService->getOrRemember(
            "metrics-$company->ticker",
            fn () =>
                response()->json([
                    'data' => $this->metricService->getAllForCompany($company->ticker)
                ]
            )
        );
    }
}
