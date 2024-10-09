<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('permission.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'route_prefix' => ['required', 'string', 'regex:/^[a-zA-Z_]+$/'],
            'route_method' => ['required', 'string', 'regex:/^[a-zA-Z_]+$/'],
        ];
    }

    public function messages()
    {
        return [
            'route_prefix.regex' => 'Prefixo da rota inválido. O prefixo da rota deve possuir apenas letras, se necessário, use _ para separar as palavras!',
            'route_method.regex' => 'Método da rota inválido. O método da rota deve possuir apenas letras, se necessário, use _ para separar as palavras!',
        ];
    }
}
