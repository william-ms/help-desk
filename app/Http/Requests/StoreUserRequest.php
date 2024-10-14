<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('user.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users')->whereNull('deleted_at')],
            'role' => ['required', 'integer'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Já existe um usuário cadastrado com esse email!'
        ];
    }
}
