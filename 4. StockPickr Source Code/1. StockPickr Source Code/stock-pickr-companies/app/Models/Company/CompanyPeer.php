<?php

namespace App\Models\Company;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Company\CompanyPeer
 *
 * @property int $id
 * @property int $company_id
 * @property int $peer_id
 * @property string $ticker
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPeer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPeer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPeer query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPeer whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPeer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPeer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPeer wherePeerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPeer whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPeer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Database\Factories\Company\CompanyPeerFactory factory(...$parameters)
 */
class CompanyPeer extends Model
{
    use HasFactory;
}
