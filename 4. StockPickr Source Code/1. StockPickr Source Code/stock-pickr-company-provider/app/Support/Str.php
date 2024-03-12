<?php

namespace App\Support;

use Illuminate\Support\Str as SupportStr;

class Str extends SupportStr
{
    /**
     * @param string $string
     * @param string[] $needles
     * @return bool
     */
    public static function containsSome(string $string, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (parent::contains($string, $needle)) {
                return true;
            }
        }

        return false;
    }
}
