<?php

namespace App\CoreFacturalo\Requests\Api\Transform;

use App\CoreFacturalo\Requests\Api\Transform\Common\EstablishmentTransform;
use App\CoreFacturalo\Requests\Api\Transform\Common\PersonTransform;
use App\CoreFacturalo\Requests\Api\Transform\Common\ActionTransform;
use App\CoreFacturalo\Requests\Api\Transform\Common\LegendTransform;
use App\CoreFacturalo\Requests\Api\Transform\Common\OperationDataTransform;

class PurchaseSettlementTransform
{
    public static function transform($inputs)
    {

        // dd($inputs);

        $totals = $inputs['totales'];

        $inputs_transform = [
            'series' => Functions::valueKeyInArray($inputs, 'serie_documento'),
            'number' => Functions::valueKeyInArray($inputs, 'numero_documento'),
            'date_of_issue' => Functions::valueKeyInArray($inputs, 'fecha_de_emision'),
            'time_of_issue' => Functions::valueKeyInArray($inputs, 'hora_de_emision'),
            'operation_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_operacion'),
            'document_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_documento'),
            'currency_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_moneda'),
            // 'establishment' => EstablishmentTransform::transform($inputs['datos_del_emisor']),
            'supplier' => PersonTransform::transform($inputs['datos_del_proveedor_o_receptor']),
            'operation_data' => OperationDataTransform::transform($inputs['datos_de_la_operacion']),
            'exchange_rate_sale' => Functions::valueKeyInArray($inputs, 'factor_tipo_de_cambio', 1),
            'total_prepayment' => Functions::valueKeyInArray($totals, 'total_anticipos'),
            'total_taxed' => Functions::valueKeyInArray($totals, 'total_operaciones_gravadas'),
            'total_unaffected' => Functions::valueKeyInArray($totals, 'total_operaciones_inafectas'),
            'total_exonerated' => Functions::valueKeyInArray($totals, 'total_operaciones_exoneradas'),
            'total_igv' => Functions::valueKeyInArray($totals, 'total_igv'),
            'total_taxes' => Functions::valueKeyInArray($totals, 'total_impuestos'),
            'total_value' => Functions::valueKeyInArray($totals, 'total_valor'),
            'subtotal' => Functions::valueKeyInArray($totals, 'subtotal_compra'),
            'total' => Functions::valueKeyInArray($totals, 'total_compra'),
            'items' => self::items($inputs),
            'prepayments' => self::prepayments($inputs),
            'related' => self::related($inputs),
            'legends' => LegendTransform::transform($inputs),
            'observations' => Functions::valueKeyInArray($inputs, 'observaciones'),
            'actions' => ActionTransform::transform($inputs)
        ];

        return $inputs_transform;
    }


    private static function items($inputs)
    {
        if(key_exists('items', $inputs)) {
            $items = [];
            foreach ($inputs['items'] as $row) {
                $items[] = [
                    'internal_id' => $row['codigo_interno'],
                    'description' => $row['descripcion'],
                    'item_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_item', '01'),
                    'item_code' => Functions::valueKeyInArray($row, 'codigo_producto_sunat'),
                    'item_code_gs1' => Functions::valueKeyInArray($row, 'codigo_producto_gsl'),
                    'unit_type_id' => strtoupper($row['unidad_de_medida']),
                    'currency_type_id' => $inputs['codigo_tipo_moneda'],

                    'quantity' => Functions::valueKeyInArray($row, 'cantidad'),
                    'unit_value' => Functions::valueKeyInArray($row, 'valor_unitario'),
                    'price_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_precio'),
                    'unit_price' => Functions::valueKeyInArray($row, 'precio_unitario'),

                    'affectation_igv_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_afectacion_igv'),
                    'total_base_igv' => Functions::valueKeyInArray($row, 'total_base_igv'),
                    'percentage_igv' => Functions::valueKeyInArray($row, 'porcentaje_igv'),
                    'total_igv' => Functions::valueKeyInArray($row, 'total_igv'),

                    'total_taxes' => Functions::valueKeyInArray($row, 'total_impuestos'),
                    'total_value' => Functions::valueKeyInArray($row, 'total_valor_item'),
                    'total' => Functions::valueKeyInArray($row, 'total_item'),

                    'income_tax_affectation_igv_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_afectacion_igv_impuesto_renta'),
                    'income_retention_percentage' => Functions::valueKeyInArray($row, 'porcentaje_retencion_renta'),
                    'income_retention_amount' => Functions::valueKeyInArray($row, 'monto_retencion_renta'),
                ];
            }

            return $items;
        }
        return null;
    }
  

    private static function prepayments($inputs)
    {
        if(key_exists('anticipos', $inputs)) {
            $prepayments = [];
            foreach ($inputs['anticipos'] as $row)
            {
                $prepayments[] = [
                    'number' => $row['numero'],
                    'document_type_id' => $row['codigo_tipo_documento'],
                    'amount' => $row['monto']
                ];
            }

            return $prepayments;
        }
        return null;
    }
 
    private static function related($inputs)
    {
        if(key_exists('relacionados', $inputs)) {
            $related = [];
            foreach ($inputs['relacionados'] as $row)
            {
                $related[] = [
                    'number' => $row['numero'],
                    'document_type_id' => $row['codigo_tipo_documento'],
                    'amount' => $row['monto']
                ];
            }

            return $related;
        }
        return null;
    }
 
}