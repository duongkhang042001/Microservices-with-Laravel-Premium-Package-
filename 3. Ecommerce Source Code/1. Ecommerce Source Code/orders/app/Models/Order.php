<?php

namespace App\Models;

use Ecommerce\Common\DataTransferObjects\Order\OrderData;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function toData(): OrderData
    {
        return new OrderData(
            $this->product_id,
            $this->quantity,
            $this->total_price
        );
    }
}
