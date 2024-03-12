<?php

namespace App\Repositories\Company;

use App\Models\Company\Sector;
use Illuminate\Support\Str;

class SectorRepository
{
    public function findByNameOrCreate(string $name): Sector
    {
        $sector = Sector::where('name', $name)
            ->limit(1)
            ->first();

        if (! $sector) {
            $sector = Sector::create([
                'name'  => $name,
                'slug'  => Str::slug($name)
            ]);
        }

        return $sector;
    }
}
