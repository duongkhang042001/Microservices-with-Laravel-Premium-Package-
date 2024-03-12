<?php

namespace App\Services\FinancialStatement;

use App\Containers\IncomingFinancialStatementContainer;
use App\Models\Company\Company;
use App\Models\FinancialStatement\BalanceSheet;
use App\Models\FinancialStatement\CashFlow;
use App\Models\FinancialStatement\FinancialStatement;
use App\Models\FinancialStatement\IncomeStatement;
use App\Repositories\FinancialStatement\FinancialStatementRepository;
use App\Repositories\FinancialStatement\Factory\FinancialStatementRepositoryFactory;
use App\Repositories\FinancialStatement\IncomeStatementRepository;
use Illuminate\Support\Collection;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementContainer;

class FinancialStatementService
{
    const TYPE_BALANCE_SHEET = 'balance-sheet';
    const TYPE_INCOME_STATEMENT = 'income-statement';
    const TYPE_CASH_FLOW = 'cash-flow';

    public function __construct(
        private IncomeStatementRepository $incomeStatements,
        private FinancialStatementRepositoryFactory $repositoryFactory,
        private FinancialStatementContainerFactoryService $containerFactory,
        private FinancialStatementFormatterService $financialStatementFormatter
    ) {
    }

    public function save(
        Company $company,
        IncomingFinancialStatementContainer $statement,
        string $table,
        array $skippedYears = []
    ) {
        foreach ($statement->data as $year => $data) {
            if (in_array($year, $skippedYears)) {
                continue;
            }

            $repo = $this->repositoryFactory->create($table);
            $repo->save($company, $year, $data);
        }
    }

    /**
     * Visszaadja az összes olyan item nevet, ami megtalálható valamelyik financial statement -ben
     * @return Collection
     */
    public function getAvailableItems(): Collection
    {
        $incomeItems = collect((new IncomeStatement())->getTableColumns());
        $incomeItems = $this->mapItemsToTable($incomeItems, 'income_statements');

        $balanceSheetItems = collect((new BalanceSheet())->getTableColumns());
        $balanceSheetItems = $this->mapItemsToTable($balanceSheetItems, 'balance_sheets');

        $cashFlowItems = collect((new CashFlow())->getTableColumns());
        $cashFlowItems = $this->mapItemsToTable($cashFlowItems, 'cash_flows');

        return $incomeItems
            ->merge($balanceSheetItems)
            ->merge($cashFlowItems);
    }

    public function getSummary(Company $company, FinancialStatement $model): FinancialStatementContainer
    {
        $container = $this->containerFactory->createContainer($model, $company);
        $this->financialStatementFormatter->formatContainer($container);

        return $container;
    }

    public function createRepository(string $table): FinancialStatementRepository
    {
        return $this->repositoryFactory->create($table);
    }

    public function getYearsWhereHasData(Company $company)
    {
        return $this->incomeStatements->findYearsWhereHasData($company);
    }

    protected function mapItemsToTable(Collection $items, string $table): Collection
    {
        return $items->mapWithKeys(function (string $item) use ($table) {
            return [
                $item => $table,
            ];
        });
    }
}
