<?php

namespace App\Services;

use StockPickr\Common\Containers\MarketData\MarketDataContainer;
use StockPickr\Common\Containers\MarketData\UpsertMarketDataContainer;
use StockPickr\Common\Containers\UpsertCompanyContainer;
use StockPickr\Common\Events\Company\RemoveCompanyEvent;
use StockPickr\Common\Events\CreateCompanyEvent;
use StockPickr\Common\Events\MarketData\CreateMarketDataEvent;
use StockPickr\Common\Events\MarketData\UpdateMarketDataEvent;
use StockPickr\Common\Events\ScoreCompaniesEvent;
use StockPickr\Common\Events\UpdateCompanyEvent;
use StockPickr\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function __construct(private string $consumerMode)
    {
    }

    protected function getServiceName(): string
    {
        return 'scheduler';
    }

    public function getConsumerMode(): string
    {
        return $this->consumerMode;
    }

    public function publishCreateCompany(UpsertCompanyContainer $company, string $scheduleId): void
    {
        $this->publish(new CreateCompanyEvent($scheduleId, $company));
    }

    public function publishUpdateCompany(UpsertCompanyContainer $company, string $scheduleId): void
    {
        $this->publish(new UpdateCompanyEvent($scheduleId, $company));
    }

    public function publishCreateMarketData(string $ticker, MarketDataContainer $marketData, string $scheduleId): void
    {
        $this->publish(new CreateMarketDataEvent(
            $scheduleId,
            UpsertMarketDataContainer::create($ticker, $marketData)
        ));
    }

    public function publishUpdateMarketData(string $ticker, MarketDataContainer $marketData, string $scheduleId): void
    {
        $this->publish(new UpdateMarketDataEvent(
            $scheduleId,
            UpsertMarketDataContainer::create($ticker, $marketData)
        ));
    }

    public function publishScoreCompanies(string $scheduleId): void
    {
        $this->publish(new ScoreCompaniesEvent(
            $scheduleId
        ));
    }

    public function publishRemoveCompany(string $ticker): void
    {
        $this->publish(new RemoveCompanyEvent($ticker));
    }
}
