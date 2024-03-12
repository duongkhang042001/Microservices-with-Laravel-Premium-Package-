<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\CompanyMetricMedian;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class GetMetricsMedianForSectorApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_200()
    {
        $company1 = Company::factory(['ticker' => 'TST1', 'sector' => 'Tech'])->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST1',
            'company_id' => $company1->id,
            'debt_to_capital' => 0.2,
        ])->create();

        $response = $this->json('GET', '/api/v1/metrics/medians/sector/Tech');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_return_medians_for_a_sector()
    {
        $company1 = Company::factory(['ticker' => 'TST1', 'sector' => 'Tech'])->create();
        $company2 = Company::factory(['ticker' => 'TST2', 'sector' => 'Tech'])->create();
        $company3 = Company::factory(['ticker' => 'TST3', 'sector' => 'Consumer'])->create();

        CompanyMetricMedian::factory([
            'ticker' => 'TST1',
            'company_id' => $company1->id,
            'debt_to_capital' => 0.2,
        ])->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST2',
            'company_id' => $company2->id,
            'debt_to_capital' => 0.3,
        ])->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST3',
            'company_id' => $company3->id,
            'debt_to_capital' => 0.35,
        ])->create();

        $data = $this->json('GET', '/api/v1/metrics/medians/sector/Tech')->json('data');
        $this->assertEquals('25.00%', $data['debtToCapital']['value']);
        $this->assertEquals('Debt to Capital', $data['debtToCapital']['name']);
    }
}
