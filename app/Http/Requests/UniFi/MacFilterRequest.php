<?php

namespace App\Http\Requests\UniFi;

use Illuminate\Foundation\Http\FormRequest;

class MacFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'wlan_id' => 'required',
            'mac' => 'required|string',
            'name' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'wlan_id.required' => 'O ID da rede WiFi é obrigatório.',
            'mac.required' => 'O endereço MAC é obrigatório.',
        ];
    }
}
