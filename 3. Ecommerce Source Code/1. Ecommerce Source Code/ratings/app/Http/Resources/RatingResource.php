<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'productId' => $this->id,
            'averageRating' => $this->average_rating,
            'numberOfRatings' => $this->ratings()->count(),
        ];
    }
}
