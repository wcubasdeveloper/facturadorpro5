<?php

namespace Modules\MercadoPago\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {

        return $this->collection->transform(function($row, $key) {

            return [
                'id' => $row->id,
                'date' => $row->date,
                'time' => $row->time,
                'description' => $row->description,
                'payment_id' => $row->payment_id,
                'amount' => $row->amount,
                'commission_percentage' => $row->commission_percentage,
                'deposit_amount' => $row->deposit_amount,
                'payment_state_id' => $row->payment_state_id,
                'payment_state_name' => $row->payment_state->name,
                'payment_state_message' => $row->payment_state->user_message,
                'verified' => ($row->transaction_verification) ? true : false,
                'verified_name' => ($row->transaction_verification) ? 'SI' : 'NO',
                'bank_account_id' => $row->bank_account_id,
                'bank_account_number' => $row->data_bank_account->account_number,
                'customer_fullname' => "{$row->data_bank_account->user->name} {$row->data_bank_account->user->lastname}",
                'customer_number' => "{$row->data_bank_account->user->identity_document_type->name}: {$row->data_bank_account->user->number}",
            ];

        });
        
    }
}