<?php

namespace App\Services;

use StockPickr\Common\Containers\Charts\ChartsUpsertFailedContainer;
use StockPickr\Common\Events\Charts\ChartsCreateFailedEvent;
use StockPickr\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function __construct(private string $consumerMode)
    {
    }

    public function getServiceName(): string
    {
        return 'charts';
    }

    public function getConsumerMode(): string
    {
        return $this->consumerMode;
    }

    public function publishChartsCreateFailed(string $ticker, string $message): void
    {
        $this->publish(new ChartsCreateFailedEvent(ChartsUpsertFailedContainer::from([
            'ticker' => $ticker,
            'message' => $message
        ])));
    }
}
