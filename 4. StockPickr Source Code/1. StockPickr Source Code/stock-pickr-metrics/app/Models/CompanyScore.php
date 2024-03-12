<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\CompanyScore
 *
 * @property int $id
 * @property string $ticker
 * @property int $company_id
 * @property int|null $debt_to_capital
 * @property int|null $current_ratio
 * @property int|null $quick_ratio
 * @property int|null $cash_to_debt
 * @property int|null $interest_to_operating_profit
 * @property int|null $long_term_debt_to_ebitda
 * @property int|null $interest_coverage_ratio
 * @property int|null $debt_to_assets
 * @property int|null $operating_cash_flow_to_current_liabilities
 * @property int|null $capex_as_percent_of_revenue
 * @property int|null $capex_as_percent_of_operating_cash_flow
 * @property int|null $payout_ratio
 * @property int|null $roic
 * @property int|null $croic
 * @property int|null $rota
 * @property int|null $roa
 * @property int|null $roe
 * @property int|null $free_cash_flow_to_revenue
 * @property int|null $net_margin
 * @property int|null $operating_margin
 * @property int|null $gross_margin
 * @property int|null $operating_cash_flow_margin
 * @property int|null $sga_to_gross_profit
 * @property int|null $eps_growth
 * @property int|null $net_income_growth
 * @property int|null $total_revenue_growth
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @method static \Database\Factories\CompanyScoreFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereCapexAsPercentOfOperatingCashFlow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereCapexAsPercentOfRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereCashToDebt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereCroic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereCurrentRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereDebtToAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereDebtToCapital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereEpsGrowth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereFreeCashFlowToRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereGrossMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereInterestCoverageRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereInterestToOperatingProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereLongTermDebtToEbitda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereNetIncomeGrowth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereNetMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereOperatingCashFlowMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereOperatingCashFlowToCurrentLiabilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereOperatingMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore wherePayoutRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereQuickRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereRoa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereRoe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereRoic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereRota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereSgaToGrossProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereTotalRevenueGrowth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyScore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompanyScore extends Model
{
    use HasFactory;
}
