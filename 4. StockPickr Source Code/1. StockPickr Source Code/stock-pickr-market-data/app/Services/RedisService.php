<?php

namespace App\Services;

use StockPickr\Common\Containers\MarketData\MarketDataContainer;
use StockPickr\Common\Containers\MarketData\MarketDataUpsertFailedContainer;
use StockPickr\Common\Containers\MarketData\UpsertMarketDataContainer;
use StockPickr\Common\Events\MarketData\MarketDataCreatedEvent;
use StockPickr\Common\Events\MarketData\MarketDataCreateFailedEvent;
use StockPickr\Common\Events\MarketData\MarketDataUpdatedEvent;
use StockPickr\Common\Events\MarketData\MarketDataUpdateFailedEvent;
use StockPickr\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function __construct(private string $consumerMode)
    {
    }

    public function getServiceName(): string
    {
        return 'market-data';
    }

    public function getConsumerMode(): string
    {
        return $this->consumerMode;
    }

    public function publishMarketDataCreated(string $ticker, MarketDataContainer $marketData, string $scheduleId): void
    {
        $this->publish(new MarketDataCreatedEvent(
            $scheduleId,
            UpsertMarketDataContainer::create($ticker, $marketData)
        ));
    }

    public function publishMarketDataUpdated(string $ticker, MarketDataContainer $marketData, string $scheduleId): void
    {
        $this->publish(new MarketDataUpdatedEvent(
            $scheduleId,
            UpsertMarketDataContainer::create($ticker, $marketData)
        ));
    }

    public function publishMarketDataCreateFailed(string $ticker, string $message, string $scheduleId): void
    {
        $this->publish(new MarketDataCreateFailedEvent(
            $scheduleId,
            MarketDataUpsertFailedContainer::create($ticker, $message)
        ));
    }

    public function publishMarketDataUpdateFailed(string $ticker, string $message, string $scheduleId): void
    {
        $this->publish(new MarketDataUpdateFailedEvent(
            $scheduleId,
            MarketDataUpsertFailedContainer::from(compact('ticker', 'message'))
        ));
    }
}
