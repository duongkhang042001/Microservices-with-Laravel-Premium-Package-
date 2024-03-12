<?php

namespace App\Services;

use App\Models\Company;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\Metrics\MetricsContainer;
use StockPickr\Common\Containers\Metrics\MetricsUpsertedContainer;
use StockPickr\Common\Containers\Metrics\MetricsUpsertFailedContainer;
use StockPickr\Common\Containers\Score\CompanyScoredContainer;
use StockPickr\Common\Containers\ScoreCompaniesFailedContainer;
use StockPickr\Common\Events\Metrics\MetricsCreatedEvent;
use StockPickr\Common\Events\Metrics\MetricsCreateFailedEvent;
use StockPickr\Common\Events\Metrics\MetricsUpdatedEvent;
use StockPickr\Common\Events\Score\CompanyScoredEvent;
use StockPickr\Common\Events\ScoreCompaniesFailedEvent;
use StockPickr\Common\Events\ScoreCompaniesSucceededEvent;
use StockPickr\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function __construct(private string $consumerMode)
    {
    }

    public function getServiceName(): string
    {
        return 'metrics';
    }

    public function getConsumerMode(): string
    {
        return $this->consumerMode;
    }

    public function publishMetricsCreatedEvent(
        CompanyUpsertedContainer $company,
        MetricsContainer $metrics
    ): void {
        $this->publish(new MetricsCreatedEvent(
            MetricsUpsertedContainer::create($company->ticker, $metrics)
        ));
    }

    public function publishMetricsUpdatedEvent(
        CompanyUpsertedContainer $company,
        MetricsContainer $metrics,
    ): void {
        $this->publish(new MetricsUpdatedEvent(
            MetricsUpsertedContainer::create($company->ticker, $metrics)
        ));
    }

    public function publishMetricsCreateFailedEvent(
        string $ticker,
        string $message
    ): void {
        $this->publish(new MetricsCreateFailedEvent(
            MetricsUpsertFailedContainer::create($ticker, $message)
        ));
    }

    public function publishMetricsUpdateFailedEvent(
        string $ticker,
        string $message
    ): void {
        $this->publish(new MetricsCreateFailedEvent(
            MetricsUpsertFailedContainer::create($ticker, $message)
        ));
    }

    public function publishCompanyScored(Company $company): void
    {
        $this->publish(new CompanyScoredEvent(
            CompanyScoredContainer::from($company->toArray())
        ));
    }

    public function publishScoreSucceeded(
        string $scheduleId,
    ): void {
        $this->publish(new ScoreCompaniesSucceededEvent(
            $scheduleId
        ));
    }

    public function publishScoreFailed(
        string $scheduleId,
        string $message
    ): void {
        $this->publish(new ScoreCompaniesFailedEvent(
            $scheduleId,
            ScoreCompaniesFailedContainer::create($message)
        ));
    }
}
