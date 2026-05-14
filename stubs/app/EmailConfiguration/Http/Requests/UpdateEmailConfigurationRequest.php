<?php

namespace App\EmailConfiguration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmailConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $table = config('email-configuration.table', 'email_configurations');
        $id = $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', Rule::unique($table, 'name')->ignore($id)],
            'subject' => ['sometimes', 'required', 'string'],
            'slug' => ['sometimes', 'required', 'string', Rule::unique($table, 'slug')->ignore($id)],
            'html_content' => ['sometimes', 'required', 'string'],
            'text_content' => ['nullable', 'string'],
            'variables' => ['nullable', 'array'],
            'variables.*' => ['string'],
            'is_active' => ['nullable', 'boolean'],
            'type' => ['nullable', 'string'],
        ];
    }
}
