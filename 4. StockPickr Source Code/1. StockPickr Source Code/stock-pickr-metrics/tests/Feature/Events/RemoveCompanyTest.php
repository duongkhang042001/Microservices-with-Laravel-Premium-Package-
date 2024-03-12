<?php

namespace Tests\Feature\Events;

use App\Events\RemoveCompany;
use App\Models\Company;
use App\Models\CompanyMetric;
use App\Models\CompanyMetricMedian;
use App\Models\CompanyScore;
use App\Models\CompanySectorScore;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemoveCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_remove_a_company()
    {
        Company::factory(['ticker' => 'TST'])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('companies', 0);
    }

    /** @test */
    public function it_should_remove_a_company_with_metrics()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetric::factory(['ticker' => 'TST', 'company_id' => $company->id])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('company_metrics', 0);
    }

    /** @test */
    public function it_should_remove_a_company_with_score()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyScore::factory(['ticker' => 'TST', 'company_id' => $company->id])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('company_scores', 0);
    }

    /** @test */
    public function it_should_remove_a_company_with_sector_score()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanySectorScore::factory(['ticker' => 'TST', 'company_id' => $company->id])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('company_sector_scores', 0);
    }

    /** @test */
    public function it_should_remove_a_company_with_metric_median()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetricMedian::factory(['ticker' => 'TST', 'company_id' => $company->id])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('company_metric_medians', 0);
    }
}
