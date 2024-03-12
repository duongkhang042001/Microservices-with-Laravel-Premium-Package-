<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Chart
 *
 * @property int $id
 * @property string $ticker
 * @property string $chart
 * @property array $data
 * @property array $years
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ChartFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereChart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chart whereYears($value)
 * @mixin \Eloquent
 */
class Chart extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'years' => 'json',
        'data'  => 'json'
    ];

    public function getData(): array
    {
        return $this->data;
    }

    public function getYears(): array
    {
        return $this->years;
    }
}
