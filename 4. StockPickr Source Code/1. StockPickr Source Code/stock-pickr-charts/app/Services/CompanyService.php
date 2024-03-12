<?php

namespace App\Services;

use App\Events\CreateCharts;
use App\Models\Company;
use App\Repositories\CompanyRepository;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\Metrics\MetricsUpsertedContainer;

final class CompanyService
{
    public function __construct(private CompanyRepository $companies)
    {
    }

    public function upsert(CompanyUpsertedContainer $companyContainer): Company
    {
        return $this->companies->upsert(
            $this->convertFinancialStatements($companyContainer)
        );
    }

    public function updateMetrics(MetricsUpsertedContainer $metricsContainer): void
    {
        $company = $this->companies->getByTicker($metricsContainer->ticker);
        $this->companies->updateMetrics($company, $metricsContainer->metrics);

        event(new CreateCharts($company));
    }

    private function convertFinancialStatements(
        CompanyUpsertedContainer $companyContainer
    ) {
        $convertedCompany = $companyContainer->toArray();
        foreach ($companyContainer->getCashFlows()->capex->data as $year => $value) {
            $arr = $value->toArray();
            $arr['rawValue'] = $value->rawValue * -1;

            $convertedCompany['financialStatements']['cashFlows']['capex']['data'][$year] = $arr;
        }

        return CompanyUpsertedContainer::from($convertedCompany);
    }
}
