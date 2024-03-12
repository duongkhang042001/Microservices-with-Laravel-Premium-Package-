<?php

namespace StockPickr\Common\Containers\Company\FinancialStatement;

use StockPickr\Common\Containers\Container;
use StockPickr\Common\Support\Arr;

final class FinancialStatementValueContainer extends Container
{
    public ?float $rawValue;
    public ?string $formattedValue;
    /** @var string[] */
    public array $classes;

    /**
     * @param array{
     *   rawValue: ?float,
     *   formattedValue: ?string,
     *   classes: string[]
     * } $data
     */
    public static function from(array $data): static
    {
        $container = new static();

        $container->rawValue = $data['rawValue'];
        $container->formattedValue = $data['formattedValue'];
        $container->classes = Arr::get($data, 'classes', []);

        return $container;
    }

    public static function create(
        ?float $rawValue, 
        ?string $formattedValue, 
        array $classes = []
    ): static {
        $container = new static();

        $container->rawValue = $rawValue;
        $container->formattedValue = $formattedValue;
        $container->classes = $classes;

        return $container;
    }
}