<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => (int) $this->id,
            'ticker'                => (string) $this->ticker,
            'name'                  => (string) $this->name,
            'fullName'              => (string) $this->full_name,
            'description'           => (string) $this->description,
            'industry'              => (string) $this->industry,
            'ceo'                   => (string) $this->ceo,
            'employees'             => (string) $this->employees_formatted,
            'sector'                => new SectorResource($this->sector),
            'peers'                 => $this->when($this->peers, fn () => PeerResource::collection($this->peers)),
        ];
    }
}
