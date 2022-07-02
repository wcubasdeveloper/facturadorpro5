<?php

namespace Modules\Expense\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class BankLoanRequest
 *
 * @package Modules\Expense\Http\Requests
 * @mixin  FormRequest
 */
class BankLoanRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            /*
            'bank_id' => [
                'required',
            ],
            */
            'loan_reason_id' => [
                'required',
            ],
            'number' => [
                // 'required_if:expense_type_id,"1", "2", "3"',
                'nullable',
            ],
            'bank_account_id' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],
            'fee' => [
                'required',
            ],
        ];
    }
}
