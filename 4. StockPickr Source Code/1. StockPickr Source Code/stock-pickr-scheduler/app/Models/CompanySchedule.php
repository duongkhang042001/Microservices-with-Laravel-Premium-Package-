<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompanySchedule
 *
 * @property string $id
 * @property string $event
 * @property string|null $ticker
 * @property string $started_at
 * @property string|null $finished_at
 * @property string $state
 * @property array|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CompanyScheduleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompanySchedule extends Model
{
    use HasFactory, Uuid;

    // public $incrementing = false;
    protected $guarded = [];
    // protected $keyType = 'string';
    protected $primaryKey = 'pid';

    protected $casts = [
        'payload' => 'array'
    ];
}
