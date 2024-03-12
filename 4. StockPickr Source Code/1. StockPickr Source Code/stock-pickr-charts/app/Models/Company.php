<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Company
 *
 * @property int $id
 * @property string $ticker
 * @property array|null $financial_statements
 * @property array|null $metrics
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Chart[] $charts
 * @property-read int|null $charts_count
 * @method static \Database\Factories\CompanyFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereFinancialStatements($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereMetrics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Company extends Model
{
    use HasFactory;

    protected $casts = [
        'financial_statements' => 'array',
        'metrics'  => 'array'
    ];

    public static function boot()
    {
        parent::boot();
        self::deleting(function (Company $company) {
            $company->charts->each->delete();
        });
    }

    public function charts()
    {
        return $this->hasMany(Chart::class);
    }

    public function getIncomeStatements(): array
    {
        return $this->financial_statements['incomeStatements'];
    }

    public function getBalanceSheets(): array
    {
        return $this->financial_statements['balanceSheets'];
    }

    public function getCashFlows(): array
    {
        return $this->financial_statements['cashFlows'];
    }
}
