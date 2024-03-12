<?php

namespace App\Actions;

use App\Exceptions\ProductInventoryExceededException;
use App\Models\Order;
use App\Models\Product;
use App\Services\RedisService;
use App\Services\WarehouseService;

class CreateOrderAction
{
    public function __construct(
        private readonly WarehouseService $warehouseService,
        private readonly RedisService $redis,
    ) {}

    /**
     * @throws ProductInventoryExceededException
     */
    public function execute(Product $product, float $quantity): Order
    {
        if ($quantity > $this->warehouseService->getTotalInventory($product)) {
            throw new ProductInventoryExceededException(
                "There is not enough $product->name in inventory"
            );
        }

        $order = Order::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'total_price' => $product->price * $quantity,
        ]);

        $this->redis->publishOrderCreated($order->toData());

        return $order;
    }
}
