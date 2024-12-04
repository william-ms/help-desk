<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'company_id' => ['nullable', 'integer'],
            'departament_id' => ['nullable', 'integer'],
            'category_id' => ['required', 'integer'],
            'subcategory_id' => ['nullable', 'integer'],
            'subject' => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
    }
}
