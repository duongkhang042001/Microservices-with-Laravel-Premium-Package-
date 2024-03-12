<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'averageRating' => $this->averageRating,
            'availableQuantity' => $this->availableQuantity,
        ];
    }
}
