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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CompanyMetric|null $metric
 * @property-read \App\Models\CompanyScore|null $score
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
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $max_possible_scores
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CompanyMetric[] $metrics
 * @property-read int|null $metrics_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CompanyScore[] $scores
 * @property-read int|null $scores_count
 * @property-read \App\Models\CompanySectorScore|null $sector_score
 * @property int|null $total_sector_scores
 * @property float|null $total_sector_score_percent
 * @property-read int $max_possible_sector_scores
 * @property-read \App\Models\CompanyMetricMedian|null $metric_median
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTotalSectorScorePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTotalSectorScores($value)
 */
class Company extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::deleting(function (Company $company) {
            $company->metrics->each->delete();
            $company->score?->delete();
            $company->sector_score?->delete();
            $company->metric_median?->delete();
        });
    }

    public function getRouteKeyName()
    {
        return 'ticker';
    }

    public function metrics()
    {
        return $this->hasMany(CompanyMetric::class);
    }

    public function score()
    {
        return $this->hasOne(CompanyScore::class);
    }

    public function sector_score()
    {
        return $this->hasOne(CompanySectorScore::class);
    }

    public function metric_median()
    {
        return $this->hasOne(CompanyMetricMedian::class);
    }

    public function getMaxPossibleScoresAttribute(): int
    {
        return $this->total_score_percent == 0
            ? 0
            : (int) ceil($this->total_scores / $this->total_score_percent);
    }

    public function getMaxPossibleSectorScoresAttribute(): int
    {
        return $this->total_sector_score_percent == 0
            ? 0
            : (int) ceil($this->total_sector_scores / $this->total_sector_score_percent);
    }
}
