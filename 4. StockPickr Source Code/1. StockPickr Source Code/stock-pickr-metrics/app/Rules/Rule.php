<?php

namespace App\Rules;

use App\Enums\Scores;

abstract class Rule
{
    abstract public function getDifference(
        float $ownValue,
        float $othersValue
    ): float;

    public function getScore(float $ownValue, float $othersValue): int
    {
        $diff = $this->getDifference($ownValue, $othersValue);

        return match (true) {
            $diff >= 0.15 => Scores::A,
            $diff <= 0.1499 && $diff >= -0.15 => Scores::B,
            $diff <= -0.1501 && $diff >= -0.33 => Scores::C,
            default => Scores::D
        };
    }
}
