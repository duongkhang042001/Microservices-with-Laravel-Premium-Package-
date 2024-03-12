<?php

namespace App\Repositories;

use App\Models\Company;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\Metrics\MetricsContainer;
use StockPickr\Common\Containers\Metrics\MetricsUpsertedContainer;

final class CompanyRepository
{
    public function upsert(CompanyUpsertedContainer $companyContainer): Company
    {
        $company = Company::firstOrNew([
            'ticker' => $companyContainer->ticker
        ]);

        $company->financial_statements = $companyContainer->financialStatements->toArray();
        $company->save();

        return $company;
    }

    public function updateMetrics(
        Company $company,
        MetricsContainer $metricsContainer
    ): void {
        $company->metrics = $metricsContainer->toArray();
        $company->save();
    }

    public function getByTicker(string $ticker): Company
    {
        return Company::where('ticker', $ticker)->firstOrFail();
    }

    public function delete(string $ticker): void
    {
        $this->getByTicker($ticker)->delete();
    }
}
