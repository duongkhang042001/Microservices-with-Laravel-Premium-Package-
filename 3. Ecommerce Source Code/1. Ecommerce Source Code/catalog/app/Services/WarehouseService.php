<?php

namespace App\Services;

use Ecommerce\Common\DataTransferObjects\Product\ProductData;
use Ecommerce\Common\DataTransferObjects\Warehouse\InventoryData;
use Illuminate\Support\Collection;

class WarehouseService
{
    public function __construct(private readonly HttpClient $httpClient)
    {
    }

    /**
     * @param Collection<ProductData> $products
     * @return Collection<InventoryData>
     */
    public function getAvailableInventories(Collection $products): Collection
    {
        return $this->httpClient
            ->get('inventory/products', [
                'productIds' => $products->pluck('id')->toArray()
            ])
            ->map(fn (array $inventory) => new InventoryData(...$inventory));
    }
}
