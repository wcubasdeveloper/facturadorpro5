<?php
namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Cash;
use App\Models\Tenant\CashDocument;
use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNote;


class CashController extends Controller
{

    /**
     * web service para recibo de documentos junto con caja
     * apertura y cierre de caja
     * creacion de relaciones de documentos con caja (notas de venta y facturas/boletas)
     *
     * @param $request {}
     * example:
     * beginningBalance: number
     * dateOpening: "Y-m-d"
     * timeOpening: "H:m:s"
     * internalsId: [{external_id: String, type: String NOTA|BOLETA|'' }]
     */
    public function storeRestaurant(Request $request) {

        $cash = new Cash();
        $cash->user_id = auth()->user()->id;
        $cash->date_opening = $request->dateOpening;
        $cash->time_opening = $request->timeOpening;
        $cash->date_closed = date('Y-m-d');
        $cash->time_closed = date('H:i:s');
        $cash->beginning_balance = (float)$request->beginningBalance;
        $cash->final_balance = 0;
        $cash->income = 0;
        $cash->state = 0;
        $cash->reference_number = $request->referenceNumber;
        $cash->apply_restaurant = 1;

        $cash->save();

        $total_documents = 0;

        // se recorren todos los externals id de la caja anteriormente creada
        // para registrar la relación con ella y acumular el monto total
        foreach ($request->internalsId as $row) {
            if($row['type'] == 'NOTA'){
                $sale_note = SaleNote::where('external_id', $row['external_id'])->first();
                $total_documents += (float)$sale_note->total;

                CashDocument::create([
                    'cash_id' => $cash->id,
                    'sale_note_id' => $sale_note->id,
                ]);
            } else {
                $document = Document::where('external_id', $row['external_id'])->first();
                $total_documents += (float)$document->total;

                CashDocument::create([
                    'cash_id' => $cash->id,
                    'document_id' => $document->id,
                ]);
            }
        }

        // se toman los montos anteriores para cerrar la caja
        $cash->income = $total_documents;
        $cash->final_balance = $cash->beginning_balance + $cash->income;
        $cash->save();





        return [
            'success' => true,
            'message' => 'Caja creada con éxito'
        ];
    }

}
