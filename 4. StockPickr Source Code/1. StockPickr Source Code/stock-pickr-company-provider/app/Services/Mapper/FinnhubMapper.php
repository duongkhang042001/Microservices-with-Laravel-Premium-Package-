<?php

namespace App\Services\Mapper;

use App\Services\Mapper\MarketDataProviderMapper;
use Illuminate\Support\Arr;
use App\Support\Str;

class FinnhubMapper extends MarketDataProviderMapper
{
    public function mapCompanyData(array $data): array
    {
        $name = $this->mapCompanyName($data['name']);

        return [
            'name'          => $name,
            'ticker'        => $data['ticker'],
            'description'   => $data['description'],
            'industry'      => $data['gind'],
            'sector'        => $data['gsector'],
            'employees'     => $data['employeeTotal'],
            'ceo'           => $data['ceo']
        ];
    }

    public function mapShareData(array $companyData, ?float $price, ?float $beta): array
    {
        return [
            'price'             => $price,
            'marketCap'         => Arr::get($companyData, 'marketCapitalization'),
            'beta'              => $beta,
            'sharesOutstanding' => Arr::get($companyData, 'shareOutstanding')
        ];
    }

    public function mapCeo(array $data): ?string
    {
        $ceo = collect(Arr::get($data, 'executive', []))
            ->filter(fn (array $executive) =>
                Str::containsSome(Arr::get($executive, 'position', ''), ['Chief Executive Officer', 'Chief Executive', 'CEO', 'ceo'])
            )
            ->first();

        return Arr::get($ceo, 'name');
    }

    public function mapAnalystData(array $priceTarget, array $recommendation): array
    {
        $recommendation = $this->mapRecommendation($recommendation);

        return [
            'priceTarget' => [
                'high'      => Arr::get($priceTarget, 'targetHigh'),
                'average'   => Arr::get($priceTarget, 'targetMean'),
                'low'       => Arr::get($priceTarget, 'targetLow'),
            ],
            'rating' => [
                'buy'   => $recommendation['buy'],
                'hold'  => $recommendation['hold'],
                'sell'  => $recommendation['sell'],
                'date'  => $recommendation['ratingDate']
            ]
        ];
    }

    protected function mapRecommendation(array $recommendation): array
    {
        $mappedRecommendation = [];
        $mappedRecommendation['buy'] = Arr::get($recommendation, 'buy', 0)
            + Arr::get($recommendation, 'strongBuy', 0);

        $mappedRecommendation['sell'] = Arr::get($recommendation, 'sell', 0)
            + Arr::get($recommendation, 'strongSell', 0);

        $mappedRecommendation['ratingDate'] = Arr::get($recommendation, 'period');
        $mappedRecommendation['hold'] = Arr::get($recommendation, 'hold', 0);

        return $mappedRecommendation;
    }

    protected function balanceSheetMap(): array
    {
        return [
            'totalAssets'                               => 'totalAssets',
            'totalCurrentAssets'                        => 'currentAssets',
            'totalCurrentLiabilities'                   => 'currentLiabilities',
            'totalEquity'                               => 'totalEquity',
            'longTermDebt'                              => 'totalLongTermDebt',
            'currentPortionOfLongTermDebt'              => 'currentPortionLongTermDebt',
            'shortTermInvestments'                      => 'shortTermInvestments',
            'currentCash'                               => ['cash', 'cashEquivalents', fn ($a, $b) => $a ?: $b],
            'cash'                                      => 'cashShortTermInvestments',
            'tradeaccountsReceivableCurrent'            => 'accountsReceivables',
            'netIntangibleAssets'                       => 'intangiblesAssets',
            'tangibleAssets'                            => ['totalAssets', 'intangiblesAssets', fn ($a, $b) => $a - $b],
            'totalLiabalities'                          => 'totalLiabilities'
        ];
    }

    protected function incomeStatementMap(): array
    {
        return [
            'totalRevenue'                              => 'revenue',
            'costOfRevenue'                             => 'costOfGoodsSold',
            'grossProfit'                               => 'grossIncome',
            'researchAndDevelopment'                    => 'researchDevelopment',
            'sga'                                       => 'sgaExpense',
            'operatingIncome'                           => ['grossIncome', 'totalOperatingExpense', 'ebit', fn ($grossIncome, $expense, $ebit) => $grossIncome ? $grossIncome - $expense : $ebit],
            'incomeTax'                                 => 'provisionforIncomeTaxes',
            'pretaxIncome'                              => 'pretaxIncome',
            'netIncome'                                 => 'netIncome',
            'ebit'                                      => 'ebit',
            'eps'                                       => 'dilutedEPS',
            'interestExpense'                           => 'interestIncomeExpense'
        ];
    }

    protected function cashFlowMap(): array
    {
        return [
            'netIncome'                                 => 'netIncomeStartingLine',
            'depreciationAmortization'                  => 'depreciationAmortization',
            'cashDividendsPaid'                         => 'cashDividendsPaid',
            'operatingCashFlow'                         => 'netOperatingCashFlow',
            'capex'                                     => 'capex',
            'cashFromInvesting'                         => 'netInvestingCashFlow',
            'cashFromFinancing'                         => 'netCashFinancingActivities',
            'freeCashFlow'                              => ['netOperatingCashFlow', 'capex']
        ];
    }

    protected function mapCompanyName(string $name): string
    {
        $nameParts = explode(' ', $name);
        $lastIndex = count($nameParts) - 1;

        if (Str::lower($nameParts[$lastIndex]) === 'ord') {
            $nameParts[$lastIndex] = 'Inc';
        }

        return Str::upper(implode(' ', $nameParts));
    }

    public function mapStocks(array $stocks): array
    {
        return collect($stocks)
            ->filter(fn (array $stock) => Arr::get($stock, 'type') === 'Common Stock')
            ->map(function (array $stock) {
                $stock['mic'] = Str::lower(Arr::get($stock, 'mic', 'otc'));
                return $stock;
            })
            ->reject(fn (array $stock) => Str::contains($stock['mic'], 'otc'))
            ->reject(fn (array $stock) => Str::contains($stock['displaySymbol'], '.'))
            ->pluck('displaySymbol')
            ->values()
            ->all();
    }

    /**
     * Visszaadja a statementhez tartozó évet, amikor azt kibocsájtották
     * @param array $yearlyItem
     * @return string
     */
    protected function getReportYear(array $yearlyItem): string
    {
        return Arr::get($yearlyItem, $this->getReportYearKey());
    }

    /**
     * Visszaadja, hogy milyen számmal kell elosztani az API -ból kapott értékeket. Ha pl egy API eredeti számokat
     * közöl ez 1 000 000 lesz, tehát milliós nagyságrendben tároljuk, így  4,5 milliárdból lesz 4 500
     * @return int
     */
    protected function getDollarUnitDivider(): int
    {
        return 1;
    }

    /**
     * Visszaadja azt az indexet, ami az API -ból kapott adatok közt az évet jelöli
     * @return string
     */
    protected function getReportYearKey(): string
    {
        return 'year';
    }
}
