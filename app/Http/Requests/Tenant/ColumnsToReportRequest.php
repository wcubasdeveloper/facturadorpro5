<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ColumnsToReportRequest
 *
 * @package App\Http\Requests\Tenant
 */
class ColumnsToReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'report' => [
                'required'
            ],
            'columns' => [
                'required'
            ],
            'updated' => [
                'bool'
            ],
        ];
    }
}
