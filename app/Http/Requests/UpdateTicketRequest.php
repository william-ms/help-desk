<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('ticket.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['nullable', 'string'],
            'transfer_assignee_id' => ['nullable', 'string'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $data = $validator->getData();
            $Ticket = $this->ticket;
            
            if($data['type'] === 'transfer') {  
                if($data['transfer_assignee_id'] == $Ticket->requester_id) {
                    $validator->errors()->add('tranfer', 'Não é possível transferir o ticket para o usuário que abriu o ticket!');
                }

                if($data['transfer_assignee_id'] == auth()->id()) {
                    $validator->errors()->add('tranfer', 'Não é possível transferir o ticket para você mesmo!');
                }
            }
        });
    }
}
