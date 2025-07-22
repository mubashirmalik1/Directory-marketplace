<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateServiceCategoryRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $categoryUuid = $this->route('serviceCategory')->uuid;

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('service_categories', 'name')->ignore($categoryUuid, 'uuid'),
            ],
            'parent_uuid' => ['nullable', 'string', 'exists:service_categories,uuid'],
        ];
    }
} 