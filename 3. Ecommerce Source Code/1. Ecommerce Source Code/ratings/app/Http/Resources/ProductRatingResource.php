<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductRatingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'productId' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'createdAt' => $this->created_at->isoFormat('ll'),
        ];
    }
}
