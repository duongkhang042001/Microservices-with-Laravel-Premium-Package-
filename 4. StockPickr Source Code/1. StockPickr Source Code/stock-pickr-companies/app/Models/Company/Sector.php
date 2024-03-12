<?php

namespace App\Models\Company;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Company\Sector
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $slug
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Company\Company[] $companies
 * @property-read int|null $companies_count
 * @method static \Database\Factories\Company\SectorFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sector extends Model
{
    use HasFactory;
}
