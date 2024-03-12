<?php

namespace App\Models;

use Ecommerce\Common\DataTransferObjects\Product\CategoryData;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function toData(): CategoryData
    {
        return new CategoryData($this->id, $this->name);
    }
}
