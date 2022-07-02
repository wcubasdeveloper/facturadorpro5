<?php

    namespace Modules\FullSuscription\Http\Requests;


    use Illuminate\Foundation\Http\FormRequest;


    /**
     * Class PlanFullSuscriptionRequest
     *
     * @package Modules\FullSuscription\Http\Requests
     * @mixin FormRequest
     */
    class PlanFullSuscriptionRequest extends FormRequest
    {

        /**
         * @return bool
         */
        public function authorize()
        {
            return true;
        }

        /**
         * @return string[][]
         */
        public function rules()
        {

            return [
                'description' => [
                    'required',
                ],
                'name' => [
                    'required',
                ],
                // 'periods' => [
                //  'required',
                // ],
                // 'payment_method_type_id' => [
                //  'required',
                // ],
                'items' => [
                    'required',
                ],
            ];
        }

        /**
         * @return array
         */
        public function messages()
        {
            return [
                'description.required' => 'El campo Descripción es obligatorio.',
                'name.required' => 'El campo Nombre es obligatorio.',
                //'periods.required' => 'El campo Periodo es obligatorio.',
                //'payment_method_type_id.required' => 'El Método de pago es obligatorio.',
                'items.required' => 'Se requiere artículos.',
            ];
        }
    }
