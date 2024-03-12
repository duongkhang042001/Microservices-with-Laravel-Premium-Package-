<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyMetric;
use App\Models\CompanyMetricMedian;
use App\Models\CompanyScore;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::factory()
            ->count(1000)
            ->create();

        foreach ($companies as $company) {
            foreach (range(2017, 2020) as $year) {
                CompanyMetric::factory([
                    'ticker' => $company->ticker,
                    'company_id' => $company->id,
                    'year' => $year
                ])->create();
            }

            CompanyScore::factory([
                'ticker' => $company->ticker,
                'company_id' => $company->id
            ])->create();

            CompanyMetricMedian::factory([
                'ticker' => $company->ticker,
                'company_id' => $company->id
            ])->create();
        }
    }
}
