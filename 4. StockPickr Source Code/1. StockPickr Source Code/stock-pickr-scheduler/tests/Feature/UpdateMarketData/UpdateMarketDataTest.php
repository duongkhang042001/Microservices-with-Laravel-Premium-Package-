<?php

namespace Tests\Feature\UpdateMarketData;

use App\Repositories\CompanyScheduleRepository;
use App\Services\CompanyProviderService;
use App\Services\RedisService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use StockPickr\Common\Containers\ShareDataContainer;

class UpdateMarketDataTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_company_schedule_and_publish_an_update_market_data_event()
    {
        $this->mockCompanyProvider('TST');
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishUpdateMarketData');
        });

        $this->artisan('company:update:market-data');

        $this->assertDatabaseHas('company_schedules', [
            'ticker'    => 'TST',
            'event'     => CompanyScheduleRepository::EVENT_UPDATE_MARKET_DATA,
            'state'     => CompanyScheduleRepository::STATE_IN_PROGRESS
        ]);
    }

    private function mockCompanyProvider(string $ticker)
    {
        $this->mock(CompanyProviderService::class, function (MockInterface $mock) use ($ticker) {
            $mock->shouldReceive('getNextTickerForShareDataUpdate')
                ->andReturn($ticker);

            $mock->shouldReceive('getShareData')
                ->andReturn(new ShareDataContainer([
                    'price'     => 100,
                    'marketCap' => 1000
                ]));
        });
    }
}
