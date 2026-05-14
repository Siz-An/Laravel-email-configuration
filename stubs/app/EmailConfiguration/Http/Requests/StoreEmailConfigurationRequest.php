<?php

namespace App\EmailConfiguration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmailConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $table = config('email-configuration.table', 'email_configurations');

        return [
            'name' => ['required', 'string', Rule::unique($table, 'name')],
            'subject' => ['required', 'string'],
            'slug' => ['required', 'string', Rule::unique($table, 'slug')],
            'html_content' => ['required', 'string'],
            'text_content' => ['nullable', 'string'],
            'variables' => ['nullable', 'array'],
            'variables.*' => ['string'],
            'is_active' => ['nullable', 'boolean'],
            'type' => ['nullable', 'string'],
        ];
    }
}
