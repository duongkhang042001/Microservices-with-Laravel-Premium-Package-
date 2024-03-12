<?php

namespace App\Services;

use App\Containers\MetricContainer;
use App\Models\Company;
use App\Repositories\CompanyMetricRepository;
use App\Repositories\MetricRepository;
use Illuminate\Support\Str;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\Metrics\MetricsContainer;

final class MetricService
{
    /**
     * Akkor van használva, ha egy mutatóhoz 0 valamelyik érték, ezért az
     * "végtelen" lenne. A 0 nem hibát, vagy hiányzó értéket jelent, hanem
     * normál esetet, pl egy hitelmentes cég esetén x / 100 osztás eredményét
     * "végtelen" értére állítja
     */
    public const INFINITE_VALUE = 100;

    public function __construct(
        private MetricRepository $metrics,
        private CompanyMetricRepository $companyMetrics,
        private CalculatorService $calc,
        private CagrCalculatorService $cagrCalculator,
        private MetricContainerService $metricsContainerService
    ) {
    }

    public function upsert(
        Company $company,
        CompanyUpsertedContainer $companyContainer
    ): MetricsContainer {
        // Egyelőre nem csak a kapott, hanem az összes évet kitörli, így
        // minden cégről 5 évnyi adat van
        $this->companyMetrics->deleteAllForCompany($company->ticker);
        $companyMetrics = collect([]);

        foreach ($this->getYearsWithData($companyContainer) as $year) {
            $metrics = $this->getAllMetricValue($companyContainer, $year);
            $companyMetrics[] = $this->companyMetrics
                ->create($company, $year, $metrics);
        }

        return $this->metricsContainerService
            ->convertToContainer($companyMetrics);
    }

    public function getAllForCompany(string $ticker): MetricsContainer
    {
        return $this->metricsContainerService->convertToContainer(
            $this->companyMetrics->getAllForCompany($ticker)
        );
    }

    public function getDebtToCapital(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $totalDebt = $this->getTotalLongTermDebt($company, $year);
        $equity = $company->getBalanceSheets()
            ->getRawValue('totalEquity', $year);

        // @phpstan-ignore-next-line
        return match ($equity <= 0) {
            true => 1,
            false => $this->calc->divide($totalDebt, $totalDebt + $equity, 0),
        };
    }

    public function getCurrentRatio(
        CompanyUpsertedContainer $company,
        int $year
    ): ?float {
        $currentAssets = $company->getBalanceSheets()
            ->getRawValue('totalCurrentAssets', $year);

        $currentLiabilities = $company->getBalanceSheets()
            ->getRawValue('totalCurrentLiabilities', $year);

        return $this->calc->divide(
            $currentAssets,
            $currentLiabilities,
            self::INFINITE_VALUE
        );
    }

    public function getQuickRatio(
        CompanyUpsertedContainer $company,
        int $year
    ): ?float {
        $accounts = $company->getBalanceSheets()
            ->getRawValue('currentAccountAndReceivables', $year);

        $cash = $company->getBalanceSheets()->getRawValue('cash', $year);
        $cashAndAccounts = $cash + $accounts;
        $currentLiabilities = $company->getBalanceSheets()
            ->getRawValue('totalCurrentLiabilities', $year);

        return $this->calc->divide(
            $cashAndAccounts,
            $currentLiabilities,
            self::INFINITE_VALUE
        );
    }

    public function getCashToDebt(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $cash = $company->getBalanceSheets()->getRawValue('cash', $year);
        $totalDebt = $this->getTotalLongTermDebt($company, $year);

        return $this->calc->divide($cash, $totalDebt, 1);
    }

    public function getInterestToOperatingProfit(
        CompanyUpsertedContainer $company,
        int $year
    ): ?float {
        // Ez itt net interest expense, tehát (expense + income)
        $interest = $company->getIncomeStatements()
            ->getRawValue('interestExpense', $year);

        $operatingProfit = $company->getIncomeStatements()
            ->getRawValue('operatingIncome', $year);

        $cases = [
            ['interest' => $this->calc->zeroOrMore(), 'operating' => $this->calc->negative(),   'result' => null],
            ['interest' => $this->calc->zeroOrMore(), 'operating' => $this->calc->zeroOrMore(), 'result' => 0],
            ['interest' => $this->calc->any(),        'operating' => $this->calc->zeroOrLess(), 'result' => self::INFINITE_VALUE],
            ['interest' => $this->calc->any(),        'operating' => $this->calc->any(),        'result' => $this->calc->divide(abs($interest), $operatingProfit, 1)],
        ];

        $case = collect($cases)
            ->filter(fn (array $case) => $case['interest']($interest) && $case['operating']($operatingProfit))
            ->first();

        return $case['result'];
    }

    public function getLongTermDebtToEbitda(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $totalLongTermDebt = $this->getTotalLongTermDebt($company, $year);
        $ebitda = $this->getEbitda($company, $year);

        if ($totalLongTermDebt === 0.0) {
            return 0;
        }

        if ($ebitda <= 0) {
            return self::INFINITE_VALUE;
        }

        return $this->calc->divide($totalLongTermDebt, $ebitda);
    }

    public function getInterestCoverageRatio(
        CompanyUpsertedContainer $company,
        int $year
    ): ?float {
        $ebit = $company->getIncomeStatements()->getRawValue('ebit', $year);
        $interest = $company->getIncomeStatements()
            ->getRawValue('interestExpense', $year);

        $cases = [
            ['interest' => $this->calc->positive(), 'ebit' => $this->calc->positive(), 'coverage' => self::INFINITE_VALUE],
            ['interest' => $this->calc->positive(), 'ebit' => $this->calc->negative(), 'coverage' => null],
            ['interest' => $this->calc->any(),      'ebit' => $this->calc->any(),      'coverage' => $this->calc->divide($ebit, abs($interest))],
        ];

        $case = collect($cases)
            ->filter(fn (array $case) => $case['interest']($interest) && $case['ebit']($ebit))
            ->first();

        return $case['coverage'];
    }

    public function getDebtToAssets(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $totalDebt = $this->getTotalLongTermDebt($company, $year);
        $totalAssets = $company
            ->getBalanceSheets()->getRawValue('totalAssets', $year);

        return $this->calc->divide($totalDebt, $totalAssets, 0);
    }

    public function getOperatingCashFlowToCurrentLiabilities(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $operatingCashFlow = $company
            ->getCashFlows()->getRawValue('operatingCashFlow', $year);
        $currentLiabilities = $company
            ->getBalanceSheets()->getRawValue('totalCurrentLiabilities', $year);

        $cases = [
            ['ocf' => $this->calc->negative(), 'libailities' => $this->calc->any(),  'result' => 0],
            ['ocf' => $this->calc->any(),      'libailities' => $this->calc->zero(), 'result' => self::INFINITE_VALUE],
            ['ocf' => $this->calc->any(),      'libailities' => $this->calc->any(),  'result' => $this->calc->divide($operatingCashFlow, $currentLiabilities)],
        ];

        $case = collect($cases)
            ->filter(fn (array $case) => $case['ocf']($operatingCashFlow) && $case['libailities']($currentLiabilities))
            ->first();

        return $case['result'];
    }

    public function getCapexAsPercentOfRevenue(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $capex = abs($company->getCashFlows()->getRawValue('capex', $year));
        $revenue = $company
            ->getIncomeStatements()->getRawValue('totalRevenue', $year);

        return $this->calc->divide($capex, $revenue);
    }

    public function getCapexAsPercentOfOperatingCashFlow(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $capex = abs($company->getCashFlows()->getRawValue('capex', $year));
        $cashFlow = $company
            ->getCashFlows()->getRawValue('operatingCashFlow', $year);

        // @phpstan-ignore-next-line
        return match ($capex !== 0.0 && $cashFlow < 0) {
            true => self::INFINITE_VALUE,
            false => $this->calc->divide($capex, $cashFlow, 0),
        };
    }

    public function getPayoutRatio(
        CompanyUpsertedContainer $company,
        int $year
    ): ?float {
        $dividend = abs($company
            ->getCashFlows()->getRawValue('cashDividendPaid', $year));
        $fcf = $company->getCashFlows()->getRawValue('freeCashFlow', $year);

        if ($dividend === 0.0) {
            return null;
        }

        if ($fcf <= 0) {
            return 1;
        }

        return $this->calc->divide($dividend, $fcf);
    }

    public function getRoic(CompanyUpsertedContainer $company, int $year): float
    {
        $operatingIncome = $company
            ->getIncomeStatements()->getRawValue('operatingIncome', $year);
        $investedCapital = $this->getInvestedCapital($company, $year);
        $taxRate = $this->getTaxRate($company, $year);

        return $this->calc->divide(
            $operatingIncome * (1 - $taxRate),
            $investedCapital,
            0
        );
    }

    public function getRota(CompanyUpsertedContainer $company, int $year): float
    {
        $netIncome = $company
            ->getIncomeStatements()->getRawValue('netIncome', $year);
        $tangibleAssets = $company
            ->getBalanceSheets()->getRawValue('tangibleAssets', $year);

        return $this->calc->divide($netIncome, $tangibleAssets);
    }

    public function getRoa(CompanyUpsertedContainer $company, int $year): float
    {
        $netIncome = $company
            ->getIncomeStatements()->getRawValue('netIncome', $year);
        $totalAssets = $company
            ->getBalanceSheets()->getRawValue('totalAssets', $year);

        return $this->calc->divide($netIncome, $totalAssets);
    }

    public function getRoe(CompanyUpsertedContainer $company, int $year): float
    {
        $netIncome = $company
            ->getIncomeStatements()->getRawValue('netIncome', $year);
        $totalEquity = $company
            ->getBalanceSheets()->getRawValue('totalEquity', $year);

        return $this->calc->divide($netIncome, $totalEquity);
    }

    public function getCroic(CompanyUpsertedContainer $company, int $year): float
    {
        $freeCashFlow = $company
            ->getCashFlows()->getRawValue('freeCashFlow', $year);
        $investedCapital = $this->getInvestedCapital($company, $year);

        return $this->calc->divide($freeCashFlow, $investedCapital);
    }

    public function getFreeCashFlowToRevenue(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $freeCashFlow = $company
            ->getCashFlows()->getRawValue('freeCashFlow', $year);

        $revenue = $company
            ->getIncomeStatements()->getRawValue('totalRevenue', $year);

        return $this->calc->divide($freeCashFlow, $revenue);
    }

    public function getNetMargin(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $netIncome = $company
            ->getIncomeStatements()->getRawValue('netIncome', $year);

        $revenue = $company
            ->getIncomeStatements()->getRawValue('totalRevenue', $year);

        return $this->calc->divide($netIncome, $revenue);
    }

    public function getOperatingMargin(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $operatingIncome = $company
            ->getIncomeStatements()->getRawValue('operatingIncome', $year);

        $revenue = $company
            ->getIncomeStatements()->getRawValue('totalRevenue', $year);

        return $this->calc->divide($operatingIncome, $revenue);
    }

    public function getGrossMargin(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $grossProfit = $company
            ->getIncomeStatements()->getRawValue('grossProfit', $year);

        $revenue = $company
            ->getIncomeStatements()->getRawValue('totalRevenue', $year);

        return $this->calc->divide($grossProfit, $revenue);
    }

    public function getOperatingCashFlowMargin(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $operatingCashFlow = $company
            ->getCashFlows()->getRawValue('operatingCashFlow', $year);

        $revenue = $company
            ->getIncomeStatements()->getRawValue('totalRevenue', $year);

        return $this->calc->divide($operatingCashFlow, $revenue);
    }

    public function getSgaToGrossProfit(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $sga = $company
            ->getIncomeStatements()
            ->getRawValue('sellingGeneralAdministrative', $year);

        $grossProfit = $company
            ->getIncomeStatements()->getRawValue('grossProfit', $year);

        // @phpstan-ignore-next-line
        return match ($grossProfit <= 0) {
            true => 1,
            false => $this->calc->divide($sga, $grossProfit),
        };
    }

    public function getEpsGrowth(
        CompanyUpsertedContainer $company,
        int $year
    ): ?float {
        return $this->cagrCalculator
            ->getOneYearCagrForMetric($company, $year, 'eps');
    }

    public function getNetIncomeGrowth(
        CompanyUpsertedContainer $company,
        int $year
    ): ?float {
        return $this->cagrCalculator
            ->getOneYearCagrForMetric($company, $year, 'netIncome');
    }

    public function getTotalRevenueGrowth(
        CompanyUpsertedContainer $company,
        int $year
    ): ?float {
        return $this->cagrCalculator
            ->getOneYearCagrForMetric($company, $year, 'totalRevenue');
    }

    private function getInvestedCapital(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $totalDebt = $this->getTotalLongTermDebt($company, $year);
        $cash = $company->getBalanceSheets()->getRawValue('cash', $year);
        $equity = $company
            ->getBalanceSheets()->getRawValue('totalEquity', $year);

        return $totalDebt + $equity + $cash;
    }

    private function getTaxRate(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $incomeTax = $company
            ->getIncomeStatements()->getRawValue('incomeTax', $year);
        $pretaxIncome = $company
            ->getIncomeStatements()->getRawValue('pretaxIncome', $year);

        return $this->calc->divide($incomeTax, $pretaxIncome);
    }

    private function getTotalLongTermDebt(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $longTermDebt = $company->getBalanceSheets()
            ->getRawValue('longTermDebt', $year);

        $currentDebt = $company->getBalanceSheets()
            ->getRawValue('currentPortionOfLongTermDebt', $year);

        return $longTermDebt + $currentDebt;
    }

    private function getEbitda(
        CompanyUpsertedContainer $company,
        int $year
    ): float {
        $ebit = $company->getIncomeStatements()->getRawValue('ebit', $year);
        $da = $company->getCashFlows()
            ->getRawValue('deprecationAmortization', $year);

        return $ebit + $da;
    }

    /**
     * @return array<int>
     */
    private function getYearsWithData(CompanyUpsertedContainer $company): array
    {
        return array_keys($company->getIncomeStatements()->totalRevenue->data);
    }

    /**
     * @return array<string, ?float> 'debt_to_capital' => 0.45
     */
    private function getAllMetricValue(
        CompanyUpsertedContainer $company,
        int $year
    ): array {
        return $this->metrics->getAll()
            ->map(fn (MetricContainer $metric) => [
                'fn' => 'get' . Str::ucfirst(Str::camel($metric->slug)),
                'metric' => $metric,
            ])
            ->filter(fn (array $data) => method_exists(self::class, $data['fn']))
            ->mapWithKeys(fn (array $data) => [
                    $data['metric']->slug => $this->{$data['fn']}(
                        $company,
                        $year
                    ),
            ])
            ->all();
    }
}
