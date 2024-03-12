<?php

namespace App\Actions;

use App\Exceptions\ProductInventoryExceededException;
use App\Models\Inventory;
use App\Models\Product;
use App\Services\RedisService;
use Ecommerce\Common\DataTransferObjects\Order\OrderData;
use Ecommerce\Common\DataTransferObjects\Warehouse\InventoryData;
use Illuminate\Support\Facades\DB;

class DecreaseInventoryAction
{
    public function __construct(private readonly RedisService $redis)
    {
    }

    public function execute(OrderData $orderData): void
    {
        DB::transaction(function () use ($orderData) {
            $product = Product::findOrFail($orderData->productId);

            $this->decrease($product, $orderData->quantity);

            $totalQuantity = Inventory::totalQuantity($product);

            $inventoryData = new InventoryData(
                $product->id,
                $totalQuantity
            );

            $this->redis->publishInventoryUpdated($inventoryData);
        });
    }

    /**
     * @param Product $product
     * @param float $quantity
     * @throws ProductInventoryExceededException|\Throwable
     */
    private function decrease(Product $product, float $quantity): void
    {
        if (Inventory::totalQuantity($product) < $quantity) {
            throw new ProductInventoryExceededException(
                "There is not enough $product->name in inventory"
            );
        }

        $quantityLeft = $quantity;

        foreach ($product->inventories as $inventory) {
            if ($inventory->quantity >= $quantityLeft) {
                $inventory->quantity -= $quantityLeft;
                $inventory->save();

                $this->deleteInventoryIfEmpty($inventory);

                break;
            }

            $quantityLeft -= $inventory->quantity;

            $inventory->delete();
        }
    }

    private function deleteInventoryIfEmpty(Inventory $inventory): void
    {
        if ($inventory->quantity === 0.0) {
            $inventory->delete();
        }
    }
}
