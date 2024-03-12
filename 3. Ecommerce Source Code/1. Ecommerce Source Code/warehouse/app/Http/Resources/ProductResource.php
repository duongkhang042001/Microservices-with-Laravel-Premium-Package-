<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'totalInventory' => $this->loadSum('inventories', 'quantity')->inventories_sum_quantity,
        ];
    }
}
