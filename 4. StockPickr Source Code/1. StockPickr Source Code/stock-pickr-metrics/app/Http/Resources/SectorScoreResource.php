<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use StockPickr\Common\Formatters\Percent;

class SectorScoreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'debtToCapital' => $this->sector_score->debt_to_capital,
            'currentRatio' => $this->sector_score->current_ratio,
            'quickRatio' => $this->sector_score->quick_ratio,
            'cashToDebt' => $this->sector_score->cash_to_debt,
            'interestToOperatingProfit' => $this->sector_score->interest_to_operating_profit,
            'longTermDebtToEbitda' => $this->sector_score->long_term_debt_to_ebitda,
            'interestCoverageRatio' => $this->sector_score->interest_coverage_ratio,
            'debtToAssets' => $this->sector_score->debt_to_assets,
            'operatingCashFlowToCurrentLiabilities' => $this->sector_score->operating_cash_flow_to_current_liabilities,
            'capexAsPercentOfRevenue' => $this->sector_score->capex_as_percent_of_revenue,
            'capexAsPercentOfOperatingCashFlow' => $this->sector_score->capex_as_percent_of_operating_cash_flow,
            'payoutRatio' => $this->sector_score->payout_ratio,
            'roic' => $this->sector_score->roic,
            'croic' => $this->sector_score->croic,
            'rota' => $this->sector_score->rota,
            'roa' => $this->sector_score->roa,
            'roe' => $this->sector_score->roe,
            'freeCashFlowToRevenue' => $this->sector_score->free_cash_flow_to_revenue,
            'netMargin' => $this->sector_score->net_margin,
            'operatingMargin' => $this->sector_score->operating_margin,
            'grossMargin' => $this->sector_score->gross_margin,
            'operatingCashFlowMargin' => $this->sector_score->operating_cash_flow_margin,
            'sgaToGrossProfit' => $this->sector_score->sga_to_gross_profit,
            'epsGrowth' => $this->sector_score->eps_growth,
            'netIncomeGrowth' => $this->sector_score->net_income_growth,
            'totalRevenueGrowth' => $this->sector_score->total_revenue_growth,
            'summary' => [
                'totalScores' => $this->total_sector_scores,
                'totalScorePercent' => Percent::create(['decimals' => 0])->format($this->total_sector_score_percent),
                'maxPossibleScores' => (int) $this->max_possible_sector_scores
            ]
        ];
    }
}
