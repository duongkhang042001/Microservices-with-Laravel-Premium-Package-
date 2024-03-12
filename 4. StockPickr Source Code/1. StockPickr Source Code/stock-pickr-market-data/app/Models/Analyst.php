<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Analyst
 *
 * @property int $id
 * @property string $ticker
 * @property int|null $buy
 * @property int|null $hold
 * @property int|null $sell
 * @property float|null $price_target_low
 * @property float|null $price_target_average
 * @property float|null $price_target_high
 * @property int|null $number_of_analysts
 * @property string|null $rating_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AnalystFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst query()
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst whereBuy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst whereHold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst whereNumberOfAnalysts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst wherePriceTargetAverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst wherePriceTargetHigh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst wherePriceTargetLow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst whereRatingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst whereSell($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analyst whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Analyst extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setPriceTargetLowAttribute(?float $value): void
    {
        if ($value !== null && $value != 0) {
            $this->attributes['price_target_low'] = $value;
        }
    }

    public function setPriceTargetHighAttribute(?float $value): void
    {
        if ($value !== null && $value != 0) {
            $this->attributes['price_target_high'] = $value;
        }
    }
}
