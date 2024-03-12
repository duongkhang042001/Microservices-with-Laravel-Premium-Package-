<?php

namespace Tests\Feature\Api;

use App\Enums\Scores;
use App\Models\Company;
use App\Models\CompanySectorScore;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class GetSectorScoresApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_200()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanySectorScore::factory([
            'company_id' => $company->id,
            'ticker' => $company->ticker,
        ])->create();

        $response = $this->json('GET', '/api/v1/scores/TST/sector');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_return_404_if_company_not_found()
    {
        $response = $this->json('GET', '/api/v1/scores/TST/sector');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_should_return_sector_scores()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanySectorScore::factory([
            'company_id' => $company->id,
            'ticker' => $company->ticker,
            'debt_to_capital' => Scores::A,
            'eps_growth' => Scores::B,
            'current_ratio' => Scores::C,
            'payout_ratio' => Scores::D,
        ])->create();

        $data = $this->json('GET', '/api/v1/scores/TST/sector')->json('data');

        $this->assertEquals('4', $data['debtToCapital']);
        $this->assertEquals('3', $data['epsGrowth']);
        $this->assertEquals('2', $data['currentRatio']);
        $this->assertEquals('1', $data['payoutRatio']);
    }

    /** @test */
    public function it_should_return_empty_string_if_score_not_available()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanySectorScore::factory([
            'company_id' => $company->id,
            'ticker' => $company->ticker,
            'debt_to_capital' => Scores::NOT_AVAILABLE,
        ])->create();

        $data = $this->json('GET', '/api/v1/scores/TST/sector')->json('data');
        $this->assertEquals('', $data['debtToCapital']);
    }

    /** @test */
    public function it_should_return_a_summary()
    {
        $company = Company::factory([
            'ticker' => 'TST',
            'total_sector_scores' => 78,
            'total_sector_score_percent' => 0.7500
        ])->create();
        CompanySectorScore::factory([
            'company_id' => $company->id,
            'ticker' => $company->ticker,
            'debt_to_capital' => 3,
            'current_ratio' => 3,
            'quick_ratio' => 3,
            'cash_to_debt' => 3,
            'interest_to_operating_profit' => 3,
            'long_term_debt_to_ebitda' => 3,
            'interest_coverage_ratio' => 3,
            'debt_to_assets' => 3,
            'operating_cash_flow_to_current_liabilities' => 3,
            'capex_as_percent_of_revenue' => 3,
            'capex_as_percent_of_operating_cash_flow' => 3,
            'payout_ratio' => 3,
            'roic' => 3,
            'croic' => 3,
            'rota' => 3,
            'roa' => 3,
            'roe' => 3,
            'free_cash_flow_to_revenue' => 3,
            'net_margin' => 3,
            'operating_margin' => 3,
            'gross_margin' => 3,
            'operating_cash_flow_margin' => 3,
            'sga_to_gross_profit' => 3,
            'eps_growth' => 3,
            'net_income_growth' => 3,
            'total_revenue_growth' => 3,
        ])->create();

        $data = $this->json('GET', '/api/v1/scores/TST/sector')->json('data');
        $this->assertEquals([
            'totalScores' => 78,
            'totalScorePercent' => '75%',
            'maxPossibleScores' => 104,
        ], $data['summary']);
    }
}
