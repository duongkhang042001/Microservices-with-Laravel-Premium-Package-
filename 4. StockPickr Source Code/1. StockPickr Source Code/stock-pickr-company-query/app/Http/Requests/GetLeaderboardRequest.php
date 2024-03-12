<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetLeaderboardRequest extends FormRequest
{
    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function rules()
    {
        return [
            'limit' => 'required|numeric',
            'offset' => 'required|numeric'
        ];
    }
}
