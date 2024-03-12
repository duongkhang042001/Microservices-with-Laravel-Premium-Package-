<?php

namespace StockPickr\Common\Containers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class UpsertCompanyContainer extends Container
{
    public string $ticker;
    public string $name;
    public string $sector;

    public ?string $description;
    public ?string $industry;
    public ?int $employees;
    public ?string $ceo;
    public Collection $peers;

    /**
     * Assoc array indexed by years
     * ['incomeStatements' => ['2020' => []]]
     */
    public array $financialStatements;

    public static function from(array $data): static
    {
        $container = new static();
        $container->ticker = $data['ticker'];
        $container->name = $data['name'];
        $container->sector = $data['sector'];

        $container->description = Arr::get($data, 'description');
        $container->industry = Arr::get($data, 'industry');

        $employees = Arr::get($data, 'employees');
        $container->employees = is_numeric($employees)
            ? (int) $employees
            : null;

        $container->ceo = Arr::get($data, 'ceo');

        $container->financialStatements = [
            'incomeStatements' => $data['financialStatements']['incomeStatements'],
            'balanceSheets' => $data['financialStatements']['balanceSheets'],
            'cashFlows' => $data['financialStatements']['cashFlows']
        ];

        $container->peers = collect(Arr::get($data, 'peers', []));
        return $container;
    }

    public function getIncomeStatements(): array
    {
        return $this->financialStatements['incomeStatements'];
    }

    public function getBalanceSheets(): array
    {
        return $this->financialStatements['balanceSheets'];
    }

    public function getCashFlows(): array
    {
        return $this->financialStatements['cashFlows'];
    }
}