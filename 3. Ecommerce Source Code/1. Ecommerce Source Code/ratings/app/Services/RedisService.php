<?php

namespace App\Services;

use Ecommerce\Common\DataTransferObjects\Rating\ProductRatingData;
use Ecommerce\Common\Events\Rating\ProductRatedEvent;
use Ecommerce\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function getServiceName(): string
    {
        return 'ratings';
    }

    public function publishProductRated(ProductRatingData $data): void
    {
        $this->publish(new ProductRatedEvent($data));
    }
}

