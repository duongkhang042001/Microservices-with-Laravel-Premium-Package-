<?php

namespace App\Rules;

final class UpwardRule extends Rule
{
    public function getDifference(float $ownValue, float $othersValue): float
    {
        if ($ownValue <= 0 && $othersValue > 0) {
            return -1;
        }

        if ($ownValue >= 0 && $othersValue < 0) {
            return 1;
        }

        if ($ownValue === 0.0 && $othersValue === 0.0) {
            return 0;
        }

        if ($othersValue === 0.0) {
            return 1;
        }

        // Itt már biztos, hogy mindkettő azonos előjellel szerepel

        // @phpstan-ignore-next-line
        return match (abs($ownValue) > abs($othersValue)) {
            true => ($ownValue / $othersValue - 1),
            false => ($othersValue / $ownValue - 1) * -1,
        };
    }
}
