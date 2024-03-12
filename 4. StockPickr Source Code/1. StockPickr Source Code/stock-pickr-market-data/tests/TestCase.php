<?php

namespace Tests;

use App\Services\RedisService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use Mockery\MockInterface;
use StockPickr\Common\Containers\MarketData\UpsertMarketDataContainer;
use StockPickr\Common\Containers\UpsertCompanyContainer;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function tearDown(): void
    {
        Redis::flushall();
        parent::tearDown();
    }

    protected function mockRedisService(string $fn)
    {
        $this->mock(RedisService::class, function (MockInterface $mock) use ($fn) {
            $mock->shouldReceive($fn);
        });
    }

    public function getData(array $override): array
    {
        $data = [
            'ticker'    => 'TST',
            'analyst'   => [
                'priceTargetLow'      => 100.25,
                'priceTargetAverage'  => 150.00,
                'priceTargetHigh'     => 200.10,

                'ratingBuy'             => 12,
                'ratingHold'            => 20,
                'ratingSell'            => 3,
            ],
            'shareData' => [
                'price'                 => 100.2310,
                'marketCap'             => 13472.572199,
                'sharesOutstanding'     => 1320,
                'beta'                  => 2.973
            ]
        ];

        $data['analyst'] = array_merge($data['analyst'], Arr::get($override, 'analyst', []));
        $data['shareData'] = array_merge($data['shareData'], Arr::get($override, 'shareData', []));

        return $data;
    }

    protected function createUpsertCompanyContainer(array $data)
    {
        $container = UpsertCompanyContainer::from(array_merge([
            'ticker'        => 'TST',
            'name'          => 'Test Inc.',
            'sector'        => 'Tech',
            'shareData'     => [],
            'analyst'       => [],
            'financialStatements' => [
                'incomeStatements' => [],
                'balanceSheets'    => [],
                'cashFlows'        => [],
            ]
        ], $data));

        return $container;
    }

    protected function createUpsertMarketDataContainer(array $data)
    {
        return UpsertMarketDataContainer::from(array_merge([
            'ticker'     => 'TST',
            'scheduleId' => 'abc-123',
            'marketData' => [
                'shareData'     => [],
                'analyst'       => []
            ],
        ], $data));
    }
}
