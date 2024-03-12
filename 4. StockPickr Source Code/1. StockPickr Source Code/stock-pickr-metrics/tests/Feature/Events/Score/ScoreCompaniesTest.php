<?php

namespace Tests\Feature\Events\Score;

use App\Enums\Scores;
use App\Events\ScoreCompanies;
use App\Models\Company;
use App\Models\CompanyMetric;
use App\Models\CompanyMetricMedian;
use App\Models\CompanyScore;
use App\Services\RedisService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery\MockInterface;

class ScoreCompaniesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_score_existing_companies()
    {
        $company1 = Company::factory(['ticker' => 'TST1'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();
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

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST1',
            'debt_to_capital' => Scores::A,
            'current_ratio' => Scores::A,
        ]);
        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST2',
            'debt_to_capital' => Scores::D,
            'current_ratio' => Scores::D,
        ]);
    }

    /** @test */
    public function it_should_score_new_companies()
    {
        $company1 = Company::factory(['ticker' => 'TST1'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();
        $company3 = Company::factory(['ticker' => 'TST3'])->create();

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

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST1',
            'debt_to_capital' => Scores::B,
            'current_ratio' => Scores::B,
        ]);
        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST2',
            'debt_to_capital' => Scores::D,
            'current_ratio' => Scores::D,
        ]);
        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST3',
            'debt_to_capital' => Scores::A,
            'current_ratio' => Scores::A,
        ]);
    }

    /** @test */
    public function it_should_score_as_null_if_metrics_null()
    {
        $company1 = Company::factory(['ticker' => 'TST1'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST1',
                'company_id' => $company1->id,
                'payout_ratio' => null,
            ])
            ->create();
        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST2',
                'company_id' => $company2->id,
                'payout_ratio' => 0.33,
            ])
            ->create();

        CompanyMetricMedian::factory([
            'ticker' => 'TST1',
            'company_id' => $company1,
            'payout_ratio' => null,
        ])->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST2',
            'company_id' => $company2,
            'payout_ratio' => 0.33,
        ])->create();

        event(new ScoreCompanies('abc-123'));

        $this->assertDatabaseHas('company_scores', [
            'ticker' => 'TST1',
            'payout_ratio' => null,
        ]);
    }

    /** @test */
    public function it_should_publish_score_succeeded_event()
    {
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishScoreSucceeded');
            $mock->shouldReceive('publishCompanyScored');
        });

        $company1 = Company::factory(['ticker' => 'TST1'])->create();
        $company2 = Company::factory(['ticker' => 'TST2'])->create();

        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST1',
                'company_id' => $company1->id,
                'debt_to_capital' => 0.4,
            ])
            ->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST1',
            'company_id' => $company1,
            'payout_ratio' => 0.4,
        ])->create();
        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST2',
                'company_id' => $company2->id,
                'debt_to_capital' => 0.4,
            ])
            ->create();
        CompanyMetricMedian::factory([
            'ticker' => 'TST2',
            'company_id' => $company2,
            'payout_ratio' => 0.4,
        ])->create();

        event(new ScoreCompanies('abc-123'));
    }
}
