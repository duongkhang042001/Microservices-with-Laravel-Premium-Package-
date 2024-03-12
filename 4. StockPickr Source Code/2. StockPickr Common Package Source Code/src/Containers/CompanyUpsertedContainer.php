<?php

namespace StockPickr\Common\Containers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use StockPickr\Common\Containers\Company\FinancialStatement\BalanceSheet\BalanceSheetContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\CashFlow\CashFlowContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\IncomeStatement\IncomeStatementContainer;

final class CompanyUpsertedContainer extends Container
{
    public int $id;
    public string $ticker;
    public string $name;
    public string $fullName;

    public SectorContainer $sector;
    public ?string $industry;

    public ?string $description;
    public ?int $employees;
    public ?string $ceo;
    public Collection $peers;

    public Collection $financials;
    public FinancialStatementsContainer $financialStatements;

    public static function from(array $data): static
    {
        $container = new static();

        $container->id = $data['id'];
        $container->ticker = $data['ticker'];
        $container->name = $data['name'];
        $container->fullName = $data['fullName'];
        $container->sector = SectorContainer::from($data['sector']);

        $container->description = Arr::get($data, 'description');
        $container->industry = Arr::get($data, 'industry');

        $employees = Arr::get($data, 'employees');
        $container->employees = is_numeric($employees)
            ? (int) $employees
            : null;

        $container->ceo = Arr::get($data, 'ceo');        
        $container->peers = collect(Arr::get($data, 'peers', []));

        $container->financials = collect(Arr::get($data, 'financials'));
        $container->financialStatements = self::createFinancialStatements($data);

        return $container;
    }

    public function getIncomeStatements(): IncomeStatementContainer
    {
        return $this->financialStatements->incomeStatements;
    }

    public function getBalanceSheets(): BalanceSheetContainer
    {
        return $this->financialStatements->balanceSheets;
    }

    public function getCashFlows(): CashFlowContainer
    {
        return $this->financialStatements->cashFlows;
    }

    private static function createFinancialStatements(array $data): FinancialStatementsContainer
    {
        $incomeStatements = is_array($data['financialStatements']['incomeStatements']) 
            ? IncomeStatementContainer::from($data['financialStatements']['incomeStatements'])
            : $data['financialStatements']['incomeStatements'];

        $balanceSheets = is_array($data['financialStatements']['balanceSheets']) 
            ? BalanceSheetContainer::from($data['financialStatements']['balanceSheets'])
            : $data['financialStatements']['balanceSheets'];

        $cashFlows = is_array($data['financialStatements']['cashFlows']) 
            ? CashFlowContainer::from($data['financialStatements']['cashFlows'])
            : $data['financialStatements']['cashFlows'];

        return FinancialStatementsContainer::create(            
            $incomeStatements,
            $balanceSheets,
            $cashFlows
        );
    }
}