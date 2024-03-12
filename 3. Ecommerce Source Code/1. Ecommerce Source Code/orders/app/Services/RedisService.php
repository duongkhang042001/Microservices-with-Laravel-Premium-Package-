<?php

namespace App\Services;

use Ecommerce\Common\DataTransferObjects\Order\OrderData;
use Ecommerce\Common\Events\Order\OrderCreatedEvent;
use Ecommerce\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function getServiceName(): string
    {
        return 'orders';
    }

    public function publishOrderCreated(OrderData $data): void
    {
        $this->publish(new OrderCreatedEvent($data));
    }
}
