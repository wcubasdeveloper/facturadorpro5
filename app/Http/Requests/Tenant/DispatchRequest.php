<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 *
 * @mixin FormRequest
 */
class DispatchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        //$id = $this->input('id');

        return [
            'unit_type_id' => [
                'required',
            ],
            // 'transfer_reason_description' => [
            //     'required',
            // ],
            // 'observations' => [
            //     'required',
            // ],
            'delivery.address'=> [
                'required',
                'max:100',

            ],
            'dispatcher.identity_document_type_id'=> [
                'required',
            ],
            'dispatcher.number'=> [
                'required',
            ],
            'dispatcher.name'=> [
                'required',
            ],
            // 'driver.identity_document_type_id'=> [
            //     'required',
            // ],
            // 'driver.number'=> [
            //     'required',
            // ],
            // 'license_plate'=> [
            //     'required',
            // ],
            'license_plate'=> [
                'required_if:transport_mode_type_id, "02"',
            ],
            'driver.identity_document_type_id'=> [
                'required_if:transport_mode_type_id, "02"',
            ],
            'driver.number'=> [
                'required_if:transport_mode_type_id, "02"',
            ],

            'customer_id'=> [
                'required',
            ],
            'transport_mode_type_id'=> [
                'required',
            ],
            'transfer_reason_type_id'=> [
                'required',
            ],
            'origin.address'=> [
                'required',
                'max:100',
            ],

            'related.number'=> [
                'required_if:transfer_reason_type_id, "09"',
                'regex:"^[0-9]{4}-[0-9]{2}-[0-9]{3}-[0-9]{6}$"'
            ],
            'related.document_type_id'=> [
                'required_if:transfer_reason_type_id, "09"',
            ],


        ];
    }

    public function messages()
    {
        return [
            
            'transfer_reason_description.required' => 'El campo Descripción de motivo de traslado es obligatorio.',
            'observations.required' => 'El campo Observaciones es obligatorio.',
            'dispatcher.identity_document_type_id.required' => 'El campo Tipo Doc. Identidad es obligatorio.',
            'dispatcher.number.required' => 'El campo Número es obligatorio.',
            'dispatcher.name.required' => 'El campo Nombre y/o razón social es obligatorio.',
            'driver.identity_document_type_id.required' => 'El campo Tipo Doc. Identidad es obligatorio.',
            'driver.number.required' => 'El campo Número es obligatorio.',
            'license_plate.required' => 'El campo Número de placa del vehiculo es obligatorio.',

            'driver.number.required_if' => 'El campo Número es obligatorio cuando modo de traslado es '.$this->transport_mode_type_id.'.',
            'driver.identity_document_type_id.required_if' => 'El campo Tipo Doc. Identidad es obligatorio cuando modo de traslado es '.$this->transport_mode_type_id.'.',

            'related.number.regex' => 'El formato de Número de documento es inválido - Formato del campo: XXXX-XX-XXX-XXXXXX, Ejemplo: 0001-01-002-001234',

        ];
    }
}
