<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubcategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('subcategory.create');
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
            'category_id' => ['required', 'integer'],
            'name' => ['required', 'string', Rule::unique('subcategories')->where('category_id', $request['category_id'])->whereNull('deleted_at')],
            'automatic_response' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Já existe para essa categoria uma subcategoria cadastrada com esse nome.',
            'automatic_response.required' => 'Informe uma resposta automática para a subcategoria'
        ];
    }
}
