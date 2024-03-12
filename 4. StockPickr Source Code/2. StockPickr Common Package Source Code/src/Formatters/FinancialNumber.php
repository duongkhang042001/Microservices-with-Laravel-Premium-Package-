<?php

declare(strict_types=1);

namespace StockPickr\Common\Formatters;

final class FinancialNumber extends Formatter
{
    private const BILLION_VALUE = 1000;

    public function format(?float $value): ?string
    {
        return match ($value) {
            null => null,
            0.0 => '0',
            default => $this->getFormattedValue((float) $value)
        };
    }

    private function getFormattedValue(float $value): string
    {
        return $this->getSignedValue(
            $value,
            $this->getSufixedLocalValue($value)
        );
    }

    private function getSufixedLocalValue(float $value): string
    {
        $absoluteValue = abs($value);

        return match ($this->isMillion($absoluteValue)) {
            true => $this->numberFormat($absoluteValue) . 'M',
            false => $this->numberFormat(
                $absoluteValue / self::BILLION_VALUE) . 'B'
        };
    }

    private function getSignedValue(
        float $originalValue,
        string $sufixedLocalValue
    ): string {
        /** @phpstan-ignore-next-line */
        return match ($originalValue < 0) {
            true => sprintf('(%s)', $sufixedLocalValue),
            false => $sufixedLocalValue
        };
    }

    private function isMillion(float $value): bool
    {
        return abs($value) < self::BILLION_VALUE;
    }
}
