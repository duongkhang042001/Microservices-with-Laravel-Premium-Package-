<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\CompanyMetricMedian;
use Illuminate\Support\Collection;

final class CompanyMetricMedianRepository
{
    public function getFirstOrNew(Company $company): CompanyMetricMedian
    {
        return CompanyMetricMedian::firstOrNew([
            'company_id' => $company->id,
            'ticker' => $company->ticker
        ]);
    }

    /**
     * @return Collection<CompanyMetricMedian>
     */
    public function getAll(): Collection
    {
        return CompanyMetricMedian::all();
    }

    public function getByCompany(Company $company): CompanyMetricMedian
    {
        return CompanyMetricMedian::where('company_id', $company->id)
            ->firstOrFail();
    }

    /**
     * @return Collection<CompanyMetricMedian>
     */
    public function getBySector(string $sector): Collection
    {
        return CompanyMetricMedian::select('company_metric_medians.*')
            ->join('companies', 'companies.id', '=', 'company_metric_medians.company_id')
            ->where('companies.sector', $sector)
            ->get();
    }
}
