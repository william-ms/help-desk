<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\Departament;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateSubcategoryRequest extends FormRequest
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
            'company_id' => ['nullable', 'integer'],
            'departament_id' => ['nullable', 'integer'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', Rule::unique('subcategories')->where('category_id', $request['category_id'])->whereNull('deleted_at')->ignore($this->subcategory->id)],
            'automatic_response' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'category_id.exists' => 'Categoria não encontrada',
            'name.unique' => 'Já existe para essa categoria uma subcategoria cadastrada com esse nome.',
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $data = $validator->getData();

                if(!auth()->user()->can('subcategory.companies') && empty(auth()->user()->companies()->find($data['company_id']))) {
                    $validator->errors()->add(
                        'company_id',
                        'Empresa não encontrada!'
                    );
                } else if (empty(Company::find($data['company_id']))) {
                    $validator->errors()->add(
                        'company_id',
                        'Empresa não encontrada!'
                    );
                }

                if(!auth()->user()->can('subcategory.departaments') && empty(auth()->user()->departaments()->find($data['departament_id']))) {
                    $validator->errors()->add(
                        'departament_id',
                        'Departamento não encontrado!'
                    );
                } else if (empty(Departament::find($data['departament_id']))) {
                    $validator->errors()->add(
                        'departament_id',
                        'Departamento não encontrado!'
                    );
                }
            }
        ];
    }
}
