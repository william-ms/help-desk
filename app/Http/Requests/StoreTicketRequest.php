<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\Departament;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('ticket.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => ['required', 'integer'],
            'departament_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'subcategory_id' => ['nullable', 'integer'],
            'subject' => ['required', 'string'],
            'description' => ['required', 'string'],
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

                if(!auth()->user()->can('ticket.companies') && empty(auth()->user()->companies()->find($data['company_id']))) {
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

                if(!auth()->user()->can('ticket.departaments') && empty(auth()->user()->departaments()->find($data['departament_id']))) {
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
