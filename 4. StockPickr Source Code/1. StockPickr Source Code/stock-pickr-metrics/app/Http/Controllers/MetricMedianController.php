<?php

namespace App\Http\Controllers;

use App\Http\Resources\MetricsResource;
use App\Repositories\CompanyRepository;
use App\Services\MetricMedianService;
use App\Services\MetricService;
use StockPickr\Common\Services\CacheService;

class MetricMedianController extends Controller
{
    public function __construct(
        private MetricService $metricService,
        private MetricMedianService $metricMedianService,
        private CacheService $cacheService,
        private CompanyRepository $companies
    ) {}

    public function index()
    {
        return $this->cacheService->getOrRemember(
            "metrics",
            fn () => new MetricsResource(
                $this->metricMedianService->getMediansForAllCompany(),
            )
        );
    }

    public function get(string $ticker)
    {
        return $this->cacheService->getOrRemember(
            "metric-median-$ticker",
            fn () => new MetricsResource(
                $this->metricMedianService->getMediansForCompany(
                    $this->companies->firstOrFail($ticker)
                )
            )
        );
    }
}
