<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use StockPickr\Common\Formatters\Percent;

class ScoreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'debtToCapital' => $this->score->debt_to_capital,
            'currentRatio' => $this->score->current_ratio,
            'quickRatio' => $this->score->quick_ratio,
            'cashToDebt' => $this->score->cash_to_debt,
            'interestToOperatingProfit' => $this->score->interest_to_operating_profit,
            'longTermDebtToEbitda' => $this->score->long_term_debt_to_ebitda,
            'interestCoverageRatio' => $this->score->interest_coverage_ratio,
            'debtToAssets' => $this->score->debt_to_assets,
            'operatingCashFlowToCurrentLiabilities' => $this->score->operating_cash_flow_to_current_liabilities,
            'capexAsPercentOfRevenue' => $this->score->capex_as_percent_of_revenue,
            'capexAsPercentOfOperatingCashFlow' => $this->score->capex_as_percent_of_operating_cash_flow,
            'payoutRatio' => $this->score->payout_ratio,
            'roic' => $this->score->roic,
            'croic' => $this->score->croic,
            'rota' => $this->score->rota,
            'roa' => $this->score->roa,
            'roe' => $this->score->roe,
            'freeCashFlowToRevenue' => $this->score->free_cash_flow_to_revenue,
            'netMargin' => $this->score->net_margin,
            'operatingMargin' => $this->score->operating_margin,
            'grossMargin' => $this->score->gross_margin,
            'operatingCashFlowMargin' => $this->score->operating_cash_flow_margin,
            'sgaToGrossProfit' => $this->score->sga_to_gross_profit,
            'epsGrowth' => $this->score->eps_growth,
            'netIncomeGrowth' => $this->score->net_income_growth,
            'totalRevenueGrowth' => $this->score->total_revenue_growth,
            'summary' => [
                'totalScores' => (int) $this->total_scores,
                'totalScorePercent' => Percent::create(['decimals' => 0])->format($this->total_score_percent),
                'maxPossibleScores' => (int) $this->max_possible_scores,
                'position' => $this->position,
                'positionPercentile' => Percent::create(['decimals' => 0])->format($this->position_percentile)
            ]
        ];
    }
}
