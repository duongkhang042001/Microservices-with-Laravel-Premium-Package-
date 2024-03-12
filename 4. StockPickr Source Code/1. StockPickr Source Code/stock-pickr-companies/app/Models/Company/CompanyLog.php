<?php

namespace App\Models\Company;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Company\CompanyLog
 *
 * @property int $id
 * @property string $action
 * @property string $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompanyLog extends Model
{
    use HasFactory;
}
