<?php
namespace Modules\Restaurant\Http\Controllers;

use App\Exports\CashProductExport;
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
        return view('restaurant::cash.index');
    }

    public function posFilter()
    {
        return view('restaurant::cash.filter_pos');
    }

    public function columns()
    {
        return [
            'income' => 'Ingresos',
        ];
    }

    public function records(Request $request)
    {
        $records = Cash::where('apply_restaurant', 1)
                        ->where($request->column, 'like', "%{$request->value}%")
                        ->where('reference_number', 'like', "%{$request->reference_number}%")
                        ->whereTypeUser()
                        ->orderBy('date_closed', 'DESC')
                        ->orderBy('time_closed', 'DESC');


        return new CashCollection($records->paginate(config('tenant.items_per_page')));
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
        $cashes = Cash::select('id')->where('apply_restaurant', 1)->whereDate('date_opening', date('Y-m-d'))->pluck('id');
        $cash_documents =  CashDocument::whereIn('cash_id', $cashes)->get();
        // dd($cash_documents);

        $company = Company::first();
        set_time_limit(0);

        $pdf = PDF::loadView('tenant.cash.report_general_pdf', compact("cash_documents", "company"));
        $filename = "Reporte_POS";
        return $pdf->download($filename.'.pdf');

    }

    public function report_products($id)
    {

        $data = $this->getDataReport($id);
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


    public function getDataReport($id){

        $cash = Cash::findOrFail($id);
        $company = Company::first();
        $cash_documents =  CashDocument::select('document_id')->where('cash_id', $cash->id)->get();

        $source = DocumentItem::with('document')->whereIn('document_id', $cash_documents)->get();

        $documents = collect($source)->transform(function(DocumentItem $row){
            $item = $row->item;
            $data = $row->toArray();
            $data['item'] =$item;
            $data['unit_value']=$data['unit_value']??0;
            $data['sub_total'] =$data['unit_value'] * $data['quantity'];
            $data['number_full'] = $row->document->number_full;
            $data['description'] = $row->item->description;
            return $data;
        });

        $documents = $documents->merge($this->getSaleNotesReportProducts($cash));

        $documents = $documents->merge($this->getPurchasesReportProducts($cash));

        return compact("cash", "company", "documents");

    }



    public function getSaleNotesReportProducts($cash){

        $cd_sale_notes =  CashDocument::select('sale_note_id')->where('cash_id', $cash->id)->get();

        $sale_note_items = SaleNoteItem::with('sale_note')->whereIn('sale_note_id', $cd_sale_notes)->get();

        return collect($sale_note_items)->transform(function(SaleNoteItem $row){
            $item = $row->item;
            $data = $row->toArray();
            $data['item'] =$item;
            $data['unit_value']=$data['unit_value']??0;
            $data['sub_total'] =$data['unit_value'] * $data['quantity'];
            $data['number_full'] = $row->sale_note->number_full;
            $data['description'] = $row->item->description;
            return $data;
        });

    }


    public function getPurchasesReportProducts($cash){

        $cd_purchases =  CashDocument::select('purchase_id')->where('cash_id', $cash->id)->get();
        $purchase_items = PurchaseItem::with('purchase')->whereIn('purchase_id', $cd_purchases)->get();

        return collect($purchase_items)->transform(function(PurchaseItem $row){

            $item = $row->item;
            $data = $row->toArray();
            $data['item'] =$item;
            $data['unit_value']=$data['unit_value']??0;
            $data['sub_total'] =$data['unit_value'] * $data['quantity'];
            $data['number_full'] = $row->purchase->number_full;
            $data['description'] = $row->item->description;
            return $data;
        });

    }

}
