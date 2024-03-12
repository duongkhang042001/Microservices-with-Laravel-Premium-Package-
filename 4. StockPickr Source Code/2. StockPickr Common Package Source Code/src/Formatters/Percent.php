<?php

declare(strict_types=1);

namespace StockPickr\Common\Formatters;

final class Percent extends Formatter
{
    public function format(?float $value): ?string
    {
        return match (is_null($value)) {
            true => null,
            false => $this->getFormattedValue((float) $value)
        };
    }

    private function getFormattedValue(float $value): string
    {
        return $this->numberFormat(
            (float) $value * 100
        ) . '%';
    }
}
