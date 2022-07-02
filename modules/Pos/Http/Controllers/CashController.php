<?php

namespace Modules\Pos\Http\Controllers;

use App\Http\Controllers\Tenant\EmailController;
use App\Models\Tenant\Cash;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\PaymentMethodType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Pos\Exports\ReportCashExport;
use Modules\Pos\Mail\CashEmail;
use Mpdf\Mpdf;

class CashController extends Controller
{
    public function email(Request $request) {
        $request->validate(
            ['email' => 'required']
        );

        $company = Company::active();
        $email = $request->input('email');

        $mailable = new CashEmail($company, $this->getPdf($request->cash_id));
        $model = Cash::find($request->cash_id);
        $id = (int) $model->id;
        $sendIt = EmailController::SendMail($email, $mailable, $id, $model);
        /*
        Configuration::setConfigSmtpMail();
        $array_email = explode(',', $email);
        if (count($array_email) > 1) {
            foreach ($array_email as $email_to) {
                $email_to = trim($email_to);
                if(!empty($email_to)) {
                    Mail::to($email_to)->send(new CashEmail($company, $this->getPdf($request->cash_id)));
                }
            }
        } else {
            Mail::to($email)->send(new CashEmail($company, $this->getPdf($request->cash_id)));
        }*/

        return [
            'success' => true
        ];
    }

    /**
     * Obtiene el string del metodo de pago
     *
     * @param $payment_id
     *
     * @return string
     */
    public static function getStringPaymentMethod($payment_id) {
        $payment_method = PaymentMethodType::find($payment_id);
        return (!empty($payment_method)) ? $payment_method->description : '';
    }

    /**
     * Genera un formato de numero para las operaciones del reporte.
     *
     * @param int    $number
     * @param int    $decimal
     * @param string $decimal_separador
     * @param string $miles_separador
     *
     * @return string
     */
    public static function FormatNumber($number = 0, $decimal = 2, $decimal_separador = '.', $miles_separador = '') {
        return number_format($number, $decimal, $decimal_separador, $miles_separador);
    }

    /**
     * @param int $cash_id
     *
     * @return array
     */
    public function setDataToReport($cash_id = 0) {

        set_time_limit(0);
        $data = [];
        /** @var Cash $cash */
        $cash = Cash::findOrFail($cash_id);
        $establishment = $cash->user->establishment;
        $status_type_id = self::getStateTypeId();
        $final_balance = 0;
        $cash_income = 0;
        $credit = 0;
        $cash_egress = 0;
        $cash_final_balance = 0;
        $cash_documents = $cash->cash_documents;
        $all_documents = [];

        // Metodos de pago de no credito
        $methods_payment_credit = PaymentMethodType::NonCredit()->get()->transform(function ($row) {
            return $row->id;
        })->toArray();

        $methods_payment = collect(PaymentMethodType::all())->transform(function ($row) {
            return (object)[
                'id'   => $row->id,
                'name' => $row->description,
                'sum'  => 0,
            ];
        });
        $company = Company::first();

        $data['cash'] = $cash;
        $data['cash_user_name'] = $cash->user->name;
        $data['cash_date_opening'] = $cash->date_opening;
        $data['cash_state'] = $cash->state;
        $data['cash_date_closed'] = $cash->date_closed;
        $data['cash_time_closed'] = $cash->time_closed;
        $data['cash_time_opening'] = $cash->time_opening;
        $data['cash_documents'] = $cash_documents;
        $data['cash_documents_total'] = (int)$cash_documents->count();

        $data['company_name'] = $company->name;
        $data['company_number'] = $company->number;
        $data['company'] = $company;

        $data['status_type_id'] = $status_type_id;

        $data['establishment'] = $establishment;
        $data['establishment_address'] = $establishment->address;
        $data['establishment_department_description'] = $establishment->department->description;
        $data['establishment_district_description'] = $establishment->district->description;
        $data['nota_venta'] = 0;

        $data['total_tips'] = 0;
        $data['total_payment_cash_01_document'] = 0;
        $data['total_payment_cash_01_sale_note'] = 0;

        $nota_credito = 0;
        $nota_debito = 0;
        /************************/

        foreach ($cash_documents as $cash_document) {
            $type_transaction = null;
            $document_type_description = null;
            $number = null;
            $date_of_issue = null;
            $customer_name = null;
            $customer_number = null;
            $currency_type_id = null;
            $temp = [];
            $notes = [];
            $usado = '';

            /** Documentos de Tipo Nota de venta */
            if ($cash_document->sale_note) {
                $sale_note = $cash_document->sale_note;
                if (in_array($sale_note->state_type_id, $status_type_id)) {
                    $record_total = 0;
                    $total = self::CalculeTotalOfCurency(
                        $sale_note->total,
                        $sale_note->currency_type_id,
                        $sale_note->exchange_rate_sale
                    );
                    $cash_income += $total;
                    $final_balance += $total;
                    if (count($sale_note->payments) > 0) {
                        $pays = $sale_note->payments;
                        foreach ($methods_payment as $record) 
                        {
                            $record_total = $pays->where('payment_method_type_id', $record->id)->sum('payment');
                            $record->sum = ($record->sum + $record_total);
                            if($record->id === '01') $data['total_payment_cash_01_sale_note'] += $record_total;
                        }
                    }

                    $data['total_tips'] += $sale_note->tip ? $sale_note->tip->total : 0;
                }


                $temp = [
                    'type_transaction'          => 'Venta',
                    'document_type_description' => 'NOTA DE VENTA',
                    'number'                    => $sale_note->number_full,
                    'date_of_issue'             => $sale_note->date_of_issue->format('Y-m-d'),
                    'date_sort'                 => $sale_note->date_of_issue,
                    'customer_name'             => $sale_note->customer->name,
                    'customer_number'           => $sale_note->customer->number,
                    'total'                     => ((!in_array($sale_note->state_type_id, $status_type_id)) ? 0
                        : $sale_note->total),
                    'currency_type_id'          => $sale_note->currency_type_id,
                    'usado'                     => $usado." ".__LINE__,
                    'tipo'                      => 'sale_note',
                    'total_payments'            => (!in_array($sale_note->state_type_id, $status_type_id)) ? 0 : $sale_note->payments->sum('payment'),
                ];
            } 
            /** Documentos de Tipo Document */
            elseif ($cash_document->document) {
                $record_total = 0;
                $document = $cash_document->document;
                $payment_condition_id = $document->payment_condition_id;
                $pays = $document->payments;
                $pagado = 0;
                if (in_array($document->state_type_id, $status_type_id)) {
                    if ($payment_condition_id == '01') {
                        $total = self::CalculeTotalOfCurency(
                            $document->total,
                            $document->currency_type_id,
                            $document->exchange_rate_sale
                        );
                        $usado .= '<br>Tomado para income<br>';
                        $cash_income += $total;
                        $final_balance += $total;
                        if (count($pays) > 0) {
                            $usado .= '<br>Se usan los pagos<br>';
                            foreach ($methods_payment as $record) {
                                $record_total = $pays
                                    ->where('payment_method_type_id', $record->id)
                                    ->whereIn('document.state_type_id', $status_type_id)
                                    ->sum('payment');
                                $record->sum = ($record->sum + $record_total);
                                if (!empty($record_total)) {
                                    $usado .= self::getStringPaymentMethod($record->id).'<br>Se usan los pagos Tipo '.$record->id.'<br>';
                                }

                                if($record->id === '01') $data['total_payment_cash_01_document'] += $record_total;

                            }
                        }
                    } else {
                        $usado .= '<br> state_type_id: '.$document->state_type_id.'<br>';
                        foreach ($methods_payment as $record) {
                            $record_total = $pays
                                ->where('payment_method_type_id', $record->id)
                                ->whereIn('document.state_type_id', $status_type_id)
                                ->transform(function ($row) {
                                    if (!empty($row->change) && !empty($row->payment)) {
                                        return (object)[
                                            'payment' => $row->change * $row->payment,
                                        ];
                                    }
                                    return (object)[
                                        'payment' => $row->payment,
                                    ];
                                })
                                ->sum('payment');
                            $usado .= "Id de documento {$document->id} - ".self::getStringPaymentMethod($record->id)." /* $record_total */<br>";
                            if ($record->id == '09') {
                                $usado .= '<br>Se usan los pagos Credito Tipo '.$record->id.' ****<br>';
                                // $record->sum += $document->total;
                                $credit += $document->total;
                            } elseif ($record_total != 0) {
                                if ((in_array($record->id, $methods_payment_credit))) {
                                    $record->sum += $record_total;
                                    $pagado += $record_total;
                                    $cash_income += $record_total;
                                    $credit -= $record_total;
                                    $final_balance += $record_total;
                                } else {
                                    $record->sum += $record_total;
                                    $credit += $record_total;
                                }
                            }
                        }
                        foreach ($methods_payment as $record) {
                            if ($record->id == '09') {
                                $record->sum += $document->total - $pagado;
                            }
                        }
                    }

                    $data['total_tips'] += $document->tip ? $document->tip->total : 0;

                }
                if ($record_total != $document->total) {
                    $usado .= '<br> Los montos son diferentes '.$document->total." vs ".$pagado."<br>";
                }


                $temp = [
                    'type_transaction'          => 'Venta',
                    'document_type_description' => $document->document_type->description,
                    'number'                    => $document->number_full,
                    'date_of_issue'             => $document->date_of_issue->format('Y-m-d'),
                    'date_sort'                 => $document->date_of_issue,
                    'customer_name'             => $document->customer->name,
                    'customer_number'           => $document->customer->number,
                    'total'                     => (!in_array($document->state_type_id, $status_type_id)) ? 0
                        : $document->total,
                    'currency_type_id'          => $document->currency_type_id,
                    'usado'                     => $usado." ".__LINE__,

                    'tipo' => 'document',
                    'total_payments'            => (!in_array($document->state_type_id, $status_type_id)) ? 0 : $document->payments->sum('payment'),

                ];
                /* Notas de credito o debito*/
                $notes = $document->getNotes();
            } 
            /** Documentos de Tipo Servicio tecnico */
            elseif ($cash_document->technical_service) {
                $usado = '<br>Se usan para cash<br>';
                $technical_service = $cash_document->technical_service;
                $cash_income += $technical_service->cost;
                $final_balance += $technical_service->cost;
                if (count($technical_service->payments) > 0) {
                    $usado = '<br>Se usan los pagos<br>';
                    $pays = $technical_service->payments;
                    foreach ($methods_payment as $record) {
                        $record->sum = ($record->sum + $pays->where('payment_method_type_id', $record->id)->sum('payment'));
                        if (!empty($record_total)) {
                            $usado .= self::getStringPaymentMethod($record->id).'<br>Se usan los pagos Tipo '.$record->id.'<br>';
                        }
                    }
                }
                $temp = [
                    'type_transaction'          => 'Venta',
                    'document_type_description' => 'Servicio técnico',
                    'number'                    => 'TS-'.$technical_service->id,//$value->document->number_full,
                    'date_of_issue'             => $technical_service->date_of_issue->format('Y-m-d'),
                    'date_sort'                 => $technical_service->date_of_issue,
                    'customer_name'             => $technical_service->customer->name,
                    'customer_number'           => $technical_service->customer->number,
                    'total'                     => $technical_service->cost,
                    'currency_type_id'          => 'PEN',
                    'usado'                     => $usado." ".__LINE__,
                    'tipo'                      => 'technical_service',
                    'total_payments'            => $technical_service->payments->sum('payment'),
                ];
            }
            /** Documentos de Tipo Gastos */
            elseif ($cash_document->expense_payment) {
                $expense_payment = $cash_document->expense_payment;
                //    $usado = '<br>No se usan pagos<br>';

                if ($expense_payment->expense->state_type_id == '05') {
                    $total = self::CalculeTotalOfCurency(
                        $expense_payment->payment,
                        $expense_payment->expense->currency_type_id,
                        $expense_payment->expense->exchange_rate_sale
                    );
                    //        $usado = '<br>Se usan para cash<br>';

                    $cash_egress += $total;
                    $final_balance -= $total;

                }
                $temp = [
                    'type_transaction'          => 'Gasto',
                    'document_type_description' => $expense_payment->expense->expense_type->description,
                    'number'                    => $expense_payment->expense->number,
                    'date_of_issue'             => $expense_payment->expense->date_of_issue->format('Y-m-d'),
                    'date_sort'                 => $expense_payment->expense->date_of_issue,
                    'customer_name'             => $expense_payment->expense->supplier->name,
                    'customer_number'           => $expense_payment->expense->supplier->number,
                    'total'                     => -$expense_payment->payment,
                    'currency_type_id'          => $expense_payment->expense->currency_type_id,
                    'usado'                     => $usado." ".__LINE__,

                    'tipo' => 'expense_payment',
                    'total_payments'            => -$expense_payment->payment,

                ];
            }
            /** Documentos de Tipo compras */
            else if ($cash_document->purchase) {

                /**
                 * @var \App\Models\Tenant\CashDocument $cash_document
                 * @var \App\Models\Tenant\Purchase $purchase
                 * @var \Illuminate\Database\Eloquent\Collection $payments
                 */
                $purchase = $cash_document->purchase;

                if (in_array($purchase->state_type_id, $status_type_id)) {

                    $payments = $purchase->purchase_payments;
                    $record_total = 0;
                    // $total = self::CalculeTotalOfCurency($purchase->total, $purchase->currency_type_id, $purchase->exchange_rate_sale);
                    // $cash_egress += $total;
                    // $final_balance -= $total;
                    if (count($payments) > 0) {
                        $pays = $payments;
                        foreach ($methods_payment as $record) {
                            $record_total = $pays->where('payment_method_type_id', $record->id)->sum('payment');
                            $record->sum = ($record->sum - $record_total);
                            $cash_egress += $record_total;
                            $final_balance -= $record_total;
                        }

                    }

                }

                $temp = [
                    'type_transaction'          => 'Compra',
                    'document_type_description' => $purchase->document_type->description,
                    'number'                    => $purchase->number_full,
                    'date_of_issue'             => $purchase->date_of_issue->format('Y-m-d'),
                    'date_sort'                 => $purchase->date_of_issue,
                    'customer_name'             => $purchase->supplier->name,
                    'customer_number'           => $purchase->supplier->number,
                    'total'                     => ((!in_array($purchase->state_type_id, $status_type_id)) ? 0 : $purchase->total),
                    'currency_type_id'          => $purchase->currency_type_id,
                    'usado'                     => $usado." ".__LINE__,
                    'tipo'                      => 'purchase',
                    'total_payments'            => (!in_array($purchase->state_type_id, $status_type_id)) ? 0 : $purchase->payments->sum('payment'),

                ];
            }
            /** Cotizaciones */
            else if ($cash_document->quotation) 
            {
                $quotation = $cash_document->quotation;

                // validar si cumple condiciones para usar registro en reporte
                if($quotation->applyQuotationToCash())
                {
                    if (in_array($quotation->state_type_id, $status_type_id)) 
                    {
                        $record_total = 0;
    
                        $total = self::CalculeTotalOfCurency(
                            $quotation->total,
                            $quotation->currency_type_id,
                            $quotation->exchange_rate_sale
                        );
    
                        $cash_income += $total;
                        $final_balance += $total;
    
                        if (count($quotation->payments) > 0) 
                        {
                            $pays = $quotation->payments;
                            foreach ($methods_payment as $record) {
                                $record_total = $pays->where('payment_method_type_id', $record->id)->sum('payment');
                                $record->sum = ($record->sum + $record_total);
                            }
                        }
                    }
    
                    $temp = [
                        'type_transaction'          => 'Venta (Pago a cuenta)',
                        'document_type_description' => 'COTIZACION  ',
                        'number'                    => $quotation->number_full,
                        'date_of_issue'             => $quotation->date_of_issue->format('Y-m-d'),
                        'date_sort'                 => $quotation->date_of_issue,
                        'customer_name'             => $quotation->customer->name,
                        'customer_number'           => $quotation->customer->number,
                        'total'                     => ((!in_array($quotation->state_type_id, $status_type_id)) ? 0 : $quotation->total),
                        'currency_type_id'          => $quotation->currency_type_id,
                        'usado'                     => $usado." ".__LINE__,
                        'tipo'                      => 'quotation',
                        'total_payments'            => (!in_array($quotation->state_type_id, $status_type_id)) ? 0 : $quotation->payments->sum('payment'),

                    ];

                }
                /** Cotizaciones */

            }


            if (!empty($temp)) {
                $temp['usado'] = isset($temp['usado']) ? $temp['usado'] : '--';
                $temp['total_string'] = self::FormatNumber($temp['total']);
                $temp['total_payments'] = self::FormatNumber($temp['total_payments']);
                $all_documents[] = $temp;
            }

            /** Notas de credito o debito */
            if ($notes !== null) {
                foreach ($notes as $note) {
                    $usado = 'Tomado para ';
                    /** @var \App\Models\Tenant\Note $note */
                    $sum = $note->isDebit();
                    $type = ($note->isDebit()) ? 'Nota de debito' : 'Nota de crédito';
                    $document = $note->getDocument();
                    if (in_array($document->state_type_id, $status_type_id)) {
                        $record_total = $document->getTotal();
                        /** Si es credito resta */
                        if ($sum) {
                            $usado .= 'Nota de debito';
                            $nota_debito += $record_total;
                            $final_balance += $record_total;
                            $usado .= "Id de documento {$document->id} - Nota de Debito /* $record_total * /<br>";
                        } else {
                            $usado .= 'Nota de credito';
                            $nota_credito += $record_total;
                            $final_balance -= $record_total;
                            $usado .= "Id de documento {$document->id} - Nota de Credito /* $record_total * /<br>";
                        }
                        $temp = [
                            'type_transaction'          => $type,
                            'document_type_description' => $document->document_type->description,
                            'number'                    => $document->number_full,
                            'date_of_issue'             => $document->date_of_issue->format('Y-m-d'),
                            'date_sort'                 => $document->date_of_issue,
                            'customer_name'             => $document->customer->name,
                            'customer_number'           => $document->customer->number,
                            'total'                     => (!in_array($document->state_type_id, $status_type_id)) ? 0
                                : $document->total,
                            'currency_type_id'          => $document->currency_type_id,
                            'usado'                     => $usado.' '.__LINE__,
                            'tipo'                      => 'document',
                        ];

                        $temp['usado'] = isset($temp['usado']) ? $temp['usado'] : '--';
                        $temp['total_string'] = self::FormatNumber($temp['total']);
                        $all_documents[] = $temp;
                    }

                }
            }

        }
//        $all_documents = collect($all_documents)->sortBy('date_sort')->all();
        /************************/
        /************************/
        $data['all_documents'] = $all_documents;
        $temp = [];

        foreach ($methods_payment as $index => $item) {
            $temp[] = [
                'iteracion' => $index + 1,
                'name'      => $item->name,
                'sum'       => self::FormatNumber($item->sum),
            ];
        }

        $data['nota_credito'] = $nota_credito;
        $data['nota_debito'] = $nota_debito;
        $data['methods_payment'] = $temp;
        $data['credit'] = self::FormatNumber($credit);
        $data['cash_beginning_balance'] = self::FormatNumber($cash->beginning_balance);
        $cash_final_balance = $final_balance + $cash->beginning_balance;
        $data['cash_egress'] = self::FormatNumber($cash_egress);
        $data['cash_final_balance'] = self::FormatNumber($cash_final_balance);

        $data['cash_income'] = self::FormatNumber($cash_income);

        //$cash_income = ($final_balance > 0) ? ($cash_final_balance - $cash->beginning_balance) : 0;
        return $data;
    }

    /**
     * @param int    $total
     * @param string $currency_type_id
     * @param int    $exchange_rate_sale
     *
     * @return float|int|mixed
     */
    public static function CalculeTotalOfCurency(
        $total = 0,
        $currency_type_id = 'PEN',
        $exchange_rate_sale = 1
    ) {
        if ($currency_type_id !== 'PEN') {
            $total = $total * $exchange_rate_sale;
        }
        return $total;
    }

    /**
     * Obtiene un array de status para sumarlos en los reportes
     *
     * @return string[]
     */
    public static function getStateTypeId(){
        return [
            '01', //Registrado
            '03', // Enviado
            '05', // Aceptado
            '07', // Observado
            // '09', // Rechazado
            // '11', // Anulado
            '13' // Por anular
        ];
    }

    /**
     * Genera un pdf basado en el formato deseado
     *
     * @param        $cash
     * @param string $format
     * @param integer $mm
     *
     * @return string
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    private function getPdf($cash, $format = 'ticket', $mm = null) {
        $data = $this->setDataToReport($cash);
        $quantity_rows = 30;//$cash->cash_documents()->count();

        $width = 78;
        if($mm != null) {
            $width = $mm - 2;
        }

        $view = view('pos::cash.report_pdf_'.$format, compact('data'));
        $html = $view->render();
        /*
        $html = view('pos::cash.report_pdf_' . $format,
            compact('cash', 'company', 'methods_payment','status_type_id'))->render();
        */
        if ($format === 'ticket') {
            $pdf = new Mpdf([
                                'mode'          => 'utf-8',
                                'format'        => [
                                    $width,
                                    190 +
                                    ($quantity_rows * 8),
                                ],
                                'margin_top'    => 3,
                                'margin_right'  => 3,
                                'margin_bottom' => 3,
                                'margin_left'   => 3,
                            ]);
        } else {
            $pdf = new Mpdf([
                                'mode' => 'utf-8',
                            ]);
        }

        $pdf->WriteHTML($html);

        return $pdf->output('', 'S');
    }

    /**
     * Reporte en Ticket formato cash_pdf_ticket
     *
     * @param $cash
     * @param integer $mm
     *
     * @return mixed
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function reportTicket($cash, $mm) {
        $temp = tempnam(sys_get_temp_dir(), 'cash_pdf_ticket_'.$mm);

        file_put_contents($temp, $this->getPdf($cash, 'ticket', $mm));

        return response()->file($temp);
    }

    /**
     * Reporte en A4 formato cash_pdf_a4
     *
     * @param $cash
     *
     * @return mixed
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function reportA4($cash) {
        $temp = tempnam(sys_get_temp_dir(), 'cash_pdf_a4');
        file_put_contents($temp, $this->getPdf($cash, 'a4'));

        return response()->file($temp);
    }

    /**
     * Reporte Excel de reporte de caja
     *
     * @param $cash
     *
     * @return Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function reportExcel($cash) {
        $data = $this->setDataToReport($cash);
        // dd($data);

        /*
         $cash = Cash::findOrFail($cash);
        $company = Company::first();
        $methods_payment = collect(PaymentMethodType::all())->transform(function ($row) {
            return (object)[
                'id' => $row->id,
                'name' => $row->description,
                'sum' => 0
            ];
        });
        set_time_limit(0);
        */

        $filename = "Reporte_POS - {$data['cash_user_name']} - {$data['cash_date_opening']} {$data['cash_time_opening']}";
        $report_cash_export = new ReportCashExport();
        $report_cash_export->setData($data);
        /*
        $report_cash_export ->cash($cash)
            ->company($company)
            ->methods_payment($methods_payment);
        */
        return $report_cash_export->download($filename.'.xlsx');
    }

    
    /**
     * 
     * Obtener datos para header de reporte
     *
     * @param  Cash $cash
     * @return array
     */
    public function getHeaderCommonDataToReport($cash)
    {

        $company = Company::select('name', 'number')->first();
        
        $data['cash_user_name'] = $cash->user->name;
        $data['cash_date_opening'] = $cash->date_opening;
        $data['cash_state'] = $cash->state;
        $data['cash_date_closed'] = $cash->date_closed;
        $data['cash_time_closed'] = $cash->time_closed;
        $data['cash_time_opening'] = $cash->time_opening;
        $data['company_name'] = $company->name;
        $data['company_number'] = $company->number;

        $establishment = $cash->user->establishment;
        $data['establishment_address'] = $establishment->address;
        $data['establishment_department_description'] = $establishment->department->description;
        $data['establishment_district_description'] = $establishment->district->description;

        $data['total_income'] = 0;
        $data['total_egress'] = 0;

        return $data;
    }

    
    /**
     * 
     * Generar reporte de ingresos y egresos por metodo de pago efectivo con destino caja
     *
     * @param  int $cash
     */
    public function reportCashIncomeEgress($cash)
    {

        $cash = Cash::findOrFail($cash);
        $data_payments = collect();
        $data = $this->getHeaderCommonDataToReport($cash);
        
        foreach ($cash->cash_documents as $cash_document) 
        {
            $model_associated = $cash_document->getDataModelAssociated();
            $payments = $model_associated->getCashPayments();

            $payments->each(function($payment) use($data_payments){
                $data_payments->push($payment);
            });
        }

        $data['total_income'] = $data_payments->where('type_transaction', 'income')->sum('payment');
        $data['total_egress'] = $data_payments->where('type_transaction', 'egress')->sum('payment');
        $data['total_balance'] = $data['total_income'] - $data['total_egress'];

        return $this->toPrintCashIncomeEgress(compact('data', 'data_payments'));

    }
    

    /**
     * Imprimir reporte de ingresos y egresos
     *
     * @param  array $data
     */
    public function toPrintCashIncomeEgress($data)
    {

        $view = view('pos::cash.reports.report_income_egress_pdf', $data);
        $html = $view->render();
        
        $pdf = new Mpdf(['mode' => 'utf-8']);
        $pdf->WriteHTML($html);

        $temp = tempnam(sys_get_temp_dir(), 'cash_report_income_egress_pdf');
        file_put_contents($temp, $pdf->output('', 'S'));

        return response()->file($temp);
    }


}
