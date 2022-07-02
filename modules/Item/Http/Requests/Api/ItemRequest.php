<?php

namespace Modules\Item\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        
        $id = $this->input('id');

        return [
            'internal_id' => [
                'nullable',
                Rule::unique('tenant.items')->ignore($id),
            ],
            'description' => [
                'required', 'max:600'
            ],
            'name' => [
                'max:600'
            ],
            'second_name' => [
                'max:600'
            ],
            'unit_type_id' => [
                'required',
            ],
            'currency_type_id' => [
                'required'
            ],
            'sale_unit_price' => [
                'required',
                'numeric',
                'gt:0'
            ],
            'purchase_unit_price' => [
                'required', 'numeric'
            ],
            'sale_affectation_igv_type_id' => [
                'required'
            ],
            'purchase_affectation_igv_type_id' => [
                'required'
            ],
            'model' => 'max:100',
            
            'system_isc_type_id' => [
                'required_if:has_isc, 1',
            ],
            'percentage_isc' => [
                'required_if:has_isc, 1',
                'numeric',
                'min:0',
            ],

            'purchase_system_isc_type_id' => [
                'required_if:purchase_has_isc, 1',
            ],
            'purchase_percentage_isc' => [
                'required_if:purchase_has_isc, 1',
                'numeric',
            ],
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'La descripción debe ser inferior a 600 caracteres.',
            'sale_unit_price.gt' => 'El precio unitario de venta debe ser mayor que 0.',
        ];
    }
}
