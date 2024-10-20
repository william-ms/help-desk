<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartamentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('departament.create');
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
            'company_id' => ['required', 'integer'],
            'name' => ['required', 'string', Rule::unique('departaments')->where('company_id', $request['company_id'])->whereNull('deleted_at')]
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Já existe para essa empresa um departamento cadastrado com esse nome.'
        ];
    }
}
