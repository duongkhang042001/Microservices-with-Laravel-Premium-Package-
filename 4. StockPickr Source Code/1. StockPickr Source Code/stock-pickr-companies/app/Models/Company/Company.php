<?php

namespace App\Models\Company;

use App\Models\FinancialStatement\BalanceSheet;
use App\Models\FinancialStatement\CashFlow;
use App\Models\Model;
use App\Models\FinancialStatement\IncomeStatement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use StockPickr\Common\Formatters\Number;

/**
 * App\Models\Company\Company
 *
 * @property int $id
 * @property string|null $name
 * @property string $ticker
 * @property int $sector_id
 * @property string|null $industry
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $description
 * @property int|null $employees
 * @property string|null $ceo
 * @property-read \Illuminate\Database\Eloquent\Collection|BalanceSheet[] $balance_sheets
 * @property-read int|null $balance_sheets_count
 * @property-read \Illuminate\Database\Eloquent\Collection|CashFlow[] $cash_flows
 * @property-read int|null $cash_flows_count
 * @property-read mixed $current_percentile_formatted
 * @property-read mixed $employees_formatted
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection|IncomeStatement[] $income_statements
 * @property-read int|null $income_statements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Company\CompanyPeer[] $peers
 * @property-read int|null $peers_count
 * @property-read \App\Models\Company\Sector $sector
 * @method static \Database\Factories\Company\CompanyFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereSectorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Company extends Model
{
    use HasFactory;

    protected $appends = ['current_percentile_formatted', 'employees_formatted', 'full_name'];

    public static function boot()
    {
        parent::boot();
        self::deleting(function (Company $company) {
            $company->peers->each->delete();
            $company->income_statements->each->delete();
            $company->balance_sheets->each->delete();
            $company->cash_flows->each->delete();
        });
    }

    public function getRouteKeyName()
    {
        return 'ticker';
    }

    // -------- Attributes --------

    public function getEmployeesFormattedAttribute()
    {
        return Number::create(['decimals' => 0])->format($this->employees);
    }

    public function getFullNameAttribute()
    {
        return $this->ticker . ' - ' . $this->name;
    }

    // -------- Relations ---------

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function peers()
    {
        return $this->hasMany(CompanyPeer::class, 'company_id', 'id');
    }

    public function income_statements()
    {
        return $this->hasMany(IncomeStatement::class, 'company_id', 'id');
    }

    public function balance_sheets()
    {
        return $this->hasMany(BalanceSheet::class, 'company_id', 'id');
    }

    public function cash_flows()
    {
        return $this->hasMany(CashFlow::class, 'company_id', 'id');
    }
}
