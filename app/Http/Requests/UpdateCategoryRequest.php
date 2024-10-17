<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('category.edit');
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
            'name' => ['required', 'string', Rule::unique('categories')->where('company_id', $request['company_id'])->whereNull('deleted_at')->ignore($this->category->id)],
            'automatic_response' => ['required', 'string'],
            'resolution_time' => ['required', 'date_format:H:i:s'],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Já existe para essa empresa uma categoria cadastrada com esse nome.',
            'automatic_response.required' => 'Informe uma resposta automática para a categoria'
        ];
    }
}
