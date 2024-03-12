<?php

declare(strict_types=1);

namespace StockPickr\Common\SafeTypes;

final class NullableInt extends SafeType
{
    public function format(null | string | float | int $value): ?int
    {
        return match ($this->isEmpty($value)) {
            true => null,
            false => (int) $value
        };
    }
}
