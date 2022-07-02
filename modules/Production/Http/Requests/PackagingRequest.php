<?php

    namespace Modules\Production\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class PackagingRequest extends FormRequest
    {

        public function authorize()
        {
            return true;
        }

        public function rules()
        {

            return [

                'item_id' => [
                    'required',
                ],
                'quantity' => [
                    'required',
                ],
                'number_packages' => [
                    'required',
                ],
                'establishment_id' => [ 'required', ],
                'name' => [ 'required', ],
                'date_start' => [ 'required', ],
                'time_start' => [ 'required', ],
                'date_end' => [ 'required', ],
                'time_end' => [ 'required', ],
/*

                date_end: "2022-01-24"
date_start: "2022-01-11"
item: {}
item_extra_data: {color: 2}
item_id: 257
lot_code: "Kot552"
name: "F440"
number_packages: 5
observation: "Noned"
supplies: {}
time_en: null
time_end: "19:26:48"
time_start: "19:26:46"
user_id: null
        */
            ];
        }
    }
