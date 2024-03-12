<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\CompanyMetric;
use App\Models\CompanyMetricMedian;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class GetMetricsMedianApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_200()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetricMedian::factory([
            'company_id' => $company->id,
            'debt_to_capital' => 0.2,
        ])->create();

        $response = $this->json('GET', '/api/v1/metrics/medians/TST');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_return_404_if_company_not_found()
    {
        $response = $this->json('GET', '/api/v1/metrics/medians/TST');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_should_return_medians_for_a_company()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'debt_to_capital' => 0.2,
        ])->create();

        $data = $this->json('GET', '/api/v1/metrics/medians/TST')->json('data');
        $this->assertEquals('20.00%', $data['debtToCapital']['value']);
        $this->assertEquals('Debt to Capital', $data['debtToCapital']['name']);
    }

    /** @test */
    public function it_should_return_medians_formatted_as_numbers()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetricMedian::factory([
            'company_id' => $company->id,
            'current_ratio' => 1.5,
            'quick_ratio' => 1,
            'long_term_debt_to_ebitda' => 3.1,
            'interest_coverage_ratio' => 15.72,
        ])->create();

        $data = $this->json('GET', '/api/v1/metrics/medians/TST')->json('data');
        $this->assertEquals('1.50', $data['currentRatio']['value']);
        $this->assertEquals('1.00', $data['quickRatio']['value']);
        $this->assertEquals('3.10', $data['longTermDebtToEbitda']['value']);
        $this->assertEquals('15.72', $data['interestCoverageRatio']['value']);
    }

    /** @test */
    public function it_should_return_metric_names()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetricMedian::factory([
            'company_id' => $company->id,
            'current_ratio' => 1.5,
            'eps_growth' => 1,
            'long_term_debt_to_ebitda' => 3.1,
            'interest_coverage_ratio' => 15.72,
        ])->create();

        $data = $this->json('GET', '/api/v1/metrics/medians/TST')->json('data');
        $this->assertEquals('Current Ratio', $data['currentRatio']['name']);
        $this->assertEquals('EPS growth', $data['epsGrowth']['name']);
        $this->assertEquals('Long term debt to EBITDA', $data['longTermDebtToEbitda']['name']);
        $this->assertEquals('Interest coverage ratio', $data['interestCoverageRatio']['name']);
    }
}
