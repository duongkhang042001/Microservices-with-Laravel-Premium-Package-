<?php

namespace App\Formatters;

use StockPickr\Common\Formatters\FinancialNumber;
use StockPickr\Common\Formatters\Formatter;

class MarketCap extends Formatter
{
    public function format(?float $value): ?string
    {
        return match ($this->isEmpty($value)) {
            true => null,
            false => '$' . FinancialNumber::create(['decimals' => 0])->format($value)
        };
    }

    private function isEmpty(?float $value): bool
    {
        return $value === 0.0 || is_null($value);
    }
}
