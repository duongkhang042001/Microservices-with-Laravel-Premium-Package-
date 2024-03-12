<?php

declare(strict_types=1);

namespace StockPickr\Common\Formatters;

final class Money extends Formatter
{
    public function format(?float $value): ?string
    {
        return match ($this->isEmpty($value)) {
            true => null,
            false => $this->getFormattedValue((float) $value)
        };
    }

    protected function getFormattedValue(float $value): string
    {
        /** @phpstan-ignore-next-line */
        return match ($value < 0) {
            true => '($' . $this->numberFormat(abs($value)) . ')',
            false => '$' . $this->numberFormat($value)
        };
    }

    protected function isEmpty(?float $value): bool
    {
        return (float) $value === 0.0;
    }
}
