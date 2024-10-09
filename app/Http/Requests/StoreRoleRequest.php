<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('role.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request = $this->request->all();

        return [
            'name' => ['required', 'string', 'regex:/^[a-zA-Z\sÀ-ú]*$/', Rule::unique('roles')],
            // 'guard_name' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Já existe para esse guarda uma função cadastrada com esse nome!'
        ];
    }
}
