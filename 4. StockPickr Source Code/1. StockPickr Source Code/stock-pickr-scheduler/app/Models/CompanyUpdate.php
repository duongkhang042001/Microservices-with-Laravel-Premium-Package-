<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompanyUpdate
 *
 * @property int $id
 * @property string $ticker
 * @property \Illuminate\Support\Carbon|null $market_data_updated_at
 * @property \Illuminate\Support\Carbon|null $financials_updated_at
 * @method static \Database\Factories\CompanyUpdateFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereFinancialsUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereMarketDataUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUpdate whereTicker($value)
 * @mixin \Eloquent
 */
class CompanyUpdate extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'market_data_updated_at'    => 'datetime',
        'financials_updated_at'     => 'datetime'
    ];
}
