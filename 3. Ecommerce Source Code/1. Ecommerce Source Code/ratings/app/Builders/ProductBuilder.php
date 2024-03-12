<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductBuilder extends Builder
{
    public function whereHasRatings(Collection $ids): self
    {
        return $this->with('ratings')
            ->whereIn('id', $ids);
    }
}
