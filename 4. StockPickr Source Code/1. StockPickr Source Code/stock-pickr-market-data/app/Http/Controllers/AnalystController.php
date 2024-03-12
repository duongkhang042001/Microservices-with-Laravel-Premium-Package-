<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnalystResource;
use App\Repositories\AnalystRepository;
use StockPickr\Common\Services\CacheService;

class AnalystController extends Controller
{
    public function __construct(
        private CacheService $cacheService,
        private AnalystRepository $analysts
    ) {}

    public function get(string $ticker): AnalystResource
    {
        return $this->cacheService->getOrRemember(
            "analyst-$ticker",
            fn () => new AnalystResource(
                $this->analysts->getByTickerOrFail($ticker)
            ),
            CacheService::FOR_AN_HOUR
        );
    }
}
