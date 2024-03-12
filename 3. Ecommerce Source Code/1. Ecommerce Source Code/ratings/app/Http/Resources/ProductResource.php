<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'averageRating' => $this->average_rating,
            'ratings' => ProductRatingResource::collection($this->latestRatings),
        ];
    }
}
