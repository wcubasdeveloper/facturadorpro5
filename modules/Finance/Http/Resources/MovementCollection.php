<?php

    namespace Modules\Finance\Http\Resources;

    use App\Models\Tenant\TransferAccountPayment;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Illuminate\Support\Collection;
    use Modules\Finance\Http\Controllers\MovementController;
    use Modules\Finance\Models\GlobalPayment;


    class MovementCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param Request $request
         *
         * @return array
         */
        protected static $balance = 0;
        protected static $residuary = 0;
        protected static $request;


        public function toArray($request)
        {
            self::$request = $request;
            $this->calculateResiduary(self::$request);
            /** @var Collection $data */

            $data = $this->collection->transform(function (GlobalPayment $row, $key) use ($request) {
                $index = $key + 1;
                $data_person = $row->data_person;
                $timedate = null;
                $type_movement = $row->type_movement;
                $payment = $row->payment;
                $amount = $row->payment->payment * 1;
                $instance_type_description = $row->instance_type_description;
                if (get_class($payment) == TransferAccountPayment::class) {
                    $amount = $payment->amount * 1;
                }
                $document = $row->payment->document;
                $document_type = '';
                $payments = $payment->payment;

                // Convirtiendo el documento que esta hecho en dolares a soles
                if ($document) {
                    if ($document->currency_type_id === 'USD') {
                        $amount *= $document->exchange_rate_sale;
                    }
                }
                if (get_class($payment) == TransferAccountPayment::class) {
                    // para que transferencias bancarias. refleje el numero correctamente.
                    if ($type_movement == 'input') {
                        self::$balance -= $amount;
                    } else {
                        self::$balance += $amount;
                    }
                } else {
                    self::$balance = ($row->type_movement == 'input') ? self::$balance + $amount : self::$balance - $amount;
                }

                // $timedate = $payment->date_of_payment->format('Y-m-d');
                if ($payment->date_of_payment && $payment->date_of_payment != null) {
                    $timedate = $payment->date_of_payment->toDateTimeString();

                }


                if ($payment->associated_record_payment) {
                    if ($payment->associated_record_payment->date_of_issue) {
                        $timedate = $payment->associated_record_payment->date_of_issue->format('Y-m-d') . " " . $payment->associated_record_payment->time_of_issue;
                        $timedate = Carbon::createFromFormat('Y-m-d H:i:s', $timedate)->toDateTimeString();
                    }

                    if ($payment->associated_record_payment->document_type) {
                        $document_type = $payment->associated_record_payment->document_type->description;
                    } elseif (isset($payment->associated_record_payment->prefix)) {
                        $document_type = $payment->associated_record_payment->prefix;
                    }
                }
                $destinationArray = $row->getDestinationWithCci();
                $destinationName = $destinationArray['name'] . " - " . $destinationArray['description'];

                $person_name = $data_person->name;
                $person_number = $data_person->number;
                $numberFull = $payment->associated_record_payment->number_full ?? null;
                if ($row->instance_type == 'bank_loan_payment') {
                    $person_name = $person_name->description;
                    $document_type = $row->instance_type_description;
                    // $person_name = $person_name->description;
                    $person_number = '';
                    // dd($row->payment->associated_record_payment);
                    $numberFull = $row->payment->associated_record_payment->getNumberFull();
                }

                $input = '-';
                $output = $input;

                if ($type_movement == 'input') {
                    $input = number_format($amount, 2, ".", "");
                } else {
                    $output = number_format($amount, 2, ".", "");
                }
                if (get_class($payment) == TransferAccountPayment::class) {
                    // transferencia bancaria
                    $person_name = $destinationArray['name'] ?? '-';
                    $person_number = $destinationArray['cci'] ?? '-';
                    if ($amount < 0) {
                        // banco destino
                        $output = number_format(abs($amount), 2, ".", "");
                        $input = '-';
                    } else {
                        // banco de origen

                        $input = number_format(abs($amount), 2, ".", "");
                        $output = '-'; }
                    $timedate = $row->payment->date_of_movement->format('Y-m-d H:i:s');
                    $instance_type_description = 'Transferencia Bancaria';
                }


                return [
                    'index' => $index,
                    //'data' => $row,
                    'payments' => $payments,
                    'document_type' => $document_type,
                    'id' => $row->id,
                    'destination_description' => $row->destination_description,
                    'destination_array' => $destinationArray,
                    'destination_name' => $destinationName,
                    'date_of_payment_class' => get_class($payment),
                    'date_of_payment' => $timedate,
                    'payment_method_type_description' => $this->getPaymentMethodTypeDescription($row),
                    'reference' => $payment->reference,
                    'total' => $amount,
                    'number_full' => $numberFull,
                    'currency_type_id' => $payment->associated_record_payment->currency_type_id ?? 'PEN',
                    // 'document_type_description' => ($payment->associated_record_payment->document_type) ? $payment->associated_record_payment->document_type->description:'NV',
                    'document_type_description' => $this->getDocumentTypeDescription($row),
                    'person_name' => $person_name,
                    'person_number' => $person_number,
                    // 'payment' => $row->payment,
                    // 'payment_type' => $row->payment_type,
                    'instance_type' => $row->instance_type,
                    'instance_type_description' => $instance_type_description,
                    'user_id' => $row->user_id,
                    'user_name' => optional($row->user)->name,
                    'type_movement' => $type_movement,
                    'input' => $input,
                    'output' => $output,
                    'balance' => number_format(self::$balance, 2, ".", ""),
                    'items' => $this->getItems($row),


                ];
            });

            return $data;
        }

        public function calculateResiduary($request)
        {

            if ($request->page >= 2) {

                $data = app(MovementController::class)->getRecords($request, GlobalPayment::class)
                    ->limit(($request->page * 20) - 20)->get();

                $input = $data->where('type_movement', 'input')->sum('payment.payment');
                $output = $data->where('type_movement', 'output')->sum('payment.payment');

                self::$residuary += $input - $output;
                self::$balance = self::$residuary;

            }

        }

        public function getPaymentMethodTypeDescription(GlobalPayment $row)
        {

            $payment_method_type_description = '';

            if ($row->payment->payment_method_type) {

                $payment_method_type_description = $row->payment->payment_method_type->description;

            } elseif ($row->payment->expense_method_type) {
                $payment_method_type_description = $row->payment->expense_method_type->description;
            }

            return $payment_method_type_description;
        }

        public function getDocumentTypeDescription(GlobalPayment $row)
        {

            $document_type = '';

            if ($row->payment->associated_record_payment) {
                if ($row->payment->associated_record_payment->document_type) {

                    $document_type = $row->payment->associated_record_payment->document_type->description;

                } elseif (isset($row->payment->associated_record_payment->prefix)) {

                    $document_type = $row->payment->associated_record_payment->prefix;

                }
            }
            return $document_type;

        }

        public function getItems(GlobalPayment $row)
        {
            $instanceType = $row->instance_type;
            if (in_array($instanceType, ['expense', 'income', 'bank_loan_payment'])) {

                return $row->payment->associated_record_payment->items->transform(function ($row, $key) {
                    return [
                        'description' => $row->description,
                    ];
                });
            }

            return [];

        }


    }
