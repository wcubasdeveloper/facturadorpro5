<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportMovementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            // 'item_id' => [
            //     'required',
            // ],
            'warehouse_id' => [
                'required',
            ],
            // 'inventory_transaction_id' => [
            //     'required',
            // ],
            'movement_type' => [
                'required',
            ],
        ];
    }
}