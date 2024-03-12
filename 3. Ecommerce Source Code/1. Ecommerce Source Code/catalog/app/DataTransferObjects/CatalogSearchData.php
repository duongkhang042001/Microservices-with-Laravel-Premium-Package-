<?php

namespace App\DataTransferObjects;

class CatalogSearchData
{
    public function __construct(
        public ?string $sortBy,
        public ?string $sortDirection,
        public ?string $searchTerm
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
