<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do Roteador é obrigatório.',
            'host.required' => 'O endereço IP/Host é obrigatório.',
            'username.required' => 'O usuário é obrigatório.',
            'password.required' => 'A senha é obrigatória.',
            'port.required' => 'A porta é obrigatória.',
        ];
    }
}
