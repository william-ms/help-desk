<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartamentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('departament.edit');
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
            'name' => ['required', 'string', Rule::unique('departaments')->whereNull('deleted_at')->ignore($this->departament->id)]
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Já existe um departamento cadastrado com esse nome.'
        ];
    }
}
