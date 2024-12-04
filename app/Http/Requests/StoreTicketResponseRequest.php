<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTicketResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'response' => ['required', 'string'],
            'ticket_id' => ['required', 'integer'],
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $data = $validator->safe();

            if($data['ticket_id'] != session()->get('ticket_id')) {
                $validator->errors()->add('ticket_id', 'Não foi possível responder o ticket, por favor tente novamente.');
            }
        });
    }




    // /**
    //  * Get the "after" validation callables for the request.
    //  */
    // public function after(): array
    // {
    //     return [
    //         function (Validator $validator) {
    //             if ($validator['ticket_id'] != session()->get('ticket_id')) {
    //                 $validator->errors()->add(
    //                     'ticket_id',
    //                     'Something is wrong with this field!'
    //                 );
    //             }
    //         }
    //     ];
    // }
}
