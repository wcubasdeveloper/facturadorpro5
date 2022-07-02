<?php

namespace Modules\Report\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportConfigurationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        
        return [
            'id' => [
                'required',
            ],
            'route_name' => [
                'required',
            ],
            'name' => [
                'required',
            ],
            'convert_pen' => [
                'required',
            ],
            'route_path' => [
                'required',
            ],
        ];
    }
}