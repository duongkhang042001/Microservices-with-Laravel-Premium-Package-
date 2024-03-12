<?php

namespace App\Services;

use DivisionByZeroError;
use Exception;

class ValueCalculatorService
{
    public function divide(float $x, float $y, float $byZeroFallback = 0.0): float
    {
        try {
            return $x / $y;
        } catch (DivisionByZeroError | Exception) {
            return $byZeroFallback;
        }
    }
}
