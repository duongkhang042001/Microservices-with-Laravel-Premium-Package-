<?php

namespace App\Http\Requests;

use App\Enums\CompanyLogActions;
use Illuminate\Validation\Rule;

class StoreCompanyLogRequest extends ApiFormRequest
{
    public function getAction(): string
    {
        return $this->action;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function rules()
    {
        return [
            'action' => [
                'required',
                Rule::in([CompanyLogActions::VIEW, CompanyLogActions::SEARCH])
            ],
            'payload' => 'required'
        ];
    }
}
