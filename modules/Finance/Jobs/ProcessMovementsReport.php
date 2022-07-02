<?php

namespace Modules\Finance\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Tenant\DownloadTray;
use Hyn\Tenancy\Environment;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Models\Tenant\Company;
use App\Models\Tenant\Establishment;
use Modules\Inventory\Exports\InventoryExport;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use App\Models\Tenant\Cash;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Finance\Models\GlobalPayment;
use Modules\Finance\Exports\MovementExport;


class ProcessMovementsReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, StorageDocument, FinanceTrait;

    public $params;
    public $tray_id;
    public $website_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Object $params, int $tray_id, int $website_id ) {
        $this->params = $params;
        $this->tray_id = $tray_id;
        $this->website_id = $website_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug("ProcessMovementsReport Start WebsiteId => " . $this->website_id);

        $website = Website::find($this->website_id);
        $tenancy = app(Environment::class);
        $tenancy->tenant($website);

        $path = null;
        try {

            $tray = DownloadTray::find($this->tray_id);

            $qery = $this->getRecords($this->params ,GlobalPayment::class);
            $qery->orderBy('id');
            $records = $qery->get();

            $calculateResiduary = $this->calculateResiduary($this->params);

            $transformRecords = $this->transformRecords($records, (object)$calculateResiduary);
            
            $company = Company::first();
            $establishment = Establishment::findOrFail($this->params->establishment_id);

            Log::debug("Render excel init");
            $MovementExport = new MovementExport();
            $MovementExport
            ->records($transformRecords)
            ->company($company)
            ->setNewFormat(true)
            ->establishment($establishment);

            Log::debug("Render excel finish");

            Log::debug("Upload excel init");

            $filename = 'FINANCES_Reporte_Movimientos__' . date('YmdHis');

            $MovementExport->store(DIRECTORY_SEPARATOR."download_tray_xlsx".DIRECTORY_SEPARATOR . $filename.'.xlsx', 'tenant');

            $tray->file_name = $filename;
            $path = 'download_tray_xlsx';


            
            $tray->date_end = date('Y-m-d H:i:s');
            $tray->status = 'FINISHED';
            $tray->path = $path;
            $tray->save();

        } catch (\Exception $e) {
            Log::debug("ProcessMovementsReport Error transaction ". $e);
        }

        Log::debug("ProcessMovementsReport Finish transaction");
    }

     /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::error($exception->getMessage());
    }


    private function getRecords($request, $model){

        $data_of_period = $this->getDatesOfPeriod((array)$request);
        //$payment_type = $request->payment_type;
        //$destination_type = $request['destination_type'];
        $last_cash_opening = $request->last_cash_opening;

        $params = (object)[
            'date_start' => $data_of_period['d_start'],
            'date_end' => $data_of_period['d_end'],
            'user_id' => $request->user_id
        ];

        $records = GlobalPayment::WhereFilterPaymentType($params);

        if($last_cash_opening == 'true'){

            $cash =  Cash::where([['user_id', $request->user_id],['state',true]])->first();

            if($cash){

                return $records->whereDestinationType(Cash::class)
                                ->where('destination_id', $cash->id)->latest();
            }
        }
        return $records->latest();
    }

    private function transformRecords($records, $calculateResiduary) {

        $data = $records->transform(function (GlobalPayment $row, $key) use (&$calculateResiduary) {
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
                    $calculateResiduary->balance = $calculateResiduary->balance - $amount;
                } else {
                    $calculateResiduary->balance = $calculateResiduary->balance + $amount;
                }
            } else {
                $calculateResiduary->balance = ($row->type_movement == 'input') ? $calculateResiduary->balance + $amount : $calculateResiduary->balance - $amount;
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
                'balance' => number_format($calculateResiduary->balance, 2, ".", ""),
                'items' => $this->getItems($row),

            ];
        });

        return $data;
    }

    private function calculateResiduary($request){
        $residuary = 0;
        $balance = 0;
        if ($request->page >= 2) {

            $data = app(MovementController::class)->getRecords($request, GlobalPayment::class)
                ->limit(($request->page * 20) - 20)->get();

            $input = $data->where('type_movement', 'input')->sum('payment.payment');
            $output = $data->where('type_movement', 'output')->sum('payment.payment');

            $residuary += $input - $output;
            $balance = $residuary;
        }

        return [
            'residuary' => $residuary,
            'balance' => $balance,
        ];
    }

    private function getPaymentMethodTypeDescription(GlobalPayment $row)
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
