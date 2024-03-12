<?php

namespace Tests\Feature\CreateMarketData;

use App\Repositories\CompanyScheduleRepository;
use App\Services\CompanyProviderService;
use App\Services\RedisService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use StockPickr\Common\Containers\MarketData\MarketDataContainer;

class CreateMarketDataTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_company_schedule_and_publish_a_create_market_data_event()
    {
        $this->mockCompanyProvider('TST');
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishCreateMarketData');
            $mock->shouldReceive('publishCreateCompany');
        });

        $this->artisan('company:create');

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'TST',
            'event'     => CompanyScheduleRepository::EVENT_CREATE_MARKET_DATA,
            'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
        ]);
    }

    private function mockCompanyProvider(string $ticker)
    {
        $this->mock(CompanyProviderService::class, function (MockInterface $mock) use ($ticker) {
            $mock->shouldReceive('getNextCompanyForCreate')
                ->andReturn(
                    [
                        'alternativeTicker' => $ticker,
                        'company' => $this->createUpsertCompanyContainer([
                            'ticker' => $ticker
                        ])
                    ]
                );

            $mock->shouldReceive('getMarketData')
                ->andReturn(MarketDataContainer::from([
                    'shareData' => [
                        'price'     => 100,
                        'marketCap' => 1000
                    ],
                    'analyst' => [
                        'priceTarget' => [
                            'low'       => 1,
                            'average'   => 2,
                            'high'      => 3
                        ],
                        'rating' => [
                            'buy'  => 1,
                            'hold' => 2,
                            'sell' => 3
                        ]
                    ]
                ]));
        });
    }
}
