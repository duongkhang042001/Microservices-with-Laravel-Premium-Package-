<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Company
 *
 * @property int $id
 * @property string $ticker
 * @property string $sector
 * @property int|null $position
 * @property float|null $position_percentile
 * @property int|null $total_scores
 * @property float|null $total_score_percent
 * @property int|null $total_sector_scores
 * @property float|null $total_sector_score_percent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CompanyFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company wherePositionPercentile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereSector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTotalScorePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTotalScores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTotalSectorScorePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTotalSectorScores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Company extends Model
{
    use HasFactory;

    public function getFullNameAttribute(): string
    {
        return $this->ticker . ' - ' . $this->name;
    }
}
