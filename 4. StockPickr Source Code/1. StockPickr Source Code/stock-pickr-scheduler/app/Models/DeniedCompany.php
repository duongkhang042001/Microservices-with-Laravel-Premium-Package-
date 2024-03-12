<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DeniedCompany
 *
 * @property int $id
 * @property string $ticker
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\DeniedCompanyFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|DeniedCompany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeniedCompany newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeniedCompany query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeniedCompany whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeniedCompany whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeniedCompany whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeniedCompany whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeniedCompany whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeniedCompany extends Model
{
    use HasFactory;

    protected $guarded = [];
}
