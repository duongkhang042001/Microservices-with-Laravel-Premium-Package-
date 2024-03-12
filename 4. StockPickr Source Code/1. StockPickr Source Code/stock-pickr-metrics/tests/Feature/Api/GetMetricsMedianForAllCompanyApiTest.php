<?php

namespace Tests\Feature\Api;

use App\Models\CompanyMetricMedian;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class GetMetricsMedianForAllCompanyApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_200()
    {
        CompanyMetricMedian::factory([
            'debt_to_capital' => 0.2,
        ])->create();

        $response = $this->json('GET', '/api/v1/metrics/medians');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_return_medians_for_all_company()
    {
        CompanyMetricMedian::factory([
            'debt_to_capital' => 0.2,
        ])->create();
        CompanyMetricMedian::factory([
            'debt_to_capital' => 0.5,
        ])->create();
        CompanyMetricMedian::factory([
            'debt_to_capital' => 0.35,
        ])->create();

        $data = $this->json('GET', '/api/v1/metrics/medians')->json('data');
        $this->assertEquals('35.00%', $data['debtToCapital']['value']);
        $this->assertEquals('Debt to Capital', $data['debtToCapital']['name']);
    }

    /** @test */
    public function it_should_return_medians_formatted_as_numbers()
    {
        CompanyMetricMedian::factory([
            'current_ratio' => 1.5,
            'quick_ratio' => 1,
            'long_term_debt_to_ebitda' => 3.1,
            'interest_coverage_ratio' => 15.72,
        ])->create();

        $data = $this->json('GET', '/api/v1/metrics/medians')->json('data');
        $this->assertEquals('1.50', $data['currentRatio']['value']);
        $this->assertEquals('1.00', $data['quickRatio']['value']);
        $this->assertEquals('3.10', $data['longTermDebtToEbitda']['value']);
        $this->assertEquals('15.72', $data['interestCoverageRatio']['value']);
    }
}
