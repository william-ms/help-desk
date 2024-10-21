<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('category.create');
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
            'departament_id' => ['required', 'integer'],
            'name' => ['required', 'string', Rule::unique('categories')->where('departament_id', $request['departament_id'])->whereNull('deleted_at')],
            'automatic_response' => ['required', 'string'],
            'resolution_time' => ['required', 'date_format:H:i:s'],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Já existe para esse departamento uma categoria cadastrada com esse nome.',
            'automatic_response.required' => 'Informe uma resposta automática para a categoria'
        ];
    }
}
