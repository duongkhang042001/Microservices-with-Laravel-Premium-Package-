<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScoreResource;
use App\Repositories\CompanyRepository;
use StockPickr\Common\Services\CacheService;

class ScoreController extends Controller
{
    public function __construct(
        private CacheService $cacheService,
        private CompanyRepository $companies
    ) {}

    public function get(string $ticker)
    {
        return $this->cacheService->getOrRemember(
            "scores-$ticker",
            fn () => new ScoreResource($this->companies->firstOrFail($ticker))
        );
    }
}
