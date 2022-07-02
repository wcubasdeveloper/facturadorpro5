<?php

    namespace Modules\Production\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class ProductionRequest extends FormRequest
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
                'warehouse_id' => [ 'required', ],
                'name' => [ 'required', ],
                'date_start' => [ 'required', ],
                'time_start' => [ 'required', ],
                'date_end' => [ 'required', ],
                'time_end' => [ 'required', ],
                'machine_id' => [ 'required', ],
                // 'user_id',
                // 'production_order',

                /*
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
