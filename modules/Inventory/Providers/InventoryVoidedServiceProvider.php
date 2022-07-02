<?php

namespace Modules\Inventory\Providers;

use Modules\Order\Models\OrderNote;
use App\Models\Tenant\Document;  
use Illuminate\Support\ServiceProvider;
use Modules\Inventory\Traits\InventoryTrait;
use App\Models\Tenant\Dispatch;  
use App\Models\Tenant\Note;  

class InventoryVoidedServiceProvider extends ServiceProvider
{
    use InventoryTrait;

    public function register()
    {
    }
    
    public function boot()
    {
        $this->voided();
        $this->voided_order_note();
        $this->voided_dispatch();
        $this->verifyRelatedPrepaymentDocument();
    }

    private function voided()
    {
        //Revisar los tipos de documentos, ello varia el control de stock en las anulaciones.
        Document::updated(function ($document) {
            if($document['document_type_id'] == '01' || $document['document_type_id'] == '03'){
                if(in_array($document['state_type_id'], [ '09', '11' ], true)){
                    // $warehouse = $this->findWarehouse($document['establishment_id']);

                    foreach ($document['items'] as $detail) {
                        // dd($detail['item']->presentation);
                        
                        if(!$detail->item->is_set){

                            $warehouse = ($detail->warehouse_id) ? $this->findWarehouse($this->findWarehouseById($detail->warehouse_id)->establishment_id) : $this->findWarehouse($document['establishment_id']);

                            $presentationQuantity = (!empty($detail['item']->presentation)) ? $detail['item']->presentation->quantity_unit : 1;

                            $this->createInventoryKardex($document, $detail['item_id'], $detail['quantity'] * $presentationQuantity, $warehouse->id);

                            if(!$detail->document->sale_note_id && !$detail->document->order_note_id && !$detail->document->dispatch_id && !$detail->document->sale_notes_relateds){

                                $this->updateStock($detail['item_id'], $detail['quantity'] * $presentationQuantity, $warehouse->id);

                            }else{
                                
                                if($detail->document->dispatch){

                                    if(!$detail->document->dispatch->transfer_reason_type->discount_stock){
                                        // $warehouse = $this->findWarehouse($document['establishment_id']);
                                        $this->updateStock($detail['item_id'], $detail['quantity'] * $presentationQuantity, $warehouse->id);
                                    }
                                }
                            }

                            $this->updateDataLots($detail);

                        }
                        else{
                            
                            $this->voidedDocumentItemSet($detail);
            
                        }
                        
                    }

                    $this->voidedWasDeductedPrepayment($document);

                }
            }         
        });
    }

    
    private function voidedWasDeductedPrepayment($document)
    {

        if($document->prepayments){
            
            foreach ($document->prepayments as $row) {
                $fullnumber = explode('-', $row->number);
                $series = $fullnumber[0];
                $number = $fullnumber[1];

                $doc = Document::where([['series',$series],['number',$number]])->first();
                if($doc){
                    $doc->was_deducted_prepayment = false;
                    $doc->pending_amount_prepayment += $row->total;
                    $doc->save();
                }
            }
        }
        
    }
    
    /**
     * 
     * Verificar documento relacionado a la nota de credito para liberar el monto del anticipo informado
     *
     * @return void
     */
    private function verifyRelatedPrepaymentDocument()
    {

        Note::created(function ($note) {

            //si es nc y tiene tipo de nc igual a "Anulación de la operación"
            if($note->document->document_type_id === '07' && $note->note_credit_type_id === '01')
            {
                $affected_document = $note->affected_document;

                if($affected_document)
                {
                    //si el cpe relacionado tiene anticipos y el total de la nota es igual al del cpe afectado
                    if($affected_document->prepayments && $note->document->total == $affected_document->total)
                    {
                        foreach($affected_document->prepayments as $row) 
                        {
                            $number_full = explode('-', $row->number);
                            $find_document = Document::whereFilterWithOutRelations()->where([['series', $number_full[0]],['number', $number_full[1]]])->first();
    
                            if($find_document)
                            {
                                $find_document->pending_amount_prepayment += $row->total;
    
                                if($find_document->pending_amount_prepayment <= $find_document->total)
                                {
                                    $find_document->was_deducted_prepayment = false;
                                    $find_document->save();
                                }
                            }
                        }
                    }

                }
            }

        });

    }





    private function voided_order_note(){

        OrderNote::updated(function ($order_note) {

            if(in_array($order_note->state_type_id, [ '09', '11' ], true)){

                $warehouse = $this->findWarehouse($order_note->establishment_id);

                foreach ($order_note->items as $order_note_item) {

                    $presentationQuantity = (!empty($order_note_item->item->presentation)) ? $order_note_item->item->presentation->quantity_unit : 1;

                    $this->createInventoryKardex($order_note, $order_note_item->item_id, $order_note_item->quantity * $presentationQuantity, $warehouse->id);
                    $this->updateStock($order_note_item->item_id, $order_note_item->quantity * $presentationQuantity, $warehouse->id);

                }

            }

        });

    }


    
    private function voided_dispatch()
    {
        Dispatch::updated(function ($dispatch) {

            // dd($dispatch, $dispatch['state_type_id'],$dispatch->state_type_id);
            if($dispatch->transfer_reason_type->discount_stock){

                if(in_array($dispatch->state_type_id, [ '09', '11' ], true)){

                    $warehouse = $this->findWarehouse($dispatch->establishment_id);

                    foreach ($dispatch->items as $detail) {
                        
                        $this->createInventoryKardex($dispatch, $detail->item_id, $detail->quantity, $warehouse->id);

                        if(!$detail->dispatch->reference_sale_note_id && !$detail->dispatch->reference_order_note_id && !$detail->dispatch->reference_document_id){
                            $this->updateStock($detail->item_id, $detail->quantity, $warehouse->id);
                        }
                    
                        $this->updateDataLots($detail);
                    }
                }
            }
        });
    }


}
