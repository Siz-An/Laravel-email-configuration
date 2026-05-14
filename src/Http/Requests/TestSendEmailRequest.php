<?php

namespace Sizan\EmailConfiguration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestSendEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'to' => ['required', 'email'],
            'variables' => ['nullable', 'array'],
        ];
    }
}
