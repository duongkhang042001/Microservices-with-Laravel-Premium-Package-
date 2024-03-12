<?php

namespace App\Http\Controllers;

use App\Services\CompanyService;
use StockPickr\Common\Services\CacheService;

class CompanyCountController extends Controller
{
    public function __construct(
        private CompanyService $companyService,
        private CacheService $cacheService
    ) {}

    public function get()
    {
        return $this->cacheService->getOrRemember(
            'companies-count',
            fn () => [
                'data' => $this->companyService->count()
            ]
        );
    }
}
