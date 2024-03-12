<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Repositories\Company\CompanyRepository;
use StockPickr\Common\Services\CacheService;

class CompanyController extends Controller
{
    public function __construct(
        private CacheService $cacheService,
        private CompanyRepository $companies
    ) {}

    public function get(string $ticker)
    {
        return $this->cacheService->getOrRemember(
            "company-$ticker",
            function () use ($ticker) {
                return new CompanyResource($this->companies->firstOrFail($ticker));
            }
        );
    }
}
