<?php

namespace App\Actions;

use App\Models\Category;
use App\Models\Product;
use App\Services\RedisService;

class CreateProductAction
{
    public function __construct(private readonly RedisService $redis)
    {
    }

    public function execute(Category $category, string $name, string $description, float $price): Product
    {
        $product = Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'description' => $description,
            'price' => $price
        ]);

        $this->redis->publishProductCreated(
            $product->toData(),
        );

        return $product;
    }
}
