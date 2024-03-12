<?php

namespace App\Models;

use App\Builders\InventoryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function newEloquentBuilder($query)
    {
        return new InventoryBuilder($query);
    }
}
