<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMenuCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('menu_category.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'regex:/^[a-zA-Z\sÀ-ú]*$/', Rule::unique('menu_categories')->ignore($this->menu_category->id)]
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Já existe uma categoria de menu cadastrada com esse nome!'
        ];
    }
}
