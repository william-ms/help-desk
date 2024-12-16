<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
            'departament_id' => ['required', 'integer'],
            'name' => ['required', 'string', Rule::unique('categories')->where('company_id', $request['company_id'])->where('departament_id', $request['departament_id'])->whereNull('deleted_at')->ignore($this->category->id)],
            'automatic_response' => ['nullable', 'string'],
            'resolution_time' => ['required', 'date_format:H:i:s'],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Já existe para esse departamento e empresa uma categoria cadastrada com esse nome.',
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

                if(!auth()->user()->can('category.companies') && empty(auth()->user()->companies()->find($data['company_id']))) {
                    $validator->errors()->add(
                        'company_id',
                        'Você não pode cadastrar uma categoria para essa empresa!'
                    );
                }

                if(!auth()->user()->can('category.departaments') && empty(auth()->user()->departaments()->find($data['departament_id']))) {
                    $validator->errors()->add(
                        'departament_id',
                        'Você não pode cadastrar uma categoria para esse departamento!'
                    );
                }
            }
        ];
    }
}
