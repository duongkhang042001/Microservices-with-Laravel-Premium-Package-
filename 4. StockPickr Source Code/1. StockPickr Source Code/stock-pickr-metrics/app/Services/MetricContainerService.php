<?php

namespace App\Services;

use App\Containers\MetricContainer;
use App\Models\CompanyMetric;
use App\Repositories\MetricRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementItemContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementValueContainer;
use StockPickr\Common\Containers\Metrics\MetricsContainer;
use StockPickr\Common\Formatters\FormatterFactory;

final class MetricContainerService
{
    public function __construct(
        private MetricRepository $metrics,
        private FormatterFactory $formatterFactory
    ) {}

    /**
     * @param Collection<CompanyMetric> $companyMetrics
     */
    public function convertToContainer(Collection $companyMetrics)
    {
        $data = [];
        foreach ($this->metrics->getAll() as $metric) {
            $itemContainer = new FinancialStatementItemContainer();

            $itemContainer->name = $metric->name;
            $itemContainer->formatter = $metric->formatter;
            $itemContainer->shouldHighlightNegativeValue = $metric->shouldHighlightNegativeValue;
            $itemContainer->isInverted = false;
            $itemContainer->data = [];

            $formatter = $this->formatterFactory->create($metric->formatter);

            foreach ($companyMetrics as $companyMetric) {
                $value = $companyMetric->{$metric->slug};

                $itemContainer->data[$companyMetric->year] =
                    FinancialStatementValueContainer::create(
                        $value,
                        $formatter->format($value),
                        $this->getClasses($metric, $value)
                    );
            }

            $data[Str::camel($metric->slug)] = $itemContainer->toArray();
        }

        return MetricsContainer::from($data);
    }

    /**
     * @return array<string>
     */
    private function getClasses(MetricContainer $metric, ?float $value): array
    {
        /** @phpstan-ignore-next-line */
        return match ($metric->shouldHighlightNegativeValue && $value < 0) {
            true => ['negative'],
            false => []
        };
    }
}
