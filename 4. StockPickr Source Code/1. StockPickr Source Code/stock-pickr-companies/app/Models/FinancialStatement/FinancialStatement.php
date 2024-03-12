<?php

namespace App\Models\FinancialStatement;

use App\Models\Model;

/**
 * App\Models\FinancialStatement\FinancialStatement
 *
 * @property int $id
 * @property string $ticker
 * @property string $item
 * @property string $year
 * @property float $value
 * @property int $company_id
 * @property int|null $financial_statement_item_id
 * @property-read \App\Models\FinancialStatement\FinancialStatementItem|null $financial_statement_item
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialStatement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialStatement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialStatement query()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialStatement whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialStatement whereFinancialStatementItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialStatement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialStatement whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialStatement whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialStatement whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialStatement whereYear($value)
 * @mixin \Eloquent
 */
abstract class FinancialStatement extends Model
{
    public $timestamps = false;

    abstract public function getValueBySlug(string $slug): ?float;

    public function getYear(): int
    {
        return $this->year;
    }
}
