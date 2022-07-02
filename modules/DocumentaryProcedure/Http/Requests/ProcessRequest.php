<?php

namespace Modules\DocumentaryProcedure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'name' => ['required',  Rule::unique('tenant.documentary_processes', 'name')->ignore($this->id)],
            // 'description' => 'max:250',
			'active'      => 'required|boolean',
		];
	}
}
