<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayerRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'receiver_name.sometimes' => 'The receiver name is sometimes required.',
            'document_type.sometimes' => 'The document type is sometimes required.',
            'receiver_id.sometimes' => 'The receiver ID is sometimes required.',
            'receiver_id.unique' => 'The receiver ID must be unique.',
        ];
    }
}
