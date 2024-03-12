<?php

declare(strict_types=1);

namespace StockPickr\Common\Formatters;

final class Number extends Formatter
{
    public function format(?float $value): ?string
    {
        return match ($value) {
            null => null,
            0.0 => '0',
            default => $this->numberFormat($value)
        };
    }
}
