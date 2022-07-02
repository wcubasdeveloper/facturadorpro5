<?php

namespace Modules\Inventory\Helpers;


use App\Models\Tenant\Item;
use App\Models\Tenant\{
    DocumentItem,
    DispatchItem,
    PurchaseItem,
};
use Carbon\Carbon;


class InventoryValuedKardex
{

    public static function getTransformRecords($records)
    {

        return $records->transform(function ($row, $key) {
            /** @var Item $row */
            return $row->getReportValuedKardexCollection();
            /*** Movido al modelo **/
            $values_records = self::getValuesRecords($row->document_items, $row->sale_note_items);

            $quantity_sale = $values_records['quantity_sale'];
            $total_sales = $values_records['total_sales'];

            $item_cost = $quantity_sale * $row->purchase_unit_price;
            $valued_unit = $total_sales - $item_cost;

            return [

                'id' => $row->id,
                'item_description' => $row->description,
                'category_description' => optional($row->category)->name,
                'brand_description' => optional($row->brand)->name,
                'unit_type_id' => $row->unit_type_id,
                'quantity_sale' => number_format($quantity_sale,2, ".", ""),
                'purchase_unit_price' => number_format($row->purchase_unit_price,2, ".", ""),
                'total_sales' => number_format($total_sales,2, ".", ""),
                'item_cost' => number_format($item_cost,2, ".", ""),
                'valued_unit' => number_format($valued_unit,2, ".", ""),
                'warehouses' => $row->warehouses->transform(function($row, $key){
                    return [
                        'id' => $row->id,
                        'stock' => $row->stock,
                        'warehouse_description' => $row->warehouse->description,
                        'description' => "{$row->warehouse->description} | {$row->stock}",
                    ];
                }),

            ];

        });

    }

    public static function getValuesRecords($document_items, $sale_note_items)
    {

        //quantity
        $quantity_doc_items = $document_items->sum(function($row){
            return ($row->document->document_type_id == '07') ? -$row->quantity : $row->quantity;
        });

        $quantity_sln_items = $sale_note_items->sum('quantity');

        $quantity_sale = $quantity_doc_items + $quantity_sln_items;


        //totals
        $sales_documents = $document_items->sum(function($row){
            $value_currency = self::calculateTotalCurrencyType($row->document, $row->total);
            return ($row->document->document_type_id == '07') ? -$value_currency : $value_currency;
        });

        $sales_sale_notes = $sale_note_items->sum(function($row){
            return self::calculateTotalCurrencyType($row->sale_note, $row->total);
        });

        $total_sales = $sales_documents + $sales_sale_notes;


        return [
            'quantity_sale' => $quantity_sale,
            'total_sales' => $total_sales,
        ];

    }


    public static function calculateTotalCurrencyType($record, $amount)
    {
        return ($record->currency_type_id === 'USD') ? $amount * $record->exchange_rate_sale : $amount;
    }


    public static function getDataFormatSunat($params)
    {

        $item = Item::whereFilterValuedKardexFormatSunat($params)->findOrFail($params->item_id);

        $purchase_items = $item->purchase_item;
        $document_items = $item->document_items;
        $dispatch_items = $item->dispatch_items;
        
        $all_record_items = ($purchase_items->merge($dispatch_items))->merge($document_items);

        // dd(($all_record_items));
        // dd(self::getRecordsFromItems($all_record_items));

        return [
            'item' => $item,
            'records' => self::getRecordsFromItems($all_record_items)
        ];

    }
 
    public static function getDataAdditional($request, $params, $item)
    {

        $data = [];
        $data['internal_id'] = $item->internal_id;
        $data['table_five'] = '01';
        $data['description'] = $item->description;
        $data['unit_type_table_six'] = $item->findUnitTypeCodeTableSix();

        // dd($request->all(), $params, $item);
        if($request->period == 'month'){
        
            $data['period'] = Carbon::parse($request->month_end)->format('Y');
            $data['month'] = Carbon::parse($request->month_end)->format('m');
        
        }else{
            
            $data['period'] = "{$params->date_start} - {$params->date_end}";
            $data['month'] = null;

        }

        return $data;

    }

    /**
     * Retorna arreglo ordenado que contiene informacion de los documentos asociados al item para poder realizar calculos en el reporte
     */
    private static function transformItems($collection)
    { 
        return $collection->transform(function($row, $key){
                    return self::getTempData($row);
                })
                ->sortBy('date_of_issue')
                ->sortBy('time_of_issue')
                ->values()
                ->all();
    }
     
    private static function getRecordsFromItems($collection)
    {

        // dd($collection);
        $new_collection = self::transformItems($collection);
        // dd($new_collection);

        $data = [];
        $balance_quantity = 0;
        $balance_total_cost = 0;
        $balance_unit_cost = 0;


        foreach ($new_collection as $key => $temp_data) {

            //buscar nota de credito y asignar valores, es necesario que se encuentre el doc relacionado 
            // en el arreglo, ya que desde el mismo obtiene el doc y su costo promedio

            if($temp_data['model_type'] == 'document' && $temp_data['document_type_id'] == '07'){

                $affected_document = collect($data)->first(function($row) use($temp_data){
                    return $row['model_type'] == 'document' && in_array($row['document_type_id'], ['01', '03']) && $row['id'] === $temp_data['affected_document_id'];
                });

                $temp_data['input_unit_price'] = $affected_document['output_unit_price'];
                $temp_data['input_total'] = $temp_data['input_unit_price'] * $temp_data['input_quantity'];
                $temp_data['total'] = $temp_data['input_unit_price'] * $temp_data['input_quantity'];
            }


            $balance_quantity +=  $temp_data['quantity'] * $temp_data['factor'];

            //asignar valor acumulado del documento previo del grupo saldo - campo costo unitario 
            if(isset($data[$key - 1]) && $temp_data['type'] == 'output')
            {
                $temp_data['output_unit_price'] = $data[$key - 1]['balance_unit_cost'];
                $temp_data['output_total'] = $temp_data['output_unit_price'] * $temp_data['output_quantity'];
            }

            // valores iniciales para el grupo saldos
            if($key == 0)
            {
                $balance_unit_cost = ($balance_quantity != 0) ? round($temp_data['total']  / $temp_data['quantity'] , 4) : null;
                $balance_total_cost += $temp_data['total'] * $temp_data['factor'];

            }else
            {
                // acumulado grupo saldo - columnas, total y costo unitario
                $balance_total_cost += ($temp_data['type'] == 'input') ? $temp_data['total'] * $temp_data['factor'] : $temp_data['output_total'] * $temp_data['factor'];
                $balance_unit_cost = ($balance_quantity != 0) ? round($balance_total_cost / $balance_quantity, 4) : null;
            }
            
            //asignar valores acumulados
            $temp_data['balance_quantity'] = $balance_quantity;
            $temp_data['balance_unit_cost'] = $balance_unit_cost;
            $temp_data['balance_total_cost'] = $balance_total_cost;

            $data[$key] = $temp_data;

        }

        return $data;

    }

    private static function getTempData($record_item)
    {
        
        $temp_data = [];

        if($record_item instanceof DocumentItem){

            $document = $record_item->document;
            $affected_document_id = null;

            if($document->document_type_id == '07'){
                $affected_document_id = $document->note->affected_document_id;
                $type = 'input';
            }else{
                $type ='output';
            }

            $input_quantity = null;
            $input_unit_price = null;
            $input_total = null;
            $output_quantity = null;
            $output_unit_price = null;
            $output_total = null;
            $operation_type = null;
            
            if($type == 'input'){
                
                $input_quantity =  $record_item->quantity;
                $input_unit_price =  $record_item->unit_price;
                $input_total = $record_item->total;
                $operation_type = 'DEVOLUCIÓN';
                $operation_type_code = '05';
                $factor = 1;

            }else{

                $output_quantity =  $record_item->quantity;
                $output_unit_price =  $record_item->unit_price;
                $output_total =  $record_item->total;
                $operation_type = 'VENTA';
                $factor = -1;
                $operation_type_code = '01';

            }
            // dd($document);

            $temp_data = [
                'id' => $document->id,
                'type' => $type,
                // 'type' => 'output',
                'model_type' => 'document',
                'date_of_issue' => $document->date_of_issue->format('d-m-Y'),
                'time_of_issue' => $document->time_of_issue,
                'document_type_id' => $document->document_type_id,
                'series' => $document->series,
                'number' => $document->number,
                'operation_type' => $operation_type,
                'operation_type_code' => $operation_type_code,

                'input_quantity' => $input_quantity,
                'input_unit_price' => $input_unit_price,
                'input_total' => $input_total,

                'output_quantity' => $output_quantity,
                'output_unit_price' => $output_unit_price,
                'output_total' => $output_total,

                'factor' => $factor,
                'quantity' => $record_item->quantity,
                'total' => $record_item->total,
                
                'balance_quantity' => 0,
                'balance_unit_cost' => 0,
                'balance_total_cost' => 0,

                'affected_document_id' => $affected_document_id,
            ];

        }else if($record_item instanceof PurchaseItem){

            $document = $record_item->purchase;

            $temp_data = [
                'id' => $document->id,
                'type' => 'input',
                'model_type' => 'purchase',
                'date_of_issue' => $document->date_of_issue->format('d-m-Y'),
                'time_of_issue' => $document->time_of_issue,
                'document_type_id' => $document->document_type_id,
                'series' => $document->series,
                'number' => $document->number,
                'operation_type' => 'COMPRA',
                'operation_type_code' => '02',

                'input_quantity' => $record_item->quantity,
                'input_unit_price' => $record_item->unit_price,
                'input_total' => $record_item->total,

                'output_quantity' => null,
                'output_unit_price' => null,
                'output_total' => null,

                'factor' => 1,
                'quantity' => $record_item->quantity,
                'total' => $record_item->total,
                
                'balance_quantity' => 0,
                'balance_unit_cost' => 0,
                'balance_total_cost' => 0,
                'affected_document_id' => null,
            ];

        }else if($record_item instanceof DispatchItem){


            $type = (in_array($record_item->dispatch->transfer_reason_type_id, ['01', '04', '13'])) ? 'output' : 'input';

            // $type = ($record_item->dispatch->transfer_reason_type_id == '01') ? 'output' : 'input';
            $document = $record_item->dispatch;

            $input_quantity = null;
            $input_unit_price = null;
            $input_total = null;
            $output_quantity = null;
            $output_unit_price = null;
            $output_total = null;
            $operation_type = null;
            
            if($type == 'input'){
                
                $input_quantity =  $record_item->quantity;
                $input_unit_price =  $record_item->relation_item->purchase_unit_price;
                $input_total = $record_item->quantity * $record_item->relation_item->purchase_unit_price;
                $operation_type = 'COMPRA';
                $operation_type_code = $record_item->dispatch->transfer_reason_type_id;
                $factor = 1;

            }else{

                $output_quantity =  $record_item->quantity;
                $output_unit_price =  $record_item->relation_item->sale_unit_price;
                $output_total =  $record_item->quantity * $record_item->relation_item->sale_unit_price;

                $operation_type = null;
                $operation_type_code = null;

                if($document->transfer_reason_type_id == '04'){
                    $operation_type = $document->transfer_reason_type->description;
                    $operation_type_code = '11';

                }elseif($document->transfer_reason_type_id == '13'){
                    $operation_type = $document->transfer_reason_description ?? $document->transfer_reason_type->description;
                    $operation_type_code = '99';

                }else{
                    $operation_type = 'VENTA';
                    $operation_type_code = $record_item->dispatch->transfer_reason_type_id;
                }

                $factor = -1;

            }
            // dd($document);
            $temp_data = [
                'id' => $document->id,
                'type' => $type,
                'model_type' => 'dispatch',
                'date_of_issue' => $document->date_of_issue->format('d-m-Y'),
                'time_of_issue' => $document->time_of_issue,
                'document_type_id' => $document->document_type_id,
                'series' => $document->series,
                'number' => $document->number,
                'operation_type' => $operation_type,
                'operation_type_code' => $operation_type_code,
                // 'operation_type_code' => $record_item->dispatch->transfer_reason_type_id,
                'input_quantity' =>  $input_quantity,
                'input_unit_price' => $input_unit_price,
                'input_total' => $input_total,

                'output_quantity' => $output_quantity,
                'output_unit_price' => $output_unit_price,
                'output_total' => $output_total,

                'factor' => $factor,
                'quantity' => $record_item->quantity,
                'total' => $output_total ?? $input_total,

                'balance_quantity' => 0,
                'balance_unit_cost' => 0,
                'balance_total_cost' => 0,
                'affected_document_id' => null,

            ];

        }

        return $temp_data;
    }
 
}
