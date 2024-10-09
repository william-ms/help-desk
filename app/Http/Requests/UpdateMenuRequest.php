<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('menu.create');
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
            'menu_category_id' => ['required', 'integer'],
            'name' => ['required', 'string', Rule::unique('menus')->where('menu_category_id', $request['menu_category_id'])->ignore($this->menu->id)],
            'icon' => ['required', 'string'],
            'route' => ['required', 'string', 'regex:/^[a-zA-Z_]+$/'],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Já existe para essa categoria um menu cadastrado com esse nome!',
            'route.regex' => 'A rota deve possuir apenas letras, use _ caso precise separar as palavras'
        ];
    }
}
