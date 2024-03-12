<?php

namespace Tests\Feature\Events\Score;

use App\Events\ScoreCompanies;
use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalculateTotalScoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_calculate_total_scores()
    {
        $this->createCompanyMetricWithFixValue(
            'TST',
            [
                'debt_to_capital' => 0.2,
                'current_ratio' => 2,
            ],
            Company::factory([
                'ticker' => 'TST',
                'total_scores' => null,
                'total_score_percent' => null,
            ])->create()
        );

        event(new ScoreCompanies('abc-123'));
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST',
            'total_scores' => 78    // 26 * 3
        ]);
    }

    /** @test */
    public function it_should_calculate_total_score_percent()
    {
        $this->createCompanyMetricWithFixValue(
            'TST',
            [
                'debt_to_capital' => 0.2,
                'current_ratio' => 2,
            ],
            Company::factory([
                'ticker' => 'TST',
                'total_scores' => null,
                'total_score_percent' => null,
            ])->create()
        );

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST',
            'total_score_percent' => 0.7500,
        ]);
    }

    /** @test */
    public function it_should_not_calculate_null_values()
    {
        $this->createCompanyMetricWithFixValue(
            'TST',
            [
                'payout_ratio' => null,
                'current_ratio' => 2,
            ],
            Company::factory([
                'ticker' => 'TST',
                'total_scores' => null,
                'total_score_percent' => null,
            ])->create()
        );
        $this->createCompanyMetricWithFixValue(
            'TST2',
            [
                'payout_ratio' => 0.2,
                'current_ratio' => 2,
            ],
            Company::factory([
                'ticker' => 'TST2',
                'total_scores' => null,
                'total_score_percent' => null,
            ])->create()
        );

        event(new ScoreCompanies('abc-123'));
        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST',
            'total_scores' => 75,
            // Nem 104 a max elérhető pontszám, hanem 100, mivel payout ratio null
            // így 75 / 100
            'total_score_percent' => 0.75
        ]);
    }
}
