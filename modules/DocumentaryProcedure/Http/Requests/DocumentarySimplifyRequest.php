<?php

    namespace Modules\DocumentaryProcedure\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;

    class DocumentarySimplifyRequest extends FormRequest
    {
        public function authorize()
        {
            return true;
        }

        public function rules()
        {
            return [
                'date_register' => 'required',
                'time_register' => 'required',
                'guides' => 'required|array',
                'documentary_process_id' => 'required',
                'person_id' => 'required',
                'invoice' => 'required',

            ];
        }
    }
