<?php

    namespace Modules\Production\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class MachineTypeRequest extends FormRequest
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
                'description' => [
                    'required',
                ],
                'active' => [
                    'required',
                    'boolean',

                ],


                /*
                 *
            machine_type_id:null,
            */
            ];
        }
    }
