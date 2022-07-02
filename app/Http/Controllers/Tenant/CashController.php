<?php
namespace App\Http\Controllers\Tenant;

use App\Exports\CashProductExport;
use App\Exports\CashPaymentExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CashRequest;
use App\Http\Resources\Tenant\CashCollection;
use App\Http\Resources\Tenant\CashResource;
use App\Models\Tenant\Cash;
use App\Models\Tenant\CashDocument;
use App\Models\Tenant\Company;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Document;
use App\Models\Tenant\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Pos\Models\CashTransaction;
use App\Models\Tenant\CashDocumentCredit;
use Modules\Finance\Models\Income;
use App\CoreFacturalo\Helpers\Template\ReportHelper;
use Carbon\Carbon;


/**
 * Class CashController
 *
 * @package App\Http\Controllers\Tenant
 * @mixin  Controller
 */
class CashController extends Controller
{

    use FinanceTrait;

    public function index()
    {
        return view('tenant.cash.index');
    }

    public function columns()
    {
        return [
            'income' => 'Ingresos',
        ];
    }

    public function records(Request $request)
    {
        $records = Cash::where($request->column, 'like', "%{$request->value}%")
                        ->whereTypeUser()
                        ->orderBy('date_opening', 'DESC');


        return new CashCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        return view('tenant.items.form');
    }

    public function tables()
    {
        $user = auth()->user();
        $type = $user->type;
        $users = array();

        switch($type)
        {
            case 'admin':
                $users = User::where('type', 'seller')->get();
                $users->push($user);
                break;
            case 'seller':
                $users = User::where('id', $user->id)->get();
                break;
        }

        return compact('users', 'user');
    }

    public function opening_cash()
    {

        $cash = Cash::where([['user_id', auth()->user()->id],['state', true]])->first();

        return compact('cash');
    }

    public function opening_cash_check($user_id)
    {
        $cash = Cash::where([['user_id', $user_id],['state', true]])->first();
        return compact('cash');
    }


    public function record($id)
    {
        $record = new CashResource(Cash::findOrFail($id));

        return $record;
    }

    public function store(CashRequest $request) {

        $id = $request->input('id');

        DB::connection('tenant')->transaction(function () use ($id, $request) {

            $cash = Cash::firstOrNew(['id' => $id]);
            $cash->fill($request->all());

            if(!$id){
                $cash->date_opening = date('Y-m-d');
                $cash->time_opening = date('H:i:s');
            }

            $cash->save();

            $this->createCashTransaction($cash, $request);

        });


        return [
            'success' => true,
            'message' => ($id)?'Caja actualizada con éxito':'Caja aperturada con éxito'
        ];

    }


    public function createCashTransaction($cash, $request){

        $this->destroyCashTransaction($cash);

        $data = [
            'date' => date('Y-m-d'),
            'description' => 'Saldo inicial',
            'payment_method_type_id' => '01',
            'payment' => $request->beginning_balance,
            'payment_destination_id' => 'cash',
            'user_id' => $request->user_id,
        ];

        $cash_transaction = $cash->cash_transaction()->create($data);

        $this->createGlobalPaymentTransaction($cash_transaction, $data);

    }


    public function close($id) {

        $cash = Cash::findOrFail($id);

        // dd($cash->cash_documents);

        $cash->date_closed = date('Y-m-d');
        $cash->time_closed = date('H:i:s');

        $final_balance = 0;
        $income = 0;

        foreach ($cash->cash_documents as $cash_document) {


            if($cash_document->sale_note){

                if(in_array($cash_document->sale_note->state_type_id, ['01','03','05','07','13'])){
                    $final_balance += ($cash_document->sale_note->currency_type_id == 'PEN') ? $cash_document->sale_note->total : ($cash_document->sale_note->total * $cash_document->sale_note->exchange_rate_sale);
                }

                // $final_balance += $cash_document->sale_note->total;

            }
            else if($cash_document->document){

                if(in_array($cash_document->document->state_type_id, ['01','03','05','07','13'])){
                    $final_balance += ($cash_document->document->currency_type_id == 'PEN') ? $cash_document->document->total : ($cash_document->document->total * $cash_document->document->exchange_rate_sale);
                }

                // $final_balance += $cash_document->document->total;

            }
            else if($cash_document->expense_payment){

                if($cash_document->expense_payment->expense->state_type_id == '05'){
                    $final_balance -= ($cash_document->expense_payment->expense->currency_type_id == 'PEN') ? $cash_document->expense_payment->payment:($cash_document->expense_payment->payment  * $cash_document->expense_payment->expense->exchange_rate_sale);
                }

                // $final_balance -= $cash_document->expense_payment->payment;

            }
            else if($cash_document->purchase){
                if(in_array($cash_document->purchase->state_type_id, ['01','03','05','07','13'])){
                    if($cash_document->purchase->total_canceled == 1) {
                        $final_balance -= ($cash_document->purchase->currency_type_id == 'PEN') ? $cash_document->purchase->total : ($cash_document->purchase->total * $cash_document->purchase->exchange_rate_sale);
                    }
                    
                }
            }
            // cotizacion
            else if($cash_document->quotation)
            {
                $final_balance += ($cash_document->quotation->applyQuotationToCash()) ? $cash_document->quotation->getTransformTotal() : 0;
            }

            // else if($cash_document->purchase){
            //     $final_balance -= $cash_document->purchase->total;
            // }
            // else if($cash_document->expense){
            //     $final_balance -= $cash_document->expense->total;
            // }

        }

        $cash->final_balance = round($final_balance + $cash->beginning_balance, 2);
        $cash->income = round($final_balance, 2);
        $cash->state = false;
        $cash->save();

        return [
            'success' => true,
            'message' => 'Caja cerrada con éxito',
        ];

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function cash_document(Request $request) {

        $cash = Cash::where([
                                ['user_id', auth()->user()->id],
                                ['state', true],
                            ])->first();
        
        (int)$payment_credit = 0;
        

        if($request->document_id != null) {
            $document_id = $request->document_id;

            $document =  Document::find((int)$document_id);

                                            //credito
            if($document->payment_condition_id == '02')  {
                CashDocumentCredit::create([
                    'cash_id' => $cash->id,
                    'document_id' => $document_id
                ]);

                $payment_credit += 1;
            }
        }
        else if($request->sale_note_id != null) {

             $document_id = $request->sale_note_id;

             $document =  SaleNote::find((int)$document_id);

                                                //credito
             if($document->payment_method_type_id == '09')  {
                CashDocumentCredit::create([
                    'cash_id' => $cash->id,
                    'sale_note_id' => $document_id
                ]);

                $payment_credit += 1;
            }
        }
        else if($request->quotation_id != null) {

        }

        if($payment_credit == 0) {

            $req = [
                'document_id' => $request->document_id,
                'sale_note_id' => $request->sale_note_id,
                'quotation_id' => $request->quotation_id,
            ];

            $cash->cash_documents()->updateOrCreate($req);
        }
        
        return [
            'success' => true,
            'message' => 'Venta con éxito',
        ];
    }


    public function destroy($id)
    {

        $data = DB::connection('tenant')->transaction(function () use ($id) {

            $cash = Cash::findOrFail($id);

            if($cash->global_destination()->where('payment_type', '!=', CashTransaction::class)->count() > 0){
                return [
                    'success' => false,
                    'message' => 'No puede eliminar la caja, tiene transacciones relacionadas'
                ];
            }

            $this->destroyCashTransaction($cash);
            $cash->delete();

            return [
                'success' => true,
                'message' => 'Caja eliminada con éxito'
            ];

        });

        return $data;

    }


    public function destroyCashTransaction($cash){

        $ini_cash_transaction = $cash->cash_transaction;

        if($ini_cash_transaction){
            CashTransaction::find($ini_cash_transaction->id)->delete();
        }

    }


    public function report($cash) {
        

        $cash = Cash::query()->findOrFail($cash);
        $company = Company::query()->first();

        $methods_payment = collect(PaymentMethodType::all())->transform(function($row){
            return (object)[
                'id' => $row->id,
                'name' => $row->description,
                'sum' => 0
            ];
        });

        set_time_limit(0);

        $pdf = PDF::loadView('tenant.cash.report_pdf', compact("cash", "company", "methods_payment"));

        $filename = "Reporte_POS - {$cash->user->name} - {$cash->date_opening} {$cash->time_opening}";

        return $pdf->stream($filename.'.pdf');
    }

    public function report_general()
    {
        $cashes = Cash::select('id')->whereDate('date_opening', date('Y-m-d'))->pluck('id');
        $cash_documents =  CashDocument::whereIn('cash_id', $cashes)->get();
        // dd($cash_documents);

        $company = Company::first();
        set_time_limit(0);

        $pdf = PDF::loadView('tenant.cash.report_general_pdf', compact("cash_documents", "company"));
        $filename = "Reporte_POS";
        return $pdf->download($filename.'.pdf');

    }

    public function report_products($id, $is_garage = false)
    {

        $data = $this->getDataReport($id, $is_garage);
        $pdf = PDF::loadView('tenant.cash.report_product_pdf', $data);
        $filename = "Reporte_POS_PRODUCTOS - {$data['cash']->user->name} - {$data['cash']->date_opening} {$data['cash']->time_opening}";

        return $pdf->stream($filename.'.pdf');

    }

    public function report_products_excel($id)
    {

        $data = $this->getDataReport($id);
        $filename = "Reporte_POS_PRODUCTOS - {$data['cash']->user->name} - {$data['cash']->date_opening} {$data['cash']->time_opening}";

        $cashProductExport = new CashProductExport();
        $cashProductExport
            ->documents($data['documents'])
            ->company($data['company'])
            ->cash($data['cash']);
        // return $cashProductExport->view();
        return $cashProductExport
                ->download($filename.'.xlsx');

    }


    public function getDataReport($id, $is_garage = false)
    {

        $cash = Cash::findOrFail($id);
        $company = Company::first();
        $cash_documents =  CashDocument::getDocumentIdsReport($cash);
        ReportHelper::setBoolIsGarage($is_garage);

        $source = DocumentItem::with('document')->whereIn('document_id', $cash_documents)->get();

        $documents = collect($source)->transform(function(DocumentItem $row){

            $item = $row->item;
            $data = $row->toArray();
            $data['item'] =$item;
            $data['unit_value']=$data['unit_value']??0;
            $data['sub_total'] =$data['unit_value'] * $data['quantity'];
            $data['number_full'] = $row->document->number_full;
            $data['description'] = $row->item->description;
            $data['unit_type_id'] = $this->getUnitTypeId($row);
            $data['record_type'] = 'document_item';
            return $data;
        });

        $documents = $documents->merge($this->getSaleNotesReportProducts($cash));

        $documents = $documents->merge($this->getPurchasesReportProducts($cash));

        return compact("cash", "company", "documents", 'is_garage');

    }



    public function getSaleNotesReportProducts($cash)
    {

        $cd_sale_notes =  CashDocument::getSaleNoteIdsReport($cash);

        $sale_note_items = SaleNoteItem::with('sale_note')->whereIn('sale_note_id', $cd_sale_notes)->get();

        return collect($sale_note_items)->transform(function(SaleNoteItem $row){
            $item = $row->item;
            $data = $row->toArray();
            $data['item'] =$item;
            $data['unit_value']=$data['unit_value']??0;
            $data['sub_total'] =$data['unit_value'] * $data['quantity'];
            $data['number_full'] = $row->sale_note->number_full;
            $data['description'] = $row->item->description;
            $data['unit_type_id'] = $this->getUnitTypeId($row);
            $data['record_type'] = 'sale_note_item';
            return $data;
        });

    }


    public function getPurchasesReportProducts($cash)
    {

        $cd_purchases =  CashDocument::getPurchaseIdsReport($cash);

        $purchase_items = PurchaseItem::with('purchase')->whereIn('purchase_id', $cd_purchases)->get();

        return collect($purchase_items)->transform(function(PurchaseItem $row){

            $item = $row->item;
            $data = $row->toArray();
            $data['item'] =$item;
            $data['unit_value']=$data['unit_value']??0;
            $data['sub_total'] =$data['unit_value'] * $data['quantity'];
            $data['number_full'] = $row->purchase->number_full;
            $data['description'] = $row->item->description;
            $data['unit_type_id'] = $this->getUnitTypeId($row);
            $data['record_type'] = 'purchase_item';
            return $data;
        });

    }
    
    
    /**
     * @param  array $row
     * @return string
     */
    private function getUnitTypeId($row)
    {
        return $row->item->unit_type_id ?? null;
    }


    public function report_cash_excel($cash_id)
    {
        

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
        $type_payment = ['01'];

        // Metodos de pago de no credito
        $methods_payment_credit = PaymentMethodType::NonCredit()->get()->transform(function ($row) {
            return $row->id;
        })->toArray();

        $methods_payment = collect(PaymentMethodType::where('id','01')->get())->transform(function ($row) {
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
                            foreach ($methods_payment as $record) {
                                $record_total = $pays->where('payment_method_type_id', $record->id)->sum('payment');
                                $record->sum = ($record->sum + $record_total);
                            }
                        }
                    
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
            
            else if ($cash_document->document) {
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
                                }
                        }
                    }
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
            else if ($cash_document->technical_service) {
                
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
                        /* dd($payments[0]['payment_method_type_id']); */
                        $record_total = 0;
                        // $total = self::CalculeTotalOfCurency($purchase->total, $purchase->currency_type_id, $purchase->exchange_rate_sale);
                        // $cash_egress += $total;
                        // $final_balance -= $total;
                            if (count($payments) > 0) {
                                $pays = $payments;
                                foreach ($methods_payment as $record) {
                                    $record_total = $pays->where('payment_method_type_id', '01')->sum('payment');
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

        // finanzas ingresos
        $id_income=$cash->user_id;
        $incomes=Income::where('user_id', $id_income)->whereTypeUser();
        $date_closed = Carbon::now()->format('Y-m-d');
        if($cash->date_closed){
            
            $incomes=$incomes->whereBetween('date_of_issue',[$cash->date_opening,$cash->date_closed]);
        }else{
            $incomes=$incomes->whereBetween('date_of_issue',[$cash->date_opening,$date_closed]);
        }

        $incomes=$incomes->get();
        
        if (isset($incomes[0])) {

            $data['cash_documents_total'] = (int)$incomes->count();
            /* dd(isset($incomes[0])); */
            foreach ($incomes as $income) {
                
                if (in_array($income->state_type_id, $status_type_id)){
                    $payments=$income->payments;
                            $record_total = 0;
        
                            $total = self::CalculeTotalOfCurency(
                                $income->total,
                                $income->currency_type_id,
                                $income->exchange_rate_sale
                            );
        
                            $cash_income += $total;
                            $final_balance += $total;

        
                            if (count($income->payments) > 0) 
                            {
                                $pays = $income->payments;
                                foreach ($methods_payment as $record) {
                                    $record_total = $pays->where('payment_method_type_id', $record->id)->sum('payment');
                                    $record->sum = ($record->sum + $record_total);
                                }
                            }
                }
                /* dd((!in_array($income->state_type_id, $status_type_id)) ? 0 : $income->payments->sum('payment')); */
                $usado = '';
                $temp = [
                    'type_transaction'          => 'Venta (finanzas)',
                    'document_type_description' => $income->income_type->description,
                    'number'                    => $income->number,
                    'date_of_issue'             => $income->date_of_issue->format('Y-m-d'),
                    'date_sort'                 => $income->date_of_issue,
                    'customer_name'             => $income->customer,
                    'customer_number'           => $income->customer,
                    'total'                     => ((!in_array($income->state_type_id, $status_type_id)) ? 0 : $income->total),
                    'currency_type_id'          => $income->currency_type_id,
                    'usado'                     => $usado." ".__LINE__,
                    'tipo'                      => 'finance',
                    'total_payments'            => (!in_array($income->state_type_id, $status_type_id)) ? 0 : $income->payments->sum('payment'),
    
                ];
                
                if (!empty($temp)) {
                    $temp['usado'] = isset($temp['usado']) ? $temp['usado'] : '--';
                    $temp['total_string'] = self::FormatNumber($temp['total']);
                    $temp['total_payments'] = self::FormatNumber($temp['total_payments']);
                    $all_documents[] = $temp;
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
        /* return $data; */
        /* dd($data); */
        $filename = "Reporte_POS_EFECTIVO - {$cash->user->name} - {$cash->date_opening} {$cash->time_opening}";

        $cashPaymentExport = new CashPaymentExport();
        $cashPaymentExport
            ->data($data);
        // return $cashProductExport->view();
        return $cashPaymentExport
                ->download($filename.'.xlsx');

    }

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

    public static function FormatNumber($number = 0, $decimal = 2, $decimal_separador = '.', $miles_separador = '') {
        return number_format($number, $decimal, $decimal_separador, $miles_separador);
    }

    public static function getStringPaymentMethod($payment_id) {
        $payment_method = PaymentMethodType::find($payment_id);
        return (!empty($payment_method)) ? $payment_method->description : '';
    }


}
