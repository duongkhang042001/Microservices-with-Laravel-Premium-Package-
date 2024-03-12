<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchCompanyRequest extends FormRequest
{
    public function getSearchTerm(): string
    {
        return $this->q;
    }

    public function rules()
    {
        return [
            'q' => 'required|string',
        ];
    }
}
