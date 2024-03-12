<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Services\CompanyService;
use StockPickr\Common\Services\CacheService;

class SearchCompanyController extends Controller
{
    public function __construct(
        private CompanyService $companyService,
        private CacheService $cacheService
    ) {}

    public function index(SearchCompanyRequest $request)
    {
        $searchTerm = $request->getSearchTerm();
        return $this->cacheService->getOrRemember(
            "company-search-$searchTerm",
            function () use ($searchTerm) {
                return [
                    'data' => CompanyResource::collection(
                        $this->companyService->search($searchTerm)
                    )
                ];
            }
        );
    }
}
