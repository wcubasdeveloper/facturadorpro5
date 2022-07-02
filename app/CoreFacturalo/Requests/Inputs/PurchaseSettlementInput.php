<?php

namespace App\CoreFacturalo\Requests\Inputs;

use App\CoreFacturalo\Requests\Inputs\Common\ActionInput;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Requests\Inputs\Common\LegendInput;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\CoreFacturalo\Requests\Inputs\Common\OperationDataInput;
use App\Models\Tenant\Company;
use App\Models\Tenant\PurchaseSettlement;
use App\Models\Tenant\Item;
use Illuminate\Support\Str;

class PurchaseSettlementInput
{
    public static function set($inputs)
    {

        
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series'];
        $number = $inputs['number'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, PurchaseSettlement::class);

        Functions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, PurchaseSettlement::class);

        $filename = Functions::filename($company, $document_type_id, $series, $number);
        $establishment = EstablishmentInput::set($inputs['establishment_id']);
        // $establishment = $inputs['establishment'];
        $supplier = PersonInput::set($inputs['supplier_id']);
        // dd($supplier);

        $operation_data = OperationDataInput::set($inputs['operation_data']);
 
        $inputs['type'] = 'purchase_settlement';

        return [
            'type' => $inputs['type'],
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'establishment_id' => $inputs['establishment_id'],
            'establishment' => $establishment,
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.1',
            'filename' => $filename,
            'operation_data' => $operation_data,
            'exchange_rate_sale' => $inputs['exchange_rate_sale'],
            'operation_type_id' => $inputs['operation_type_id'],
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $inputs['date_of_issue'],
            'time_of_issue' => $inputs['time_of_issue'],
            'supplier_id' => $inputs['supplier_id'],
            'supplier' => $supplier,
            'currency_type_id' => $inputs['currency_type_id'],
            'total_prepayment' => Functions::valueKeyInArray($inputs, 'total_prepayment', 0),
            'total_taxed' => $inputs['total_taxed'],
            'total_unaffected' => $inputs['total_unaffected'],
            'total_exonerated' => $inputs['total_exonerated'],
            'total_igv' => $inputs['total_igv'],
            'total_taxes' => $inputs['total_taxes'],
            'total_value' => $inputs['total_value'],
            'subtotal' => $inputs['subtotal'],
            'total' => $inputs['total'],
            'items' => self::items($inputs),
            'prepayments' => self::prepayments($inputs),
            'related' => self::related($inputs),
            'legends' => LegendInput::set($inputs),
            'observations' => Functions::valueKeyInArray($inputs, 'observations'),
            'actions' => ActionInput::set($inputs)
        ];
    }

    private static function items($inputs)
    {
        if(array_key_exists('items', $inputs)) {

            $items = [];

            foreach ($inputs['items'] as $row) {

                $item = Item::find($row['item_id']);
                $items[] = [
                    'item_id' => $item->id,
                    'item' => [
                        'description' => $item->description,
                        'item_type_id' => $item->item_type_id,
                        'internal_id' => $item->internal_id,
                        'item_code' => $item->item_code,
                        'unit_type_id' => $item->unit_type_id,
                        'amount_plastic_bag_taxes' => $item->amount_plastic_bag_taxes
                    ],
                    'quantity' => $row['quantity'],
                    'unit_value' => $row['unit_value'],
                    'price_type_id' => $row['price_type_id'],
                    'unit_price' => $row['unit_price'],
                    'affectation_igv_type_id' => $row['affectation_igv_type_id'],
                    'total_base_igv' => $row['total_base_igv'],
                    'percentage_igv' => $row['percentage_igv'],
                    'total_igv' => $row['total_igv'],
                    'total_taxes' => $row['total_taxes'],
                    'total_value' => $row['total_value'],
                    'total' => $row['total'],
                ];
            }

            return $items;

        }
        return null;
    }
 
    private static function prepayments($inputs)
    {
        if(array_key_exists('prepayments', $inputs)) {
            if($inputs['prepayments']) {
                $prepayments = [];
                foreach ($inputs['prepayments'] as $row)
                {
                    $number = $row['number'];
                    $document_type_id = $row['document_type_id'];
                    $amount = $row['amount'];

                    $prepayments[] = [
                        'number' => $number,
                        'document_type_id' => $document_type_id,
                        'amount' => $amount
                    ];
                }
                return $prepayments;
            }
        }
        return null;
    }
 

    private static function related($inputs)
    {
        if(array_key_exists('related', $inputs)) {
            if($inputs['related']) {
                $related = [];
                foreach ($inputs['related'] as $row) {
                    $number = $row['number'];
                    $document_type_id = $row['document_type_id'];
                    $amount = $row['amount'];

                    $related[] = [
                        'number' => $number,
                        'document_type_id' => $document_type_id,
                        'amount' => $amount
                    ];
                }
                return $related;
            }
        }
        return null;
    }
 
}