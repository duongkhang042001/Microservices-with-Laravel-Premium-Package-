<?php

declare(strict_types=1);

namespace StockPickr\Common\Formatters;

use InvalidArgumentException;

final class FormatterFactory
{
    /**
     * @param array{decimals: ?int} $options
     */
    public function create(
        string $type,
        array $options = ['decimals' => 2]
    ): Formatter {
        return match ($type) {
            Formatter::MONEY => Money::create($options),
            Formatter::NUMBER => Number::create($options),
            Formatter::PERCENT => Percent::create($options),
            Formatter::FINANCIAL_NUMBER => FinancialNumber::create($options),
            default => throw new InvalidArgumentException(
                'No formatter for type: ' . $type)
        };
    }
}
