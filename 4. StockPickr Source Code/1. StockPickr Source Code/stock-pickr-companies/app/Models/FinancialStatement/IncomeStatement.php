<?php

namespace App\Models\FinancialStatement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use InvalidArgumentException;

/**
 * App\Models\FinancialStatement\IncomeStatement
 *
 * @property int $id
 * @property string $ticker
 * @property string $item
 * @property string $year
 * @property float $value
 * @property int $company_id
 * @property int|null $financial_statement_item_id
 * @property-read \App\Models\FinancialStatement\FinancialStatementItem|null $financial_statement_item
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement query()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereFinancialStatementItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereYear($value)
 * @mixin \Eloquent
 * @property float|null $total_revenue
 * @property float|null $cost_of_revenue
 * @property float|null $gross_profit
 * @property float|null $operating_income
 * @property float|null $pretax_income
 * @property float|null $income_tax
 * @property float|null $interest_expense
 * @property float|null $research_and_development
 * @property float|null $sga
 * @property float|null $net_income
 * @property float|null $ebit
 * @property float|null $eps
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereCostOfRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereEbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereEps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereGrossProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereIncomeTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereInterestExpense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereNetIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereOperatingIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement wherePretaxIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereResearchAndDevelopment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereSga($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeStatement whereTotalRevenue($value)
 * @method static \Database\Factories\FinancialStatement\IncomeStatementFactory factory(...$parameters)
 */
class IncomeStatement extends FinancialStatement
{
    use HasFactory;

    public function getValueBySlug(string $slug): ?float
    {
        return match($slug) {
            'totalRevenue' => $this->total_revenue,
            'costOfRevenue' => $this->cost_of_revenue,
            'grossProfit' => $this->gross_profit,
            'operatingIncome' => $this->operating_income,
            'pretaxIncome' => $this->pretax_income,
            'incomeTax' => $this->income_tax,
            'interestExpense' => $this->interest_expense,
            'researchAndDevelopment' => $this->research_and_development,
            'sellingGeneralAdministrative' => $this->sga,
            'netIncome' => $this->net_income,
            'ebit' => $this->ebit,
            'eps' => $this->eps,
            default => new InvalidArgumentException('No value found in income statement for slug: ' . $slug)
        };
    }
}
