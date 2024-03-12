<?php

namespace App\DataTransferObjects;

class RatingData
{
    public function __construct(
        public int $productId,
        public ?float $averageRating,
        public int $numberOfRatings
    ) {}
}
