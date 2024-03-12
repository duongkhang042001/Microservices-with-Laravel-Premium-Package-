<?php

namespace App\Http\Requests;

use Illuminate\Support\Collection;

class GetRatingsRequest extends ApiFormRequest
{
    /**
     * @return Collection<int>
     */
    public function getProductIds(): Collection
    {
        return collect($this->productIds);
    }

    public function rules()
    {
        return [
            'productIds' => 'required|array'
        ];
    }
}
