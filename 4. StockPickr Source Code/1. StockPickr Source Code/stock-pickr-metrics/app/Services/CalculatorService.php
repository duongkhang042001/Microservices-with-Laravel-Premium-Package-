<?php

namespace App\Services;

use DivisionByZeroError;

final class CalculatorService
{
    public function divide(float $x, float $y, ?float $default = 0): ?float
    {
        try {
            return $x / $y;
        } catch (DivisionByZeroError) {
            return $default;
        }
    }

    public function positive()
    {
        return fn (float $number) => $number > 0;
    }

    public function negative()
    {
        return fn (float $number) => $number < 0;
    }

    public function zero()
    {
        return fn (float $number) => $number === 0.0;
    }

    public function zeroOrMore()
    {
        return fn (float $number) => $number >= 0.0;
    }

    public function zeroOrLess()
    {
        return fn (float $number) => $number <= 0.0;
    }

    public function any()
    {
        return fn () => true;
    }
}
