<?php

namespace App\Models\FinancialStatement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use InvalidArgumentException;

/**
 * App\Models\FinancialStatement\BalanceSheet
 *
 * @property int $id
 * @property string $ticker
 * @property string $item
 * @property string $year
 * @property float $value
 * @property int $company_id
 * @property int|null $financial_statement_item_id
 * @property-read \App\Models\FinancialStatement\FinancialStatementItem|null $financial_statement_item
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet query()
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereFinancialStatementItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereYear($value)
 * @mixin \Eloquent
 * @property float|null $cash
 * @property float|null $current_cash
 * @property float|null $total_current_assets
 * @property float|null $net_intangible_assets
 * @property float|null $tangible_assets
 * @property float|null $short_term_investments
 * @property float|null $tradeaccounts_receivable_current
 * @property float|null $total_assets
 * @property float|null $total_equity
 * @property float|null $current_portion_of_long_term_debt
 * @property float|null $total_current_liabilities
 * @property float|null $long_term_debt
 * @property float|null $total_liabalities
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereCurrentCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereCurrentPortionOfLongTermDebt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereLongTermDebt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereNetIntangibleAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereShortTermInvestments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereTangibleAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereTotalAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereTotalCurrentAssets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereTotalCurrentLiabilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereTotalEquity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereTotalLiabalities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceSheet whereTradeaccountsReceivableCurrent($value)
 * @method static \Database\Factories\FinancialStatement\BalanceSheetFactory factory(...$parameters)
 */
class BalanceSheet extends FinancialStatement
{
    use HasFactory;

    public function getValueBySlug(string $slug): ?float
    {
        return match($slug) {
            'cash' => $this->cash,
            'currentCash' => $this->current_cash,
            'totalCurrentAssets' => $this->total_current_assets,
            'netIntangibleAssets' => $this->net_intangible_assets,
            'tangibleAssets' => $this->tangible_assets,
            'shortTermInvestments' => $this->short_term_investments,
            'currentAccountAndReceivables' => $this->tradeaccounts_receivable_current,
            'totalAssets' => $this->total_assets,
            'totalEquity' => $this->total_equity,
            'currentPortionOfLongTermDebt' => $this->current_portion_of_long_term_debt,
            'totalCurrentLiabilities' => $this->total_current_liabilities,
            'longTermDebt' => $this->long_term_debt,
            'totalLiabilities' => $this->total_liabalities,
            default => new InvalidArgumentException('No value found in balance sheet for slug: ' . $slug)
        };
    }
}
