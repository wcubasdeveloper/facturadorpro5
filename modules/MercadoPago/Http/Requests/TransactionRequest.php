<?php

namespace Modules\MercadoPago\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {

        return [
            'description' => [
                'required',
                'max:255',
            ],
            'transaction_amount' => [
                'required',
                'numeric',
                'gt:0'
            ],
            'email' => [
                'required',
                'email'
            ], 
            'user' => [
                'required'
            ],
            'installments' => [
                'required',
            ], 
            'payment_method_id' => [
                'required',
            ], 
            'token' => [
                'required',
            ], 
            'number' => [
                'required',
                'numeric',
            ],  
            'payment_link_id' => [
                'required',
            ], 
        ];
    }
}
