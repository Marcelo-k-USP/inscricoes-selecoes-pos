<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LimpezaDadosRequest extends FormRequest
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
        'data_limite' => ['required'],
    ];

    public const messages = [
        'data_limite.required' => 'A data limite é obrigatória!',
    ];
}
