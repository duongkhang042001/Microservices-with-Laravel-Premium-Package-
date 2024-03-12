<?php

namespace Tests\Feature\Events;

use App\Events\CompanyUpserted;
use App\Services\RedisService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class PublishMetricsCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_publish_metrics_created_event()
    {
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishMetricsCreatedEvent');
            $mock->shouldNotReceive('publishMetricsUpdatedEvent');
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
    }
}
