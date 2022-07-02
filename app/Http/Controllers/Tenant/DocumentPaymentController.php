<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentPaymentRequest;
use App\Http\Requests\Tenant\DocumentRequest;
use App\Http\Resources\Tenant\DocumentPaymentCollection;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\PaymentMethodType;
use App\Exports\DocumentPaymentExport;
use Exception, Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Finance\Traits\FilePaymentTrait;
use Carbon\Carbon;
use App\Models\Tenant\CashDocumentCredit;
use App\Models\Tenant\Cash;



class DocumentPaymentController extends Controller
{

    use FinanceTrait, FilePaymentTrait;

    public function records($document_id)
    {
        $records = DocumentPayment::where('document_id', $document_id)->get();

        return new DocumentPaymentCollection($records);
    }

    public function tables()
    {
        return [
            'payment_method_types' => PaymentMethodType::all(),
            'payment_destinations' => $this->getPaymentDestinations(),
            'permissions' => auth()->user()->getPermissionsPayment()
        ];
    }

    public function document($document_id)
    {
        $document = Document::find($document_id);

        $total_paid = collect($document->payments)->sum('payment');
        $total = $document->total;
        $credit_notes_total = $document->getCreditNotesTotal();

        $total_difference = round($total - $total_paid - $credit_notes_total, 2);
        // $total_difference = round($total - $total_paid, 2);

        return [
            'number_full' => $document->number_full,
            'total_paid' => $total_paid,
            'total' => $total,
            'total_difference' => $total_difference,
            'currency_type_id' => $document->currency_type_id,
            'exchange_rate_sale' => (float) $document->exchange_rate_sale,
            'credit_notes_total' => $credit_notes_total
        ];

    }

    public function store(DocumentPaymentRequest $request)
    {
        // dd($request->all());

        $id = $request->input('id');

        DB::connection('tenant')->transaction(function () use ($id, $request) {

            $record = DocumentPayment::firstOrNew(['id' => $id]);
            $record->fill($request->all());
            $record->save();
            $this->createGlobalPayment($record, $request->all());
            $this->saveFiles($record, $request, 'documents');

        });

        $document_balance = (object)$this->document($request->document_id);

        if($document_balance->total_difference < 1) {

            $credit = CashDocumentCredit::where([
                ['status', 'PENDING'],
                ['document_id',  $request->document_id]
            ])->first();

            if($credit) {

                $cash = Cash::where([
                    ['user_id', auth()->user()->id],
                    ['state', true],
                ])->first();

                $credit->status = 'PROCESSED';
                $credit->cash_id_processed = $cash->id;
                $credit->save();

                $req = [
                    'document_id' => $request->document_id,
                    'sale_note_id' => null
                ];

                $cash->cash_documents()->updateOrCreate($req);

            }

        }




        return [
            'success' => true,
            'message' => ($id)?'Pago editado con éxito':'Pago registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $item = DocumentPayment::findOrFail($id);
        $item->delete();

        return [
            'success' => true,
            'message' => 'Pago eliminado con éxito'
        ];
    }

    public function initialize_balance()
    {

        DB::connection('tenant')->transaction(function (){

            $documents = Document::get();

            foreach ($documents as $document) {

                $total_payments = $document->payments->sum('payment');

                $balance = $document->total - $total_payments;

                if($balance <= 0){

                    $document->total_canceled = true;
                    $document->update();

                }else{

                    $document->total_canceled = false;
                    $document->update();
                }

            }

        });

        return [
            'success' => true,
            'message' => 'Acción realizada con éxito'
        ];
    }

    public function  report($start, $end, $type = 'pdf')
    {
        $documents = DocumentPayment::whereBetween('date_of_payment', [$start , $end])->get();

        $records = collect($documents)->transform(function($row){
            return [
                'id' => $row->id,
                'date_of_payment' => $row->date_of_payment->format('d/m/Y'),
                'payment_method_type_description' => $row->payment_method_type->description,
                'destination_description' => ($row->global_payment) ? $row->global_payment->destination_description:null,
                'change' => $row->change,
                'payment' => $row->payment,
                'reference' => $row->reference,
                'customer' => $row->document->customer->name,
                'number'=>  $row->document->number_full,
                'total' => $row->document->total,
            ];
        });

        if ($type == 'pdf') {
            $pdf = PDF::loadView('tenant.document_payments.report', compact("records"));

            $filename = "Reporte_Pagos";

            return $pdf->stream($filename.'.pdf');
        } elseif ($type == 'excel') {
            $filename = "Reporte_Pagos";

            // $pdf = PDF::loadView('tenant.document_payments.report', compact("records"))->download($filename.'.xlsx');

            // return $pdf->stream($filename.'.xlsx');

            return (new DocumentPaymentExport)
                ->records($records)
                ->download($filename.Carbon::now().'.xlsx');
        }

    }

}
