<?php

namespace Tests\Feature\Events\Score;

use App\Enums\Scores;
use App\Events\ScoreCompanies;
use App\Models\Company;
use App\Models\CompanyMetric;
use App\Models\CompanyMetricMedian;
use App\Models\CompanyScore;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoreCompaniesBySectorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_score_existing_companies_by_sector()
    {
        $company1 = Company::factory(['ticker' => 'TST1', 'sector' => 'Tech'])->create();
        $company2 = Company::factory(['ticker' => 'TST2', 'sector' => 'Tech'])->create();
        $company3 = Company::factory(['ticker' => 'TST3', 'sector' => 'Not Tech'])->create();
        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST1',
                'company_id' => $company1->id,
                'debt_to_capital' => 0.2,
                'current_ratio' => 2,
            ])
            ->create();

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST1',
                'company_id' => $company1->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST2',
                'company_id' => $company2->id,
                'debt_to_capital' => 0.8,
                'current_ratio' => 0.5,
            ])
            ->create();

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST2',
                'company_id' => $company2->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST3',
                'company_id' => $company3->id,
                'debt_to_capital' => 0.1,
                'current_ratio' => 10,
            ])
            ->create();

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST3',
                'company_id' => $company3->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

        CompanyMetricMedian::factory([
            'ticker' => 'TST1',
            'company_id' => $company1,
            'debt_to_capital' => 0.2,
            'current_ratio' => 2,
        ])->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST2',
            'company_id' => $company2,
            'debt_to_capital' => 0.8,
            'current_ratio' => 0.5,
        ])->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST3',
            'company_id' => $company3,
            'debt_to_capital' => 0.1,
            'current_ratio' => 10,
        ])->create();

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_sector_scores', [
            'ticker' => 'TST1',
            'debt_to_capital' => Scores::A,
            'current_ratio' => Scores::A,
        ]);
        $this->assertDatabaseHas('company_sector_scores', [
            'ticker' => 'TST2',
            'debt_to_capital' => Scores::D,
            'current_ratio' => Scores::D,
        ]);
        $this->assertDatabaseHas('company_sector_scores', [
            'ticker' => 'TST3',
            'debt_to_capital' => Scores::B,
            'current_ratio' => Scores::B,
        ]);
    }

    /** @test */
    public function it_should_score_new_companies_by_sector()
    {
        $company1 = Company::factory(['ticker' => 'TST1', 'sector' => 'Tech'])->create();
        $company2 = Company::factory(['ticker' => 'TST2', 'sector' => 'Tech'])->create();
        $company3 = Company::factory(['ticker' => 'TST3', 'sector' => 'Tech'])->create();

        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST1',
                'company_id' => $company1->id,
                'debt_to_capital' => 0.5,
                'current_ratio' => 1,
            ])
            ->create();

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST1',
                'company_id' => $company1->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST2',
                'company_id' => $company2->id,
                'debt_to_capital' => 0.8,
                'current_ratio' => 0.5,
            ])
            ->create();

        CompanyScore::factory()
            ->state([
                'ticker' => 'TST2',
                'company_id' => $company2->id,
                'debt_to_capital' => Scores::A,
                'current_ratio' => Scores::A,
            ])
            ->create();

        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST3',
                'company_id' => $company3->id,
                'debt_to_capital' => 0,
                'current_ratio' => 5,
            ])
            ->create();

        CompanyMetricMedian::factory([
            'ticker' => 'TST1',
            'company_id' => $company1,
            'debt_to_capital' => 0.5,
            'current_ratio' => 1,
        ])->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST2',
            'company_id' => $company2,
            'debt_to_capital' => 0.8,
            'current_ratio' => 0.5,
        ])->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST3',
            'company_id' => $company3,
            'debt_to_capital' => 0,
            'current_ratio' => 5,
        ])->create();

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_sector_scores', [
            'ticker' => 'TST1',
            'debt_to_capital' => Scores::B,
            'current_ratio' => Scores::B,
        ]);
        $this->assertDatabaseHas('company_sector_scores', [
            'ticker' => 'TST2',
            'debt_to_capital' => Scores::D,
            'current_ratio' => Scores::D,
        ]);
        $this->assertDatabaseHas('company_sector_scores', [
            'ticker' => 'TST3',
            'debt_to_capital' => Scores::A,
            'current_ratio' => Scores::A,
        ]);
    }
}
