<?php

namespace StockPickr\Common\Containers\Company\FinancialStatement;

use StockPickr\Common\Containers\Container;

final class FinancialStatementItemContainer extends Container
{
    public string $name;
    public string $formatter;
    public bool $shouldHighlightNegativeValue;
    public bool $isInverted;

    /**
     * 2020 => 100
     * @var array<int, FinancialStatementValueContainer>
     */
    public array $data;

    /**
     * @param array{
     *  formatter: string, 
     *  name: string, 
     *  shouldHighlightNegativeValue: bool,
     *  isInverted: bool,
     *  data: array<int, array>
     * } $data
     */
    public static function from(array $data): static
    {
        $container = new static();

        $container->name = $data['name'];
        $container->formatter = $data['formatter'];
        $container->shouldHighlightNegativeValue = $data['shouldHighlightNegativeValue'];
        $container->isInverted = $data['isInverted'];

        foreach ($data['data'] as $year => $value) {
            /** @var array{
             *   rawValue: ?float,
             *   formattedValue: ?string,
             *   classes: string[]
             * } $value
             */
            $container->data[$year] = FinancialStatementValueContainer::from($value);
        }

        return $container;
    }
}