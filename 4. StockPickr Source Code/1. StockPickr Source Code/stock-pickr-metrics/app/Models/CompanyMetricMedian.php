<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\CompanyMetricYearly
 *
 * @property int $id
 * @property string $ticker
 * @property int $year
 * @property float $debt_to_capital
 * @property float $current_ratio
 * @property float $quick_ratio
 * @property float $cash_to_debt
 * @property float|null $interest_to_operating_profit Akkor lehet NULL, ha interest bevétel van, és nincs operating profit
 * @property float $long_term_debt_to_ebitda
 * @property float|null $interest_coverage_ratio Akkor lehet NULL, ha interest bevétel van, és negatív ebit. Akkor lehet negatív, ha interest kiadás van és negatív ebit.
 * @property float $debt_to_assets
 * @property float $operating_cash_flow_to_current_liabilities
 * @property float $capex_as_percent_of_revenue
 * @property float $capex_as_percent_of_operating_cash_flow
 * @property float|null $payout_ratio Akkor lehet NULL, ha osztalékot nem fizető cégről van szó
 * @property float $roic
 * @property float $croic
 * @property float $rota
 * @property float $roa
 * @property float $roe
 * @property float $free_cash_flow_to_revenue
 * @property float $net_margin
 * @property float $operating_margin
 * @property float $gross_margin
 * @property float $operating_cash_flow_margin
 * @property float $sga_to_gross_profit
 * @property float|null $eps_growth
 * @property float|null $net_income_growth
 * @property float|null $total_revenue_growth
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereCapexAsPercentOfOperatingCashFlow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereCapexAsPercentOfRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereCashToDebt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereCroic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereCurrentRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereDebtToAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereDebtToCapital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereEpsGrowth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereFreeCashFlowToRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereGrossMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereInterestCoverageRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereInterestToOperatingProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereLongTermDebtToEbitda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereNetIncomeGrowth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereNetMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereOperatingCashFlowMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereOperatingCashFlowToCurrentLiabilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereOperatingMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric wherePayoutRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereQuickRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereRoa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereRoe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereRoic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereRota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereSgaToGrossProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereTotalRevenueGrowth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereYear($value)
 * @mixin \Eloquent
 * @property int $company_id
 * @property-read \App\Models\Company $company
 * @method static \Database\Factories\CompanyMetricFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMetric whereCompanyId($value)
 */
class CompanyMetricMedian extends Model
{
    use HasFactory;
}
