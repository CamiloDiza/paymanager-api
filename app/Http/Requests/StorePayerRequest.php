<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePayerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payer_name' => 'required|string',
            'document_type' => 'required|string',
            'payer_id' => 'required|string|unique:payers',
        ];
    }

    public function messages()
    {
        return [
            'payer_name.required' => 'The payer name is required.',
            'document_type.required' => 'The document type is required.',
            'payer_id.required' => 'The payer ID is required.',
            'payer_id.unique' => 'The payer ID must be unique.',
        ];
    }
}
