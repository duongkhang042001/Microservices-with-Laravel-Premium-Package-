<?php

declare(strict_types=1);

namespace StockPickr\Common\SafeTypes;

abstract class SafeType
{
    final private function __construct()
    {
    }

    /** @phpstan-ignore-next-line */
    abstract public function format(null | string | float | int $value);

    public static function create(): static
    {
        return new static();
    }

    protected function isEmpty(null | string | float | int $value): bool
    {
        return match ($value) {
            null => true,
            '' => true,
            default => false
        };
    }
}
