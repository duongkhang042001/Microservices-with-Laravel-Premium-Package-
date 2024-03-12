<?php

namespace Tests\Feature\Events\Score;

use App\Events\ScoreCompanies;
use App\Models\Company;
use App\Models\CompanyMetric;
use App\Models\CompanyMetricMedian;
use App\Services\RedisService;
use App\Services\ScoreService;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class ScoreFailedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_rollback_database()
    {
        try {
            $company = Company::factory([
                'ticker' => 'TST',
                'total_scores' => null,
                'total_score_percent' => null,
                'position' => null
            ])->create();
            CompanyMetric::factory([
                'ticker' => 'TST',
                'company_id' => $company->id
            ])->create();
            CompanyMetricMedian::factory([
                'ticker' => 'TST1',
                'company_id' => $company,
            ])->create();

            // A score lefut, utána történik hiba
            $this->mock(RedisService::class, function (MockInterface $mock) {
                $mock->shouldReceive('publishScoreSucceeded')
                    ->andThrow(new Exception('Something went wrong'));
            });

            event(new ScoreCompanies('abc-123'));
            $this->assertTrue(false);
        } catch (Exception) {
            $this->assertDatabaseCount('company_scores', 0);
            $this->assertDatabaseHas('companies', [
                'ticker' => 'TST',
                'total_scores' => null,
                'total_score_percent' => null,
                'position' => null
            ]);
        }
    }

    /** @test */
    public function it_should_publish_score_failed_event()
    {
        try {
            $company = Company::factory([
                'ticker' => 'TST',
                'total_scores' => null,
                'total_score_percent' => null,
                'position' => null
            ])->create();
            CompanyMetric::factory([
                'ticker' => 'TST',
                'company_id' => $company->id
            ])->create();
            CompanyMetricMedian::factory([
                'ticker' => 'TST',
                'company_id' => $company->id
            ])->create();

            $this->mock(ScoreService::class, function (MockInterface $mock) {
                $mock->shouldReceive('scoreCompanies')
                    ->andThrow(new Exception('Something went wrong'));
            });
            $this->mock(RedisService::class, function (MockInterface $mock) {
                $mock->shouldReceive('publishScoreFailed');
                $mock->shouldNotReceive('publishScoreSucceeded');
            });

            event(new ScoreCompanies('abc-123'));
            $this->assertTrue(false);
        } catch (Exception) {}
    }
}
