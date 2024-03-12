<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'totalPrice' => (float) $this->total_price,
            'product' => new ProductResource($this->product),
        ];
    }
}
