<?php

namespace App\Services;

use Ecommerce\Common\DataTransferObjects\Product\ProductData;
use Ecommerce\Common\Events\Product\ProductCreatedEvent;
use Ecommerce\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function getServiceName(): string
    {
        return 'products';
    }

    public function publishProductCreated(ProductData $data): void
    {
        $this->publish(new ProductCreatedEvent($data));
    }
}
