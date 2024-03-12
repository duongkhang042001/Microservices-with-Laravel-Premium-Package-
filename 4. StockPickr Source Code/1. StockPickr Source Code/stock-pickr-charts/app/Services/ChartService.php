<?php

namespace App\Services;

use App\ChartConfigs\ChartConfig;
use App\ChartConfigs\ChartFactory;
use App\Models\Company;
use App\Repositories\ChartRepository;
use Illuminate\Support\Facades\DB;

class ChartService
{
    const TYPE_BAR = 'bar';
    const TYPE_LINE = 'line';

    const NORMALIZER_FINANCIAL_NUMBER = 'financial-number';
    const NORMALIZER_MONEY = 'money';
    const NORMALIZER_PERCENT = 'percent';

    public function __construct(
        private ChartRepository $charts,
        private ChartFactory $chartFactory
    ) {}

    public function upsert(Company $company): void
    {
        DB::transaction(function () use ($company) {
            $this->charts->deleteAll($company);
            $this->charts->createAll($company, $this->chartFactory->createGroup('all'));
        });
    }

    /**
     * @return array[string]array
     */
    public function getChartGroup(string $ticker, string $group): array
    {
        $chartConfigs = $this->chartFactory->createGroup($group);
        $group = [];

        foreach ($chartConfigs as $chartConfig) {
            $group[$chartConfig->slug()] = $this->getData($ticker, $chartConfig);
        }

        return $group;
    }

    /**
     * @return array{data: array, config: array, normalizer: string, type: string}
     */
    private function getData(string $ticker, ChartConfig $chartConfig): array
    {
        $chart = $this->charts->get($ticker, $chartConfig);

        return [
            'data' => [
                'labels'    => $chart->getYears(),
                'datasets'  => $chart->getData()
            ],
            'config'        => $chartConfig->config(),
            'type'          => $chartConfig->type(),
            'normalizer'    => $chartConfig->normalizer()
        ];
    }
}
