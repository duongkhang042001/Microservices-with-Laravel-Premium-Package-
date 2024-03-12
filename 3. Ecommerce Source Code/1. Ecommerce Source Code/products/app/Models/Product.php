<?php

namespace App\Models;

use App\Builders\ProductBuilder;
use Ecommerce\Common\DataTransferObjects\Product\CategoryData;
use Ecommerce\Common\DataTransferObjects\Product\ProductData;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPriceFormattedAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function newEloquentBuilder($query)
    {
        return new ProductBuilder($query);
    }

    public function toData(): ProductData
    {
        return new ProductData(
            $this->id,
            $this->name,
            $this->description,
            $this->price,
            new CategoryData(
                /** @phpstan-ignore-next-line */
                $this->category->id,
                /** @phpstan-ignore-next-line */
                $this->category->name,
            ),
        );
    }
}
