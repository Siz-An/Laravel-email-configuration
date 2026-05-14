<?php

namespace Sizan\EmailConfiguration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexEmailConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $max = max(1, (int) config('email-configuration.per_page_max', 100));

        return [
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
            'type' => ['sometimes', 'nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'nullable', 'boolean'],
            'per_page' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:'.$max],
        ];
    }
}
