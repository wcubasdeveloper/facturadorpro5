<?php

namespace Modules\Production\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');

        return [
            'number' => [
                'required',
                Rule::unique('tenant.workers')->where(function ($query) use($id) {
                    return $query->where('id', '<>' ,$id);
                })
            ],
            'name' => [
                'required',
                Rule::unique('tenant.workers')->where(function ($query) use($id) {
                    return $query->where('id', '<>' ,$id);
                })
            ],
            'identity_document_type_id' => [
                'required',
            ],
            'birth_date' => [
                'required',
            ],
            'occupation' => [
                'required',
            ],
            'admission_date' => [
                'required',
            ],
            'address' => [
                'nullable',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'telephone' => [
                'nullable',
                'numeric',
            ],
        ];
    }
}
