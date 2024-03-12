<?php

namespace App\Http\Controllers;

use App\Models\Company\Company;
use App\Models\FinancialStatement\Factory\FinancialStatementFactory;
use StockPickr\Common\Services\CacheService;
use App\Services\Company\CompanyService;
use App\Services\FinancialStatement\FinancialStatementService;

class FinancialStatementController extends Controller
{
    public function __construct(
        private FinancialStatementService $financialStatementService,
        private FinancialStatementFactory $statementFactory,
        private CacheService $cacheService,
        private CompanyService $companyService
    ) {}

    public function get(Company $company, string $type)
    {
        return $this->cacheService->getOrRemember(
            "$company->ticker-financial-statement-$type",
            function () use ($company, $type) {
                $summary = $this->financialStatementService->getSummary($company, $this->statementFactory->create($type));
                return response()->json([
                    'data' => $summary
                ]);
            }
        );
    }
}
