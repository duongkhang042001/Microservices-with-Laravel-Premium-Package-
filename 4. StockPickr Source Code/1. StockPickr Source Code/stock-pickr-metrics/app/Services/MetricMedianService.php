<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyMetricMedian;
use App\Repositories\CompanyMetricMedianRepository;
use App\Repositories\MetricRepository;
use Illuminate\Support\Collection;
use StockPickr\Common\Containers\Metrics\MetricsSummaryContainer;
use Str;

final class MetricMedianService
{
    public function __construct(
        private CompanyMetricMedianRepository $companyMetricMedians,
        private MetricRepository $metrics
    ) {}

    public function upsert(Company $company): CompanyMetricMedian
    {
        $container = MetricsSummaryContainer::from($this->getMedians(
            collect($company->metrics)  // Database Collection -> Support
        ));

        $metricMedian = $this->companyMetricMedians->getFirstOrNew($company);
        foreach ($container->toArray() as $column => $value) {
            $metricMedian->{Str::snake($column)} = $value;
        }

        $metricMedian->save();
        return $metricMedian;
    }

    public function getMediansForAllCompany(): MetricsSummaryContainer {
        return MetricsSummaryContainer::from($this->getMedians(
            $this->companyMetricMedians->getAll()
        ));
    }

    public function getMediansForSector(string $sector): MetricsSummaryContainer {
        return MetricsSummaryContainer::from($this->getMedians(
            $this->companyMetricMedians->getBySector($sector)
        ));
    }

    public function getMediansForCompany(
        Company $company
    ): MetricsSummaryContainer {
        $companyMetricMedian = $this->companyMetricMedians
            ->getByCompany($company);

        $medians = collect(array_keys($companyMetricMedian->getAttributes()))
            ->mapWithKeys(fn (string $column) => [
                Str::camel($column) => $companyMetricMedian->{$column}
            ])
            ->all();

        return MetricsSummaryContainer::from($medians);
    }

    /**
     * @param Collection<CompanyMetricMedian> $companyMetrics
     */
    private function getMedians(
        Collection $companyMetrics
    ): array {
        $columns = array_keys($companyMetrics->first()->getAttributes());
        return collect($columns)
            ->reject(fn (string $column) => in_array($column, ['id', 'ticker', 'year', 'updated_at', 'created_at']))
            ->mapWithKeys(fn (string $column) => [
                Str::camel($column) => $companyMetrics->median($column)
            ])
            ->all();
    }
}
