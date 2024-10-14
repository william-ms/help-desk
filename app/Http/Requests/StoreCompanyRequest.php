<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('company.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('companies')->whereNull('deleted_at')]
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Já existe uma empresa cadastrada com esse nome!'
        ];
    }
}
