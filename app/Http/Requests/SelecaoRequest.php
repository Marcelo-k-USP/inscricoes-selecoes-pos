<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelecaoRequest extends FormRequest
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
    public function rules()
    {

        $rules = [
            'nome' => ['required', 'max:100'],
            'descricao' => ['max:255'],
            'processo_id' => 'required|numeric',
        ];
        return $rules;
    }
}
