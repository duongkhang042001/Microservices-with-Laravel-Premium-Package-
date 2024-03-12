<?php

namespace StockPickr\Common\Containers\Company\FinancialStatement;

use Iterator;
use StockPickr\Common\Containers\Container;

/**
 * @phpstan-implements Iterator<string, FinancialStatementItemContainer>
 */
abstract class FinancialStatementContainer extends Container implements Iterator
{
    protected int $position = 0;
    /** @var string[] */
    protected array $items = [];

    public function getRawValue(string $item, int $year): ?float
    {
        return $this->{$item}->data[$year]->rawValue;
    }

    public function getFormattedValue(string $item, int $year): ?string
    {
        return $this->{$item}->data[$year]->formattedValue;
    }

    public function getClasses(string $item, int $year): array
    {
        return $this->{$item}->data[$year]->classes;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current(): FinancialStatementItemContainer
    {
        $item = $this->items[$this->position];
        return $this->{$item};
    }

    public function key(): string
    {
        return $this->items[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }
}