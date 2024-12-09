<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocalUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public const rules = [
        'name' => ['required', 'max:100'],
        'email' => ['required', 'email'],
        'password' => ['required', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
    ];

    public const messages = [
        'name.required' => 'O nome do usuário é obrigatório!',
        'name.max' => 'O nome do usuário não pode exceder 100 caracteres!',
        'email.required' => 'O e-mail do usuário é obrigatório!',
        'email.email' => 'O e-mail do usuário é inválido!',
        'password.required' => 'A senha é obrigatória!',
        'password.min' => 'A senha deve ter pelo menos 8 caracteres!',
        'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial!',
    ];
}
