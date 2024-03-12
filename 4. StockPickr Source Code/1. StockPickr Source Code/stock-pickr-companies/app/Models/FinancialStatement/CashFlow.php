<?php

namespace App\Models\FinancialStatement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use InvalidArgumentException;

/**
 * App\Models\FinancialStatement\CashFlow
 *
 * @property int $id
 * @property string $ticker
 * @property string $item
 * @property string $year
 * @property float $value
 * @property int $company_id
 * @property int|null $financial_statement_item_id
 * @property-read \App\Models\FinancialStatement\FinancialStatementItem|null $financial_statement_item
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereFinancialStatementItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereYear($value)
 * @mixin \Eloquent
 * @property float|null $net_income
 * @property float|null $operating_cash_flow
 * @property float|null $capex
 * @property float|null $cash_dividends_paid
 * @property float|null $depreciation_amortization
 * @property float|null $free_cash_flow
 * @property float|null $cash_from_financing
 * @property float|null $cash_from_investing
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereCapex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereCashDividendsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereCashFromFinancing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereCashFromInvesting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereDepreciationAmortization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereFreeCashFlow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereNetIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereOperatingCashFlow($value)
 * @method static \Database\Factories\FinancialStatement\CashFlowFactory factory(...$parameters)
 */
class CashFlow extends FinancialStatement
{
    use HasFactory;

    public function getValueBySlug(string $slug): ?float
    {
        return match($slug) {
            'netIncome' => $this->net_income,
            'operatingCashFlow' => $this->operating_cash_flow,
            'capex' => $this->capex,
            'cashDividendPaid' => $this->cash_dividends_paid,
            'deprecationAmortization' => $this->depreciation_amortization,
            'freeCashFlow' => $this->free_cash_flow,
            'cashFromFinancing' => $this->cash_from_financing,
            'cashFromInvesting' => $this->cash_from_investing,
            default => new InvalidArgumentException('No value found in cash flow for slug: ' . $slug)
        };
    }
}
