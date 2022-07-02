<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class PurchaseImportRequest
 *
 * @package App\Http\Requests\Tenant
 * @mixin FormRequest
 */
class PurchaseImportRequest  extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'number' => [
                'required',
                'numeric'
            ],
            'series' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],
        ];
    }
}
