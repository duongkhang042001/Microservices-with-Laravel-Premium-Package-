<?php

namespace App\Services;

use StockPickr\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function __construct(private string $consumerMode)
    {
    }

    public function getServiceName(): string
    {
        return 'company-query';
    }

    public function getConsumerMode(): string
    {
        return $this->consumerMode;
    }
}
