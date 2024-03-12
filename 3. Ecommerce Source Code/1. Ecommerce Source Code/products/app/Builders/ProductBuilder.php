<?php

namespace App\Builders;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductBuilder extends Builder
{
    /**
     * @return Collection<Product>
     */
    public function search(
        ?string $searchTerm = null,
        ?string $sortBy = 'name',
        ?string $sortDirection = 'asc',
    ): Collection {
        return Product::select('products.*')
            ->when($searchTerm, fn (Builder $query) =>
                $query
                    ->leftJoin('categories', 'categories.id', 'products.category_id')
                    ->where('products.name', 'LIKE', "%$searchTerm%")
                    ->orWhere('products.description', 'LIKE', "%$searchTerm%")
                    ->orWhere('categories.name', 'LIKE', "%$searchTerm%")
            )->when($sortBy, fn (Builder $query) =>
                $query->orderBy("products.$sortBy", $sortDirection)
            )->get();
    }
}
