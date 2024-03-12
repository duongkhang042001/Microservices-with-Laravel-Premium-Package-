<?php

namespace App\Services\FinancialStatement;

use App\Models\Company\Company;
use App\Models\FinancialStatement\BalanceSheet;
use App\Models\FinancialStatement\CashFlow;
use App\Models\FinancialStatement\FinancialStatement;
use App\Models\FinancialStatement\IncomeStatement;
use App\Repositories\FinancialStatement\BalanceSheetRepository;
use App\Repositories\FinancialStatement\CashFlowRepository;
use App\Repositories\FinancialStatement\FinancialStatementRepository;
use App\Repositories\FinancialStatement\IncomeStatementRepository;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use StockPickr\Common\Containers\Company\FinancialStatement\BalanceSheet\BalanceSheetContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\CashFlow\CashFlowContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementItemContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementValueContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\IncomeStatement\IncomeStatementContainer;

class FinancialStatementContainerFactoryService
{
    private const TYPE_INCOME_STATEMENT = 'income-statement';
    private const TYPE_BALANCE_SHEET = 'balance-sheet';
    private const TYPE_CASH_FLOW = 'cash-flow';

    public function __construct(
        private FinancialStatementFormatterService $financialStatementFormatter
    ) {
    }

    public function createContainer(FinancialStatement $statement, Company $company): FinancialStatementContainer
    {
        return match (get_class($statement)) {
            IncomeStatement::class => $this->createIncomeStatementContainer($company),
            BalanceSheet::class => $this->createBalanceSheetContainer($company),
            CashFlow::class => $this->createCashFlowContainer($company),
            default => new InvalidArgumentException('No container found for class: ' . get_class($statement))
        };
    }

    public function createIncomeStatementContainer(Company $company): IncomeStatementContainer
    {
        $container = new IncomeStatementContainer();

        $container->totalRevenue = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'totalRevenue', $company->income_statements);
        $container->costOfRevenue = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'costOfRevenue', $company->income_statements);
        $container->grossProfit = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'grossProfit', $company->income_statements);
        $container->operatingIncome = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'operatingIncome', $company->income_statements);
        $container->pretaxIncome = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'pretaxIncome', $company->income_statements);
        $container->incomeTax = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'incomeTax', $company->income_statements);
        $container->interestExpense = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'interestExpense', $company->income_statements);
        $container->researchAndDevelopment = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'researchAndDevelopment', $company->income_statements);
        $container->sellingGeneralAdministrative = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'sellingGeneralAdministrative', $company->income_statements);
        $container->netIncome = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'netIncome', $company->income_statements);
        $container->ebit = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'ebit', $company->income_statements);
        $container->eps = $this->createStatementItemContainer(self::TYPE_INCOME_STATEMENT, 'eps', $company->income_statements);

        $this->financialStatementFormatter->formatContainer($container);
        return $container;
    }

    public function createBalanceSheetContainer(Company $company): BalanceSheetContainer
    {
        $container = new BalanceSheetContainer();

        $container->cash = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'cash', $company->balance_sheets);
        $container->currentCash = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'currentCash', $company->balance_sheets);
        $container->totalCurrentAssets = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'totalCurrentAssets', $company->balance_sheets);
        $container->netIntangibleAssets = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'netIntangibleAssets', $company->balance_sheets);
        $container->tangibleAssets = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'tangibleAssets', $company->balance_sheets);
        $container->shortTermInvestments = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'shortTermInvestments', $company->balance_sheets);
        $container->currentAccountAndReceivables = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'currentAccountAndReceivables', $company->balance_sheets);
        $container->totalAssets = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'totalAssets', $company->balance_sheets);
        $container->totalEquity = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'totalEquity', $company->balance_sheets);
        $container->currentPortionOfLongTermDebt = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'currentPortionOfLongTermDebt', $company->balance_sheets);
        $container->totalCurrentLiabilities = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'totalCurrentLiabilities', $company->balance_sheets);
        $container->longTermDebt = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'longTermDebt', $company->balance_sheets);
        $container->totalLiabilities = $this->createStatementItemContainer(self::TYPE_BALANCE_SHEET, 'totalLiabilities', $company->balance_sheets);

        $this->financialStatementFormatter->formatContainer($container);
        return $container;
    }

    public function createCashFlowContainer(Company $company): CashFlowContainer
    {
        $container = new CashFlowContainer();

        $container->netIncome = $this->createStatementItemContainer(self::TYPE_CASH_FLOW, 'netIncome', $company->cash_flows);
        $container->operatingCashFlow = $this->createStatementItemContainer(self::TYPE_CASH_FLOW, 'operatingCashFlow', $company->cash_flows);
        $container->capex = $this->createStatementItemContainer(self::TYPE_CASH_FLOW, 'capex', $company->cash_flows);
        $container->cashDividendPaid = $this->createStatementItemContainer(self::TYPE_CASH_FLOW, 'cashDividendPaid', $company->cash_flows);
        $container->deprecationAmortization = $this->createStatementItemContainer(self::TYPE_CASH_FLOW, 'deprecationAmortization', $company->cash_flows);
        $container->freeCashFlow = $this->createStatementItemContainer(self::TYPE_CASH_FLOW, 'freeCashFlow', $company->cash_flows);
        $container->cashFromFinancing = $this->createStatementItemContainer(self::TYPE_CASH_FLOW, 'cashFromFinancing', $company->cash_flows);
        $container->cashFromInvesting = $this->createStatementItemContainer(self::TYPE_CASH_FLOW, 'cashFromInvesting', $company->cash_flows);

        $this->financialStatementFormatter->formatContainer($container);
        return $container;
    }

    /**
     * @param Collection<FinancialStatement> $statements
     */
    private function createStatementItemContainer(
        string $type,
        string $slug,
        Collection $statements
    ): FinancialStatementItemContainer {
        $container = new FinancialStatementItemContainer();
        $repository = $this->getRepository($type);
        $attributes = $repository->getAttributes();

        $container->formatter = $attributes[$slug]['formatter'];
        $container->name = $attributes[$slug]['name'];
        $container->shouldHighlightNegativeValue = $attributes[$slug]['shouldHighlightNegativeValue'];
        $container->isInverted = $attributes[$slug]['isInverted'];
        $container->data = [];

        foreach ($statements as $statement) {
            /** @var FinancialStatement $statement */
            $container->data[$statement->getYear()] = FinancialStatementValueContainer::create(
                $statement->getValueBySlug($slug),
                null
            );
        }

        return $container;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function getRepository(string $type): FinancialStatementRepository
    {
        return match ($type) {
            self::TYPE_INCOME_STATEMENT => new IncomeStatementRepository(),
            self::TYPE_BALANCE_SHEET => new BalanceSheetRepository(),
            self::TYPE_CASH_FLOW => new CashFlowRepository(),
            default => new InvalidArgumentException('Statement repository not found for type: ' . $type)
        };
    }
}
