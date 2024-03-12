<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\CompanyMetric;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class GetMetricsApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_200()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetric::factory(['ticker' => 'TST', 'company_id' => $company->id])->create();

        $response = $this->json('GET', '/api/v1/metrics/TST');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_return_404_if_company_not_found()
    {
        $response = $this->json('GET', '/api/v1/metrics/TST');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_should_return_metrics_as_percent()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetric::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2020,
            'payout_ratio' => 0.34
        ])->create();

        CompanyMetric::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2019,
            'payout_ratio' => 0.3941
        ])->create();

        CompanyMetric::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2018,
            'payout_ratio' => 0.37
        ])->create();

        $data = $this->json('GET', '/api/v1/metrics/TST')->json('data');

        $this->assertEquals('0.34', $data['payoutRatio']['data']['2020']['rawValue']);
        $this->assertEquals('34.00%', $data['payoutRatio']['data']['2020']['formattedValue']);

        $this->assertEquals('0.3941', $data['payoutRatio']['data']['2019']['rawValue']);
        $this->assertEquals('39.41%', $data['payoutRatio']['data']['2019']['formattedValue']);

        $this->assertEquals('0.37', $data['payoutRatio']['data']['2018']['rawValue']);
        $this->assertEquals('37.00%', $data['payoutRatio']['data']['2018']['formattedValue']);
    }

    /** @test */
    public function it_should_return_metrics_as_number()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetric::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2020,
            'current_ratio' => 2.145768
        ])->create();

        CompanyMetric::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2019,
            'current_ratio' => 1.75875754
        ])->create();

        CompanyMetric::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2018,
            'current_ratio' => 1
        ])->create();

        $data = $this->json('GET', '/api/v1/metrics/TST')->json('data');

        $this->assertEquals('2.1458', $data['currentRatio']['data']['2020']['rawValue']);
        $this->assertEquals('2.15', $data['currentRatio']['data']['2020']['formattedValue']);

        $this->assertEquals('1.7588', $data['currentRatio']['data']['2019']['rawValue']);
        $this->assertEquals('1.76', $data['currentRatio']['data']['2019']['formattedValue']);

        $this->assertEquals('1.0000', $data['currentRatio']['data']['2018']['rawValue']);
        $this->assertEquals('1.00', $data['currentRatio']['data']['2018']['formattedValue']);
    }

    /** @test */
    public function it_should_return_metrics_with_negative_class()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetric::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2020,
            'net_margin' => 0.05
        ])->create();

        CompanyMetric::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2019,
            'net_margin' => -0.11
        ])->create();

        CompanyMetric::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2018,
            'net_margin' => -0.21
        ])->create();

        $data = $this->json('GET', '/api/v1/metrics/TST')->json('data');

        $this->assertEquals([], $data['netMargin']['data']['2020']['classes']);
        $this->assertEquals(['negative'], $data['netMargin']['data']['2019']['classes']);
        $this->assertEquals(['negative'], $data['netMargin']['data']['2018']['classes']);
    }
}
