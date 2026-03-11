<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SitePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'site_name' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'O ID do usuário é obrigatório.',
            'user_id.exists' => 'Usuário não encontrado.',
            'site_name.required' => 'O nome do site é obrigatório.',
        ];
    }
}
