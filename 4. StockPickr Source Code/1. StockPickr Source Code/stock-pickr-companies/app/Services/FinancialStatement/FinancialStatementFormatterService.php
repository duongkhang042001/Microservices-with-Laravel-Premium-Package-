<?php

namespace App\Services\FinancialStatement;

use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementItemContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementValueContainer;
use StockPickr\Common\Formatters\Formatter;
use StockPickr\Common\Formatters\FormatterFactory;

class FinancialStatementFormatterService
{
    public function __construct(private FormatterFactory $formatterFactory)
    {
    }

    public function formatContainer(FinancialStatementContainer $container)
    {
        /** @var FinancialStatementItemContainer $item */
        foreach ($container as $item) {
            /** @var Formatter $formatter */
            $formatter = $this->formatterFactory->create($item->formatter);

            /** @var FinancialStatementValueContainer $valueContainer */
            foreach ($item->data as $year => $valueContainer) {
                $invertedValue = $this->getInvertedValue($item, $valueContainer);
                $item->data[$year]->formattedValue = $formatter->format($invertedValue);

                if ($item->shouldHighlightNegativeValue && $this->isNegative($item, $valueContainer->rawValue)) {
                    $item->data[$year]->classes = ['negative'];
                }
            }
        }
    }

    private function getInvertedValue(
        FinancialStatementItemContainer $itemContainer,
        FinancialStatementValueContainer $valueContainer
    ) {
        return match ($valueContainer->rawValue !== null && $itemContainer->isInverted) {
            true => $valueContainer->rawValue * -1,
            false => $valueContainer->rawValue
        };
    }

    private function isNegative(FinancialStatementItemContainer $container, ?float $value): bool
    {
        return match ($container->isInverted) {
            true => $value > 0,
            false => $value < 0
        };
    }
}
