<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\CompanyMetric;
use Illuminate\Support\Collection;

final class CompanyMetricRepository
{
    /**
     * @param array<string, ?float> $metrics 'debt_to_capital' => 0.45
     */
    public function create(
        Company $company,
        int $year,
        array $metrics
    ): CompanyMetric {
        $companyMetric = new CompanyMetric();
        $companyMetric->company_id = $company->id;
        $companyMetric->ticker = $company->ticker;
        $companyMetric->year = $year;

        foreach ($metrics as $slug => $value) {
            $companyMetric->{$slug} = $value;
        }

        $companyMetric->save();
        return $companyMetric;
    }

    /**
     * @return Collection<CompanyMetric>
     */
    public function getAllForCompany(string $ticker): Collection
    {
        return CompanyMetric::where('ticker', $ticker)
            ->get();
    }

    public function deleteAllForCompany(string $ticker): void
    {
        CompanyMetric::where('ticker', $ticker)->delete();
    }
}
