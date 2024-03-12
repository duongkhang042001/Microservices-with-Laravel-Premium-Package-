<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\ProductRating;
use App\Services\RedisService;
use Ecommerce\Common\DataTransferObjects\Rating\ProductRatingData;
use Illuminate\Support\Facades\DB;

class RateProductAction
{
    public function __construct(private readonly RedisService $redis)
    {
    }

    public function execute(
        Product $product,
        float $rating,
        ?string $comment
    ): ProductRatingData {
        return DB::transaction(function () use ($product, $rating, $comment) {
            $averageRating = $this->rate($product, $rating, $comment);

            $data = new ProductRatingData(
                $product->id,
                $rating,
                round($averageRating, 2),
            );

            $this->redis->publishProductRated($data);

            return $data;
        });
    }

    private function rate(Product $product, float $rating, ?string $comment): float
    {
        ProductRating::create([
            'product_id' => $product->id,
            'rating' => $rating,
            'comment' => $comment,
        ]);

        return $this->updateAverageRating($product);
    }

    private function updateAverageRating(Product $product): float
    {
        $product->average_rating = $product->ratings->avg('rating');

        $product->save();

        return $product->average_rating;
    }
}
