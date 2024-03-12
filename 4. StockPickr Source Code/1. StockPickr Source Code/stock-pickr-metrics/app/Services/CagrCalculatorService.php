<?php

namespace App\Services;

use Exception;
use StockPickr\Common\Containers\CompanyUpsertedContainer;

final class CagrCalculatorService
{
    public function __construct(private CalculatorService $calc)
    {
    }

    public function getCagr(
        float $beginningValue,
        float $endingValue,
        int $numberOfYears = 1
    ): ?float {
        // -10 -> 10 ilyenkor nem lehet értelmes értéket számolni
        if ($beginningValue <= 0) {
            return null;
        }

        // 10 -> -10 ilyenkor nem lehet értelmes értéket számolni
        if ($endingValue < 0) {
            return null;
        }

        return pow(
            $this->calc->divide($endingValue, $beginningValue),
            1 / $numberOfYears
        ) - 1;
    }

    public function getOneYearCagrForMetric(
        CompanyUpsertedContainer $company,
        int $year,
        string $metricSlug
    ): ?float {
        $endingValue = $company
            ->getIncomeStatements()->getRawValue($metricSlug, $year);

        try {
            $beginningValue = $company
                ->getIncomeStatements()->getRawValue($metricSlug, $year - 1);
        } catch (Exception) {
            // Ez azt jelenti, hogy az előző évben nincs adat, tehát
            // a vizsgált évben nincs növekedés, mert ez az első év
            // amikor van adat
            return null;
        }

        return $this->getCagr($beginningValue, $endingValue);
    }
}
