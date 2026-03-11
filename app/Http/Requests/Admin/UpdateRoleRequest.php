<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,user',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'O ID do usuário é obrigatório.',
            'user_id.exists' => 'Usuário não encontrado.',
            'role.required' => 'O nível de acesso é obrigatório.',
            'role.in' => 'Nível de acesso inválido.',
        ];
    }
}
