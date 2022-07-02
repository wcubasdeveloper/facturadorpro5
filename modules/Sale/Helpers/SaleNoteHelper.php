<?php

namespace Modules\Sale\Helpers;

use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\CoreFacturalo\Requests\Api\Validation\Person;
use App\Models\Tenant\Item;
use App\CoreFacturalo\Requests\Api\Transform\Common\PersonTransform;
use App\CoreFacturalo\Requests\Api\Validation\Functions as FunctionsApi;
use App\CoreFacturalo\Requests\Inputs\Functions;
use App\Http\Controllers\SearchItemController;

class SaleNoteHelper
{
 
    public static function transformForOrder($inputs)
    {

        $totals = $inputs['totales'];

        $customer = PersonTransform::transform($inputs['datos_del_cliente_o_receptor']);

        $inputs_transform = [
            
            'establishment_id' => auth()->user()->establishment_id,
            'series_id' => Functions::valueKeyInArray($inputs, 'series_id'),
            'date_of_issue' => Functions::valueKeyInArray($inputs, 'fecha_de_emision'),
            'time_of_issue' => Functions::valueKeyInArray($inputs, 'hora_de_emision'),
            'document_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_documento'),
            'currency_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_moneda'),
            'exchange_rate_sale' => Functions::valueKeyInArray($inputs, 'factor_tipo_de_cambio', 1),
            'customer' => $customer,
            'customer_id' => FunctionsApi::person($customer, 'customers'),
            'total_prepayment' => Functions::valueKeyInArray($totals, 'total_anticipos', 0),
            'total_discount' => Functions::valueKeyInArray($totals, 'total_descuentos', 0),
            'total_charge' => Functions::valueKeyInArray($totals, 'total_cargos', 0),
            'total_exportation' => Functions::valueKeyInArray($totals, 'total_exportacion', 0),
            'total_free' => Functions::valueKeyInArray($totals, 'total_operaciones_gratuitas', 0),
            'total_taxed' => Functions::valueKeyInArray($totals, 'total_operaciones_gravadas', 0),
            'total_unaffected' => Functions::valueKeyInArray($totals, 'total_operaciones_inafectas', 0),
            'total_exonerated' => Functions::valueKeyInArray($totals, 'total_operaciones_exoneradas', 0),
            'total_igv' => Functions::valueKeyInArray($totals, 'total_igv', 0),
            'total_igv_free' => Functions::valueKeyInArray($totals, 'total_igv_operaciones_gratuitas', 0),
            'total_base_isc' => Functions::valueKeyInArray($totals, 'total_base_isc', 0),
            'total_isc' => Functions::valueKeyInArray($totals, 'total_isc', 0),
            'total_base_other_taxes' => Functions::valueKeyInArray($totals, 'total_base_otros_impuestos', 0),
            'total_other_taxes' => Functions::valueKeyInArray($totals, 'total_otros_impuestos', 0),
            'total_plastic_bag_taxes' => Functions::valueKeyInArray($totals, 'total_impuestos_bolsa_plastica', 0),
            'total_taxes' => Functions::valueKeyInArray($totals, 'total_impuestos', 0),
            'total_value' => Functions::valueKeyInArray($totals, 'total_valor', 0),
            'total' => Functions::valueKeyInArray($totals, 'total_venta', 0),
            'items' => self::items($inputs),
            'quantity_period' => 0,
            'payments' => [],
            'charges' => [],
            'discounts' => [],
            'guides' => [],

        ];



        return $inputs_transform;
    }


    private static function items($inputs)
    {

        if(key_exists('items', $inputs)) {

            $items = [];

            foreach ($inputs['items'] as $row) {
                
                $record_items = Item::where('internal_id', $row['codigo_interno'])->take(1)->get(); //necesario para transformar la coleccion y preparar el item
                $data_item = (SearchItemController::TransformToModalSaleNote($record_items))->first();

                //Se usa cuando se genera nv desde ecommerce - producto promoción
                $name_product_pdf = isset($row['nombre_producto_pdf']) ? ($row['nombre_producto_pdf'] ?? null) : null;
                
                $items[] = [
                    'item_id' => $data_item['id'],
                    'item' => $data_item,
                    'currency_type_id' => $inputs['codigo_tipo_moneda'],
                    'quantity' => Functions::valueKeyInArray($row, 'cantidad'),
                    'unit_value' => Functions::valueKeyInArray($row, 'valor_unitario'),
                    'affectation_igv_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_afectacion_igv'),
                    'total_base_igv' => Functions::valueKeyInArray($row, 'total_base_igv'),
                    'percentage_igv' => Functions::valueKeyInArray($row, 'porcentaje_igv'),
                    'total_igv' => Functions::valueKeyInArray($row, 'total_igv'), 
                    'price_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_precio'),
                    'internal_id' => $row['codigo_interno'],
                    'description' => trim($row['descripcion']),
                    'name' => Functions::valueKeyInArray($row, 'nombre'),
                    'second_name' => Functions::valueKeyInArray($row, 'nombre_secundario'),
                    'item_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_item', '01'),
                    'item_code' => Functions::valueKeyInArray($row, 'codigo_producto_sunat'),
                    'item_code_gs1' => Functions::valueKeyInArray($row, 'codigo_producto_gsl'),
                    'unit_price' => Functions::valueKeyInArray($row, 'precio_unitario'),
                    'input_unit_price_value' => Functions::valueKeyInArray($row, 'precio_unitario'),
                    'total_taxes' => Functions::valueKeyInArray($row, 'total_impuestos'),
                    'total_value' => Functions::valueKeyInArray($row, 'total_valor_item'), 
                    'total' => Functions::valueKeyInArray($row, 'total_item'),
                    //data adicional para compatibilidad al registrar nv
                    'system_isc_type_id' => null,
                    'total_base_isc' => 0,
                    'percentage_isc' => 0,
                    'total_isc' => 0,
                    'total_base_other_taxes' => 0,
                    'percentage_other_taxes' => 0,
                    'total_other_taxes' => 0,
                    'total_plastic_bag_taxes' => 0,
                    'input_unit_price_value' => 0,
                    'total_discount' => 0,
                    'total_charge' => 0,
                    'attributes' => [],
                    'charges' => [],
                    'discounts' => [],
                    'warehouse_id' => null,
                    'record_id' => null,
                    'IdLoteSelected' => null,
                    'document_item_id' => null,
                    'name_product_pdf' => $name_product_pdf,
                ];
            }

            return $items;
        }
        return null;
    }

}
