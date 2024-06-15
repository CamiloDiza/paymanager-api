<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceiverRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'receiver_name' => 'required|string',
            'document_type' => 'required|string',
            'receiver_id' => 'required|string|unique:receivers',
            'bank' => 'required|string',
            'bank_account' => 'required|string',
            'receiver_percentage' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'receiver_name.required' => 'The receiver name is required.',
            'document_type.required' => 'The document type is required.',
            'receiver_id.required' => 'The receiver ID is required.',
            'receiver_id.unique' => 'The receiver ID must be unique.',
            'bank.required' => 'The bank name is required.',
            'bank_account.required' => 'The bank account is required.',
            'receiver_percentage.required' => 'The receiver percentage is required.',
            'receiver_percentage.numeric' => 'The receiver percentage must be a number.',
        ];
    }
}
