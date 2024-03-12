<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PeerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'ticker' => (string) $this->ticker,
        ];
    }
}
