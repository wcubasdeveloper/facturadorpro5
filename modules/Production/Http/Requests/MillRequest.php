<?php

namespace Modules\Production\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MillRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'name' => [
                'required',
            ],
            'date_start' => [
                'required',
            ],
            'time_start' => [
                'required',
            ],
            'date_end' => [
                'required',
            ],
            'time_end' => [
                'required',
            ],
            'items' => [
                'required',
            ],
                /*
            'expense_reason_id' => [
                'required',
            ],
            'number' => [
                // 'required_if:expense_type_id,"1", "2", "3"',
                'nullable',
            ],
            'date_of_issue' => [
                'required',
            ],*/
        ];
    }
}
