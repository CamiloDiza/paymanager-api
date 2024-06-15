<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReceiverRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'receiver_name' => 'sometimes|required|string',
            'document_type' => 'sometimes|required|string',
            'receiver_id' => 'sometimes|required|string|unique:receivers,receiver_id,' . $this->route('receiver'),
            'bank' => 'sometimes|required|string',
            'bank_account' => 'sometimes|required|string',
            'receiver_percentage' => 'sometimes|required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'receiver_name.sometimes' => 'The receiver name is sometimes required.',
            'document_type.sometimes' => 'The document type is sometimes required.',
            'receiver_id.sometimes' => 'The receiver ID is sometimes required.',
            'receiver_id.unique' => 'The receiver ID must be unique.',
            'bank.sometimes' => 'The bank name is sometimes required.',
            'bank_account.sometimes' => 'The bank account is sometimes required.',
            'receiver_percentage.sometimes' => 'The receiver percentage is sometimes required.',
            'receiver_percentage.numeric' => 'The receiver percentage must be a number.',
        ];
    }
}
