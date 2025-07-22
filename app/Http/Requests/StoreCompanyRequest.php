<?php

namespace App\Http\Requests;

class StoreCompanyRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:companies,name',
            'description' => 'nullable|string',
            'tagline' => 'nullable|string|max:255',
            'website_url' => 'required|url:http,https|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'country_code' => 'required|string|max:2',
            'city' => 'nullable|string|max:255',
        ];
    }
} 