<?php

namespace Tests\Mocks;

use App\Services\Mapper\MarketDataProviderMapper;

class DummyMarketDataProviderMapper extends MarketDataProviderMapper
{
    protected function balanceSheetMap(): array
    {
        return [
            'total_assets'      => 'totalAssets',
            'intangible_assets' => 'intangibleAssets',
            'tangible_assets'   => ['totalAssets', 'intangibleAssets', fn ($a, $b) => $a - $b],

            'total_equity'      => 'totalEquity',
        ];
    }

    protected function incomeStatementMap(): array
    {
        return [
            'total_revenue'     => 'totalRevenue',
            'cost_of_revenue'   => 'costOfRevenue',
            'gross_profit'      => ['totalRevenue', 'costOfRevenue', fn ($a, $b) => $a - $b]
        ];
    }

    protected function cashFlowMap(): array
    {
        return [];
    }

    /**
     * Visszaadja a statementhez tartozó évet, amikor azt kibocsájtották
     * @param array $yearlyItem Egy év adatai (benne az évvel)
     * @return string
     */
    protected function getReportYear(array $yearlyItem): string
    {
        return $yearlyItem['year'];
    }

    /**
     * Visszaadja, hogy milyen számmal kell elosztani az API -ból kapott értékeket. Ha pl egy API eredeti számokat
     * közöl ez 1 000 000 lesz, tehát milliós nagyságrendben tároljuk, így  4,5 milliárdból lesz 4 500
     * @return int
     */
    protected function getDollarUnitDivider(): int
    {
        return 1000000;
    }

    /**
     * Visszaadja azt az indexet, ami az API -ból kapott adatok közt az évet jelöli
     * @return string
     */
    protected function getReportYearKey(): string
    {
        return 'year';
    }

    public function mapCompanyData(array $data): array
    {
        return [];
    }

    public function mapShareData(array $companyData, ?float $price, ?float $beta): array
    {
        return [];
    }

    public function mapAnalystData(array $priceTarget, array $recommendation): array
    {
        return [];
    }

    public function mapStocks(array $stocks): array
    {
        return $stocks;
    }

    public function mapCeo(array $data): ?string
    {
        return 'ceo';
    }

    protected function stockMap(): array
    {
        return [
            'ticker'    => 'symbol'
        ];
    }
}
