<?php

namespace App\Http\Resources;

use App\Repositories\MetricRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use StockPickr\Common\Formatters\Number;
use StockPickr\Common\Formatters\Percent;
use Str;

class MetricsResource extends JsonResource
{
    /**
     * Container -ből dolgozik
     */
    public function toArray($request)
    {
        /** @var MetricRepository */
        $metricsRepo = resolve(MetricRepository::class);
        $metrics = $metricsRepo->getAll();

        return [
            'debtToCapital' => [
                'value' => Percent::create()->format($this->debtToCapital),
                'name' => $metrics->firstWhere('slug', Str::snake('debtToCapital'))->name,
            ],
            'currentRatio' => [
                'value' => Number::create()->format($this->currentRatio),
                'name' => $metrics->firstWhere('slug', Str::snake('currentRatio'))->name,
            ],
            'quickRatio' => [
                'value' => Number::create()->format($this->quickRatio),
                'name' => $metrics->firstWhere('slug', Str::snake('quickRatio'))->name,
            ],
            'cashToDebt' => [
                'value' => Percent::create()->format($this->cashToDebt),
                'name' => $metrics->firstWhere('slug', Str::snake('cashToDebt'))->name,
            ],
            'interestToOperatingProfit' => [
                'value' => Percent::create()->format($this->interestToOperatingProfit),
                'name' => $metrics->firstWhere('slug', Str::snake('interestToOperatingProfit'))->name,
            ],
            'longTermDebtToEbitda' => [
                'value' => Number::create()->format($this->longTermDebtToEbitda),
                'name' => $metrics->firstWhere('slug', Str::snake('longTermDebtToEbitda'))->name,
            ],
            'interestCoverageRatio' => [
                'value' => Number::create()->format($this->interestCoverageRatio),
                'name' => $metrics->firstWhere('slug', Str::snake('interestCoverageRatio'))->name,
            ],
            'debtToAssets' => [
                'value' => Percent::create()->format($this->debtToAssets),
                'name' => $metrics->firstWhere('slug', Str::snake('debtToAssets'))->name,
            ],
            'operatingCashFlowToCurrentLiabilities' => [
                'value' => Percent::create()->format($this->operatingCashFlowToCurrentLiabilities),
                'name' => $metrics->firstWhere('slug', Str::snake('operatingCashFlowToCurrentLiabilities'))->name,
            ],
            'capexAsPercentOfRevenue' => [
                'value' => Percent::create()->format($this->capexAsPercentOfRevenue),
                'name' => $metrics->firstWhere('slug', Str::snake('capexAsPercentOfRevenue'))->name,
            ],
            'capexAsPercentOfOperatingCashFlow' => [
                'value' => Percent::create()->format($this->capexAsPercentOfOperatingCashFlow),
                'name' => $metrics->firstWhere('slug', Str::snake('capexAsPercentOfOperatingCashFlow'))->name,
            ],
            'payoutRatio' => [
                'value' => Percent::create()->format($this->payoutRatio),
                'name' => $metrics->firstWhere('slug', Str::snake('payoutRatio'))->name,
            ],
            'roic' => [
                'value' => Percent::create()->format($this->roic),
                'name' => $metrics->firstWhere('slug', Str::snake('roic'))->name,
            ],
            'croic' => [
                'value' => Percent::create()->format($this->croic),
                'name' => $metrics->firstWhere('slug', Str::snake('croic'))->name,
            ],
            'rota' => [
                'value' => Percent::create()->format($this->rota),
                'name' => $metrics->firstWhere('slug', Str::snake('rota'))->name,
            ],
            'roa' => [
                'value' => Percent::create()->format($this->roa),
                'name' => $metrics->firstWhere('slug', Str::snake('roa'))->name,
            ],
            'roe' => [
                'value' => Percent::create()->format($this->roe),
                'name' => $metrics->firstWhere('slug', Str::snake('roe'))->name,
            ],
            'freeCashFlowToRevenue' => [
                'value' => Percent::create()->format($this->freeCashFlowToRevenue),
                'name' => $metrics->firstWhere('slug', Str::snake('freeCashFlowToRevenue'))->name,
            ],
            'netMargin' => [
                'value' => Percent::create()->format($this->netMargin),
                'name' => $metrics->firstWhere('slug', Str::snake('netMargin'))->name,
            ],
            'operatingMargin' => [
                'value' => Percent::create()->format($this->operatingMargin),
                'name' => $metrics->firstWhere('slug', Str::snake('operatingMargin'))->name,
            ],
            'grossMargin' => [
                'value' => Percent::create()->format($this->grossMargin),
                'name' => $metrics->firstWhere('slug', Str::snake('grossMargin'))->name,
            ],
            'operatingCashFlowMargin' => [
                'value' => Percent::create()->format($this->operatingCashFlowMargin),
                'name' => $metrics->firstWhere('slug', Str::snake('operatingCashFlowMargin'))->name,
            ],
            'sgaToGrossProfit' => [
                'value' => Percent::create()->format($this->sgaToGrossProfit),
                'name' => $metrics->firstWhere('slug', Str::snake('sgaToGrossProfit'))->name,
            ],
            'epsGrowth' => [
                'value' => Percent::create()->format($this->epsGrowth),
                'name' => $metrics->firstWhere('slug', Str::snake('epsGrowth'))->name,
            ],
            'netIncomeGrowth' => [
                'value' => Percent::create()->format($this->netIncomeGrowth),
                'name' => $metrics->firstWhere('slug', Str::snake('netIncomeGrowth'))->name,
            ],
            'totalRevenueGrowth' => [
                'value' => Percent::create()->format($this->totalRevenueGrowth),
                'name' => $metrics->firstWhere('slug', Str::snake('totalRevenueGrowth'))->name,
            ],
        ];
    }
}
