<?php

namespace App\Repositories;

use App\ChartConfigs\ChartConfig;
use App\Exceptions\CompanyFinancialNotFound;
use App\Models\Chart;
use App\Models\Company;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementItemContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementValueContainer;

class ChartRepository
{
    /**
     * @param ChartConfig[] $chartConfigs
     */
    public function createAll(Company $company, array $chartConfigs): void
    {
        /** @var ChartConfig $chartConfig */
        foreach ($chartConfigs as $chartConfig) {
            $this->create($company, $chartConfig);
        }
    }

    public function create(Company $company, ChartConfig $chartConfig): Chart
    {
        $data = [];
        $years = [];

        foreach ($chartConfig->financials() as $financial) {
            $statementItemContainer = $this->getFinancialItem($company, $financial);
            $dataByYear = collect($statementItemContainer->data)
                // Az indexek miatt (évek) nem lehet pluck() -ot használni
                ->map(static fn (FinancialStatementValueContainer $value) => $value->rawValue)
                ->sortKeys(SORT_REGULAR, true);

            if ($dataByYear->isEmpty()) {
                throw new CompanyFinancialNotFound('Company financial not found when creating chart data: ' . $financial);
            }

            // Pl growth chart -nál az első elem mindig null
            $itemsWithData = $dataByYear
                ->filter()
                ->sortKeys();

            $data[] = [
                'data'  => $itemsWithData->values(),
                'fill'  => false,
                'label' => $statementItemContainer->name
            ];

            $years[] = $itemsWithData->keys();
        }

        return Chart::create([
            'ticker'        => $company->ticker,
            'company_id'    => $company->id,
            'chart'         => $chartConfig->slug(),
            'data'          => $data,
            'years'         => collect($years)->flatten()->unique()->values()
        ]);
    }

    public function get(string $ticker, ChartConfig $chart): ?Chart
    {
        return Chart::where('ticker', $ticker)
            ->where('chart', $chart->slug())
            ->firstOrFail();
    }

    public function deleteAll(Company $company): void
    {
        Chart::where('ticker', $company->ticker)
            ->delete();
    }

    private function getFinancialItem(Company $company, string $chartFinancial): FinancialStatementItemContainer
    {
        if (in_array($chartFinancial, array_keys($company->getIncomeStatements()))) {
            return FinancialStatementItemContainer::from(
                $company->getIncomeStatements()[$chartFinancial]
            );
        }

        if (in_array($chartFinancial, array_keys($company->getBalanceSheets()))) {
            return FinancialStatementItemContainer::from(
                $company->getBalanceSheets()[$chartFinancial]
            );
        }

        if (in_array($chartFinancial, array_keys($company->getCashFlows()))) {
            return FinancialStatementItemContainer::from(
                $company->getCashFlows()[$chartFinancial]
            );
        }

        return FinancialStatementItemContainer::from(
            $company->metrics[$chartFinancial]
        );
    }
}
