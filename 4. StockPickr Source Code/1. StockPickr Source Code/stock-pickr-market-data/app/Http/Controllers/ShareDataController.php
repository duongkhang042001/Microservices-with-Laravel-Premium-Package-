<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShareDataResource;
use App\Repositories\ShareDataRepository;
use StockPickr\Common\Services\CacheService;

class ShareDataController extends Controller
{
    public function __construct(
        private CacheService $cacheService,
        private ShareDataRepository $shareData
    ) {}

    public function get(string $ticker): ShareDataResource
    {
        return $this->cacheService->getOrRemember(
            "share-data-$ticker",
            fn () => new ShareDataResource(
                $this->shareData->getByTickerOrFail($ticker)
            ),
            CacheService::FOR_AN_HOUR
        );

    }
}
