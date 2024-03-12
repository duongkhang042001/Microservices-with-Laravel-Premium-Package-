<?php

namespace App\Services;

use App\DataTransferObjects\CatalogSearchData;
use Ecommerce\Common\DataTransferObjects\Product\ProductData;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(private HttpClient $httpClient)
    {
    }

    /**
     * @return Collection<ProductData>
     */
    public function getProducts(CatalogSearchData $data): Collection
    {
        return $this->httpClient
            ->get('products', $data->toArray())
            ->map(fn (array $product) => ProductData::fromArray($product));
    }
}
