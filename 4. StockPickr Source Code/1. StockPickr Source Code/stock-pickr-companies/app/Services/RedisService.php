<?php

namespace App\Services;

use StockPickr\Common\Containers\Company\CompanyLogContainer;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\CompanyUpsertFailedContainer;
use StockPickr\Common\Events\Company\CompanyLogCreatedEvent;
use StockPickr\Common\Events\CompanyCreatedEvent;
use StockPickr\Common\Events\CompanyCreateFailedEvent;
use StockPickr\Common\Events\CompanyUpdatedEvent;
use StockPickr\Common\Events\CompanyUpdateFailedEvent;
use StockPickr\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function __construct(private string $consumerMode)
    {
    }

    public function getServiceName(): string
    {
        return 'companies';
    }

    public function getConsumerMode(): string
    {
        return $this->consumerMode;
    }

    public function publishCompanyCreated(CompanyUpsertedContainer $company, string $scheduleId)
    {
        $this->publish(new CompanyCreatedEvent($scheduleId, $company));
    }

    public function publishCompanyUpdated(CompanyUpsertedContainer $company, string $scheduleId)
    {
        $this->publish(new CompanyUpdatedEvent($scheduleId, $company));
    }

    public function publishCompanyCreateFailed(
        string $ticker,
        string $message,
        string $scheduleId
    ) {
        $this->publish(new CompanyCreateFailedEvent(
            $scheduleId,
            CompanyUpsertFailedContainer::create($ticker, $message)
        ));
    }

    public function publishCompanyUpdateFailed(
        string $ticker,
        string $message,
        string $scheduleId
    ) {
        $this->publish(new CompanyUpdateFailedEvent(
            $scheduleId,
            CompanyUpsertFailedContainer::create($ticker, $message)
        ));
    }

    public function publishCompanyLogCreated(string $action, string $payload)
    {
        $this->publish(new CompanyLogCreatedEvent(
            CompanyLogContainer::create($action, $payload)
        ));
    }
}
