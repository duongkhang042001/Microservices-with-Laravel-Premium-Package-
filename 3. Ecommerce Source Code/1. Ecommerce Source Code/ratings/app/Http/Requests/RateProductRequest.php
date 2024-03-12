<?php

namespace App\Http\Requests;

class RateProductRequest extends ApiFormRequest
{
    public function getRating(): float
    {
        return $this->rating;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function rules()
    {
        return [
            'rating' => 'required|numeric|in:1,1.5,2,2.5,3,3.5,4,4.5,5'
        ];
    }
}
