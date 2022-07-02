<?php

    namespace Modules\Expense\Http\Resources;

    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;
    use Modules\Expense\Models\BankLoan;
    use Modules\Expense\Models\BankLoanFee;
    use Modules\Expense\Models\BankLoanPayment;

    class BankLoanResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @param Request $request
         *
         * @return array
         */
        public function toArray($request)
        {


            $bankLoan = BankLoan::with(['items','fee','payments','bank_account'])->find($this->id);
            // $bankLoan->payments = self::getTransformPayments($bankLoan->payments);

            return [
                'id' => $bankLoan->id,
                'external_id' => $bankLoan->external_id,
                'number' => $bankLoan->number ?? '-',
                'state_type_id' => $bankLoan->state_type_id,
                'date_of_issue' => $bankLoan->date_of_issue->format('Y-m-d'),

                'fee'=>$bankLoan->fee->transform(function(BankLoanFee $row){
                    $data = [
                        'id' => $row->id,
                        'date' => $row->date,
                        'currency_type_id' => $row->currency_type_id,
                        'amount' => (float)$row->amount,
                    ];


                    return $data;
                }),

                'payments' => $bankLoan->payments->transform(function (BankLoanPayment $row, $key) {
                    return [
                        'id' => $row->id,
                        'payment_method_type_description' => ($row->payment_method_type) ? $row->payment_method_type->description : null,
                        'destination_description' => ($row->global_payment) ? $row->global_payment->destination_description : null,
                        'reference' => $row->reference,
                        'payment' => $row->payment,
                    ];
                }),
                'bank_loan' => $bankLoan
            ];
        }


        /**
         * @param Collection $payments
         *
         * @return Collection
         */
        public static function getTransformPayments(Collection $payments)
        {

            return $payments->transform(function (BankLoanPayment $row, $key) {
                return [
                    'id' => $row->id,
                    'bank_loan_id' => $row->bank_loan_id,
                    'date_of_payment' => $row->date_of_payment->format('Y-m-d'),
                    'payment_method_type_id' => $row->payment_method_type_id,
                    'has_card' => $row->has_card,
                    'card_brand_id' => $row->card_brand_id,
                    'reference' => $row->reference,
                    'payment' => $row->payment,
                    'payment_method_type' => $row->payment_method_type,
                    'payment_destination_id' => ($row->global_payment) ? ($row->global_payment->type_record == 'cash' ? null : $row->global_payment->destination_id) : null,
                    'payment_filename' => ($row->payment_file) ? $row->payment_file->filename : null,
                    'payment_destination_disabled' => ($row->expense_method_type_id == 1) ? true : false
                ];
            });

        }

    }
