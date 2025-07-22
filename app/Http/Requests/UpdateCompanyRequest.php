<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyUuid = $this->route('company');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('companies', 'name')->ignore($companyUuid, 'uuid'),
            ],
            'description' => 'nullable|string',
            'tagline' => 'nullable|string|max:255',
            'website_url' => 'nullable|url:http,https|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'country_code' => 'nullable|string|max:2',
            'city' => 'nullable|string|max:255',
        ];
    }
} 