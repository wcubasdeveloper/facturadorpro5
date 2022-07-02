<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class VoidedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            
            'documents' => 'required|array',
            'documents.*.description' => 'required',
            'documents.*.document_id' => 'required',
        ];
    }

    
    public function messages()
    {
        return [
            'documents.*.description.required' => 'El campo descripción del motivo de anulación es obligatorio.',
        ];
    }

}
