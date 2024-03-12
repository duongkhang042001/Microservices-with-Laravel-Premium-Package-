<?php

namespace Tests\Feature\Transaction;

use App\Events\CompanyUpserted;
use App\Models\CompanyMetric;
use App\Services\MetricMedianService;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class UpdateMetricsTransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_rollback_if_error_happens()
    {
        try {
            CompanyMetric::factory()
                ->state([
                    'ticker' => 'TST',
                    'year' => 2019,
                    'debt_to_capital' => 0.5
                ])
                ->create();

            $this->mock(MetricMedianService::class, function (MockInterface $mock) {
                $mock->shouldReceive('upsert')
                    ->andThrow(new Exception('Something went wrong'));
            });

            $company = $this->createCompanyUpsertedContainer([
                'incomeStatements' => [
                    'totalRevenue' => [
                        2020 => 1000,
                        2019 => 1000,
                    ]
                ]
            ]);

            event(new CompanyUpserted($company, false));
        } catch (Exception) {
            $this->assertDatabaseCount('company_metrics', 1);
        }
    }
}
