<?php

namespace Tests\Feature\Transaction;

use App\Events\CompanyUpserted;
use App\Services\MetricMedianService;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class StoreMetricsTransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_rollback_if_error_happens()
    {
        try {
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
            $this->assertDatabaseCount('company_metrics', 0);
        }
    }
}
