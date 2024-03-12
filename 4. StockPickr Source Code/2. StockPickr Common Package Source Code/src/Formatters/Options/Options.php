<?php

declare(strict_types=1);

namespace StockPickr\Common\Formatters\Options;

use StockPickr\Common\Support\Arr;

final class Options
{
    private int $decimals = 2;

    /**
     * @param array{decimals: ?int} $options
     */
    public function __construct(array $options)
    {
        $this->decimals = Arr::get($options, 'decimals', 2);
    }

    public function getDecimals(): int
    {
        return $this->decimals;
    }
}
