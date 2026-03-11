<?php

namespace App\Http\Requests\UniFi;

use Illuminate\Foundation\Http\FormRequest;

class SelectSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_id' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'site_id.required' => 'O ID do site é obrigatório.',
        ];
    }
}
