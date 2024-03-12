<?php

namespace Tests\Feature\Create;

use App\Repositories\CompanyScheduleRepository;
use App\Services\CompanyProviderService;
use App\Services\RedisService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use StockPickr\Common\Containers\MarketData\MarketDataContainer;
use StockPickr\Common\Containers\UpsertCompanyContainer as CompanyContainer;

class CreateCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_company_schedule()
    {
        $this->mockCompanyProvider('TST');
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishCreateCompany');
            $mock->shouldReceive('publishCreateMarketData');
        });

        $this->artisan('company:create');

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'TST',
            'event'     => CompanyScheduleRepository::EVENT_CREATE_COMPANY,
            'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
        ]);
    }

    /** @test */
    public function it_should_create_a_market_data_schedule()
    {
        $this->mockCompanyProvider('TST');
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishCreateCompany');
            $mock->shouldReceive('publishCreateMarketData');
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
                ->andReturn([
                    'alternativeTicker' => $ticker,
                    'company' => CompanyContainer::from([
                        'ticker'    => $ticker,
                        'name'      => 'Test Inc.',
                        'sector'    => 'Tech',
                        'financialStatements' => [
                            'incomeStatements'  => [],
                            'balanceSheets'     => [],
                            'cashFlows'         => [],
                        ]
                    ]),
                ]);

            $mock->shouldReceive('getMarketData')
                ->andReturn(MarketDataContainer::from([
                    'analyst' => [
                        'priceTarget' => [
                            'average' => 2
                        ],
                        'rating' => [
                            'buy' => 10
                        ]
                    ],
                    'shareData' => [
                        'price' => 120,
                        'beta'  => 1.2
                    ]
                ]));
        });
    }
}
