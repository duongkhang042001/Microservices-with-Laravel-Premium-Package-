<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShareData
 *
 * @property int $id
 * @property string $ticker
 * @property float $price
 * @property float $market_cap In million
 * @property float|null $shares_outstanding In million
 * @property float|null $beta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ShareDataFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData whereBeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData whereMarketCap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData whereSharesOutstanding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShareData whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShareData extends Model
{
    use HasFactory;

    public $table = 'share_data';
    protected $guarded = [];

    public function setSharesOutstandingAttribute(?float $value): void
    {
        if ($value !== null) {
            $this->attributes['shares_outstanding'] = $value;
        }
    }

    public function setBetaAttribute(?float $value): void
    {
        if ($value !== null) {
            $this->attributes['beta'] = $value;
        }
    }
}
