<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'photo' => ['nullable', 'string'],
            'avatar' => ['nullable', 'string'],
            'password_actual' => ['nullable', 'string'],
            'password' => ['nullable', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'password_confirmation' => ['same:password']
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $data = $validator->getData();

            if(!empty($data['password'] && empty($data['password_actual']))) {
                $validator->errors()->add('password_actual', 'Informe a senha atual da conta para atualizar a senha!');
            }

            if(!empty($data['password_actual']) && !Hash::check( $data['password_actual'], auth()->user()->password) ) {
                $validator->errors()->add('password_actual', 'A senha atual está incorreta!');
            }
        });
    }
}
