<?php

namespace App\Services;

use App\DataTransferObjects\RatingData;
use Ecommerce\Common\DataTransferObjects\Product\ProductData;
use Illuminate\Support\Collection;

class RatingService
{
    public function __construct(private HttpClient $httpClient)
    {
    }

    /**
     * @param Collection<ProductData> $products
     * @return Collection<RatingData>
     */
    public function getRatings(Collection $products): Collection
    {
        return $this->httpClient
            ->get('products/ratings', [
                'productIds' => $products->pluck('id')->toArray()
            ])
            ->map(fn (array $rating) => new RatingData(...$rating));
    }
}
