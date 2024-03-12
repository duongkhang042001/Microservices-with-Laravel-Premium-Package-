<?php

namespace App\Rules;

use App\Enums\Scores;

final class DownwardRule extends Rule
{
    public function getDifference(float $ownValue, float $othersValue): float
    {
        // A downward típusú értékekből semmi nem lehet negatív, ezért nem
        // kell edge case -ekkel foglalkozni

        // Interest to operating profit -nál lehet olyan, hogy 0 az others value
        if ($ownValue !== 0.0 && $othersValue === 0.0) {
            return -1;
        }

        if ($ownValue === 0.0 && $othersValue === 0.0) {
            return 0;
        }

        if ($ownValue === 0.0) {
            return 1;
        }

        // @phpstan-ignore-next-line
        return match ($ownValue > $othersValue) {
            true => ($ownValue / $othersValue - 1) * -1,
            false => $othersValue / $ownValue - 1,
        };
    }
}
