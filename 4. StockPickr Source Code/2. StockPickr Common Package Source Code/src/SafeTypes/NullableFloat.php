<?php

declare(strict_types=1);

namespace StockPickr\Common\SafeTypes;

final class NullableFloat extends SafeType
{
    public function format(null | string | float | int $value): ?float
    {
        return match ($this->isEmpty($value)) {
            true => null,
            false => (float) $value
        };
    }
}
