<?php

namespace App\DataTransferObjects;

class CatalogData
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly ?float $averageRating,
        public readonly ?int $numberOfRatings,
        public readonly float $availableQuantity
    ) {}
}
