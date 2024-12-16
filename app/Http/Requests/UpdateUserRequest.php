<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('user.edit');
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
            'email' => ['required', 'email', Rule::unique('users')->whereNull('deleted_at')->ignore($this->user->id)],
            'password' => ['nullable', 'string'],
            'companies' => ['required', 'array'],
            'departaments' => ['required', 'array'],
            'status' => ['nullable', 'integer'],
            'role' => ['nullable', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Já existe um usuário cadastrado com esse email!'
        ];
    }
}
