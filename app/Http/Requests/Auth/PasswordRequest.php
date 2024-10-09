<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordRequest extends FormRequest
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
        return [
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'password_confirmation' => ['required', 'same:password']
        ];
    }

    public function messages()
    {
        return [
            'senha.min' => 'A senha deve ter mais de 8 caracteres',
        ];
    }
}
