<?php

namespace App\Models;

use App\Builders\ProductBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function latestRatings()
    {
        return $this->hasMany(ProductRating::class)
            ->latest();
    }

    public function newEloquentBuilder($query)
    {
        return new ProductBuilder($query);
    }
}
