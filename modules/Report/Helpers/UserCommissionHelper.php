<?php

namespace Modules\Report\Helpers;

use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Person;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Item;
use Carbon\Carbon;
use App\CoreFacturalo\Helpers\Functions\FunctionsHelper;


class UserCommissionHelper
{

    public static function getCommission($user, $utilities){

        $type = $user->user_commission->type;
        $amount = $user->user_commission->amount;

        $commission = ($type == 'amount') ? $utilities['total_utility'] * $amount : ($utilities['total_utility'] * ($amount / 100));

        return number_format($commission, 2, ".", "");

    }


    public static function getUtilities($sale_notes, $documents){

        $sale_notes_utility = self::getUtilityRecords($sale_notes);
        $documents_utility = self::getUtilityRecords($documents);

        return [
            'sale_notes_utility' => number_format($sale_notes_utility, 2, ".", ""),
            'documents_utility' => number_format($documents_utility, 2, ".", ""),
            'total_utility' => number_format($documents_utility + $sale_notes_utility, 2, ".", ""),
        ];

    }


    public static function getUtilityRecords($records){

        return $records->sum(function($record){

            return $record->items->sum(function($item) use($record){

                $total_item_purchase = self::getPurchaseUnitPrice($item) * $item->quantity;
                $total_item_sale = self::calculateTotalCurrencyType($record, $item->total);
                $total_item = $total_item_sale - $total_item_purchase;
                
                return ($record->document_type_id === '07') ? $total_item * -1 : $total_item;
    
            });
             
        });

    }

    
    public static function getPurchaseUnitPrice($record){

        $purchase_unit_price = 0;

        if($record->item->unit_type_id != 'ZZ'){

            if($record->relation_item->purchase_unit_price > 0){

                $purchase_unit_price = $record->relation_item->purchase_unit_price;

            }else{

                $purchase_item = PurchaseItem::select('unit_price')->where('item_id', $record->item_id)->latest('id')->first();
                $purchase_unit_price = ($purchase_item) ? $purchase_item->unit_price : $record->unit_price;

            }

        }

        return $purchase_unit_price;
    }
    
    public static function calculateTotalCurrencyType($record, $total)
    {
        return ($record->currency_type_id === 'USD') ? $total * $record->exchange_rate_sale : $total;
    }

        
    /**
     * 
     * Obtener totales para reporte de comisiones
     * Usado en:
     * Modules\Report\Http\Resources\ReportCommissionCollection
     * Formato excel y pdf (blade) de reportes comisiones
     *
     * @param $user
     * @return array
     */
    public static function getDataForReportCommission($user, $request)
    {

        $requestInner = $request->all();
        $date_start = $requestInner['date_start'];
        $date_end = $requestInner['date_end'];
        $establishment_id = $requestInner['establishment_id'];
        $user_type = $requestInner['user_type'];
        $user_seller_id = $requestInner['user_seller_id'];

        $row_user_id = $user->id;

        FunctionsHelper::setDateInPeriod($requestInner, $date_start, $date_end);

        //si user_seller_id es null, en la consulta se usara el id del usuario de la fila
        $documents = Document::whereFilterCommission($date_start, $date_end, $establishment_id, $user_type, $user_seller_id, $row_user_id)->get();
        $sale_notes = SaleNote::whereFilterCommission($date_start, $date_end, $establishment_id, $user_type, $user_seller_id, $row_user_id)->get();
        
        $total_commision = 0;

        $total_transactions_document = $documents->count();
        $total_transactions_sale_note = $sale_notes->count();

        $acum_sales_document = $documents->sum(function($document){ return $document->getTotalByDocumentType();});
        $acum_sales_sale_note = $sale_notes->sum('total');

        $total_commision_document = self::getTotalCommision($documents);
        $total_commision_sale_note = self::getTotalCommision($sale_notes);

        return [
            'id' => $user->id,
            'user_name' => $user->name,

            'acum_sales' => number_format($acum_sales_document + $acum_sales_sale_note, 2),
            'acum_sales_document' => $acum_sales_document,
            'acum_sales_sale_note' => $acum_sales_sale_note,

            'total_commision' => number_format($total_commision_document + $total_commision_sale_note, 2),
            'total_commision_sale_note' => $total_commision_sale_note,
            'total_commision_document' => $total_commision_document,

            'total_transactions' => $total_transactions_document + $total_transactions_sale_note,
            'total_transactions_document' => $total_transactions_document,
            'total_transactions_sale_note' => $total_transactions_sale_note,
        ];

    }

        
    /**
     * Obtener total de comisiones
     * 
     * Usado para:
     * Documents
     * SaleNotes
     *
     * @param  mixed $records
     * @return float
     */
    public static function getTotalCommision($records){

        return $records->sum(function($record){

            return $record->items->sum(function($item) use($record){

                $total_commision = 0;

                if ($item->relation_item->commission_amount) 
                {
                    if (!$item->relation_item->commission_type || $item->relation_item->commission_type == 'amount') {
                        $total_commision = $item->quantity * $item->relation_item->commission_amount;
                    } else {
                        $total_commision = $item->quantity * $item->unit_price * ($item->relation_item->commission_amount / 100);
                    }
                }

                return ($record->document_type_id === '07') ? $total_commision * -1 : $total_commision;
            });
        });

    }


}
