<?php

namespace App\Services\Mapper;

use App\Services\ValueCalculatorService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class MarketDataProviderMapper
 * @author Joó Martin
 *
 * MarketDataProvider API -tól kapott nyers adatokat mappeli saját struktúrára. Ez azt jelenti, hogy
 * amit egy API provider pl 'currentLongTermDebt' küld, abból 'currentPortionOfLong...' lesz
 */
abstract class MarketDataProviderMapper
{
    public function __construct(
        protected ValueCalculatorService $valueCalculator
    ) {}

    /**
     * @param array{name: string, ticker: string, description: string, gind: string, gsector: string, employeeTotal: int, ceo: string} $data
     * @return array{name: string, ticker: string, description: string, industry: string, sector: string, employees: int, ceo: string}
     */
    abstract public function mapCompanyData(array $data): array;
    /**
     * @param array{marketCapitalization: float, shareOutstanding: float} $companyData
     * @return array{price: float, marketCap: float, beta: float, sharesOutstanding: float}
     */
    abstract public function mapShareData(array $companyData, ?float $price, ?float $beta): array;
    /**
     * @param array{targetHigh: float, targetMean: float, targetLow: float} $priceTarget
     * @param array{buy: int, hold: int, sell: int, ratingDate: string} $recommendation
     * @return array{priceTarget: array{high: float, average: float, low: float}, rating: array{buy: int, hold: int, sell: int, date: string}}
     */
    abstract public function mapAnalystData(array $priceTarget, array $recommendation): array;
    /**
     * @param array{type: string, mic: string, displaySymbol: string} $stocks
     * @return string[]
     */
    abstract public function mapStocks(array $stocks): array;
    /**
     * @param array{executive: array{position: string, name: string}} $data
     */
    abstract public function mapCeo(array $data): ?string;

    /**
     * @return array{
     *   totalAssets: string,
     *   totalCurrentAssets: string,
     *   totalCurrentLiabilities: string,
     *   totalEquity: string,
     *   longTermDebt: string,
     *   currentPortionOfLongTermDebt: string,
     *   shortTermInvestments: string,
     *   currentCash: array,
     *   cash: string,
     *   tradeaccountsReceivableCurrent: string,
     *   netIntangibleAssets: string,
     *   tangibleAssets: array,
     *   totalLiabalities: string
     * }
     */
    abstract protected function balanceSheetMap(): array;
    /**
     * @return array{
     *   totalRevenue: string,
     *   costOfRevenue: string,
     *   grossProfit: string,
     *   researchAndDevelopment: string,
     *   sga: string,
     *   operatingIncome: array,
     *   incomeTax: string,
     *   pretaxIncome: string,
     *   netIncome: string,
     *   ebit: string,
     *   eps: string,
     *   interestExpense: string
     * }
     */
    abstract protected function incomeStatementMap(): array;
    /**
     * @return array{
     *   netIncome: string,
     *   depreciationAmortization: string,
     *   cashDividendsPaid: string,
     *   operatingCashFlow: string,
     *   capex: string,
     *   cashFromInvesting: string,
     *   cashFromFinancing: string,
     *   freeCashFlow: array
     * }
     */
    abstract protected function cashFlowMap(): array;

    /**
     * Visszaadja a statementhez tartozó évet, amikor azt kibocsájtották
     * @param array{year: string}  $yearlyItem     Egy év adatai (benne az évvel)
     * @return string
     */
    abstract protected function getReportYear(array $yearlyItem): string;

    /**
     * Visszaadja azt az indexet, ami az API -ból kapott adatok közt az évet jelöli
     * @return string
     */
    abstract protected function getReportYearKey(): string;

    /**
     * Visszaadja, hogy milyen számmal kell elosztani az API -ból kapott értékeket. Ha pl egy API eredeti számokat
     * közöl ez 1 000 000 lesz, tehát milliós nagyságrendben tároljuk, így  4,5 milliárdból lesz 4 500
     * @return int
     */
    abstract protected function getDollarUnitDivider(): int;

    /**
     * @param array $data       API -tól kapott teljes adathalmaz (pl egy Income statementek 4 évre vonatkozóan)
     * @param string $type
     * @return array
     * @throws \Exception
     */
    public function mapStatement(array $data, string $type): array
    {
        return collect($data)
            ->map(function (array $yearlyItem) {
                return $this->normalize($yearlyItem);
            })
            ->mapWithKeys(function (array $normalizedItem) use ($type) {
                $mappedItem = [];
                foreach ($this->getStatementMap($type) as $key => $apiKey) {
                    $mappedItem[$key] = $this->parseItem($normalizedItem, $apiKey);
                }

                return [(string)$this->getReportYear($normalizedItem) => $mappedItem];
            })->all();
    }

    protected function getStatementMap(string $type): array
    {
        switch ($type) {
            case 'balanceSheet':
                return $this->balanceSheetMap();
            case 'incomeStatement':
                return $this->incomeStatementMap();
            case 'cashFlow':
                return $this->cashFlowMap();
            default: throw new Exception('Unknown statement type ' . $type);
        }
    }

    protected function normalize(array $data)
    {
        return collect($data)
            ->map(function ($value) {
                return (is_null($value) || !is_numeric($value))
                    ? 0
                    : $value;
            })
            ->mapWithKeys(function ($value, $key) {
                return $key == $this->getReportYearKey()
                    ? [$key => $value]
                    : [$key => $this->valueCalculator->divide($value, $this->getDollarUnitDivider(), 0)];
            })->all();
    }

    /**
     * API egy elemét (pl total assets) dolgozza fel mapperben lévő érték alapján
     *
     * @param array $item               API -ból kapott adathalmaz egy konkrét eleme (pl 2020 vonatkozó total assets)
     * @param string|array $apiKey
     * @return float
     */
    protected function parseItem(array $item, $apiKey)
    {
        if (is_array($apiKey)) {
            return $this->parseArrayItem($item, $apiKey);
        }

        return Arr::get($item, $apiKey, 0);
    }

    /**
     * Ha a map -ben az api index helyén array szerepel az azt jelenti, hogy valamilyen function
     * alapján fel kell dolgozni több elemet, pl össze kell adni őket. Ha az utolsó elem
     * callable, akkor custom function van egyébként összeadás a default.
     *
     * @param array $item
     * @param array $apiKeys
     * @return mixed
     */
    protected function parseArrayItem(array $item, array $apiKeys)
    {
        /** @var Collection $apiKeys */
        $apiKeys = collect($apiKeys);
        $lastItem = $apiKeys->last();

        if (is_callable($lastItem)) {
            $apiKeys->pop();

            $values = $apiKeys
                ->map(fn (string $key) => Arr::get($item, $key));

            return $lastItem(...$values);
        }

        return collect($apiKeys)->reduce(function (int $carry, string $key) use ($item) {
            return $carry + Arr::get($item, $key, 0);
        }, 0);
    }
}
