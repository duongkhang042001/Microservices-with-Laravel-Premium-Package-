<?php

namespace App\Http\Controllers;

use App\Http\Resources\MetricsResource;
use App\Services\MetricMedianService;
use StockPickr\Common\Services\CacheService;
use Str;

class MetricMedianSectorController extends Controller
{
    public function __construct(
        private MetricMedianService $metricMedianService,
        private CacheService $cacheService
    ) {}

    public function get(string $sector)
    {
        $key = Str::snake($sector, '-');
        return $this->cacheService->getOrRemember(
            "metric-medians-$key",
            fn () => new MetricsResource(
                $this->metricMedianService->getMediansForSector($sector)
            )
        );
    }
}
