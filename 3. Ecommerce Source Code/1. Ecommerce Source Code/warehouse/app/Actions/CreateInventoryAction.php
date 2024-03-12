<?php

namespace App\Actions;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\RedisService;
use Ecommerce\Common\DataTransferObjects\Warehouse\InventoryData;

class CreateInventoryAction
{
    public function __construct(private readonly RedisService $redisService)
    {
    }

    public function execute(Product $product, Warehouse $warehouse, float $quantity): InventoryData
    {
        Inventory::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => $quantity,
        ]);

        $totalQuantity = Inventory::totalQuantity($product);

        $inventoryData = new InventoryData(
            $product->id,
            $totalQuantity,
        );

        $this->redisService->publishInventoryUpdated($inventoryData);

        return $inventoryData;
    }
}
