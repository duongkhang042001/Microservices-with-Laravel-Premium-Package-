<?php

namespace App\Models;

use Ramsey\Uuid\Uuid as RamseyUuid;

trait Uuid
{
    public static function bootUuid(): void
    {
        static::creating(function (self $model): void {
            $model->id = RamseyUuid::uuid4()->toString();
        });
    }
}
