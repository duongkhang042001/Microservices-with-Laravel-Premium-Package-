<?php

namespace App\Console\Commands;

use App\Actions\CreateProductAction;
use App\Actions\DecreaseInventoryAction;
use App\Services\RedisService;
use Ecommerce\Common\DataTransferObjects\Order\OrderData;
use Ecommerce\Common\DataTransferObjects\Product\ProductData;
use Ecommerce\Common\Enums\Events;
use Illuminate\Console\Command;

class RedisConsumeCommand extends Command
{
    protected $signature = 'redis:consume';
    protected $description = 'Consume events from Redis stream';

    public function handle(
        RedisService $redis,
        CreateProductAction $createProduct,
        DecreaseInventoryAction $decreaseInventory,
    ) {
        foreach ($redis->getUnprocessedEvents() as $event) {
            match ($event['type']) {
                Events::PRODUCT_CREATED =>
                    $createProduct->execute(
                        ProductData::fromArray($event['data'])
                    ),
                Events::ORDER_CREATED =>
                    $decreaseInventory->execute(
                       OrderData::fromArray($event['data'])
                    ),
                default => null
            };

            $redis->addProcessedEvent($event);
        }
    }
}
