<?php
function getLocationData($value, $type = 'sale')
{
    $customer = null;
    $district = '';
    $department = '';
    $province = '';
    $type_doc = null;
    if ($type == 'sale') {
        $type_doc = $value->document;
    }
    if (
        $value &&
        $type_doc &&
        $type_doc->customer
    ) {
        $customer = $type_doc->customer;
    }
    if ($customer != null) {
        if (
            $customer->district &&
            $customer->district->description
        ) {
            $district = $customer->district->description;
        }
        if (
            $customer->department &&
            $customer->department->description
        ) {
            $department = $customer->department->description;
        }
        if (
            $customer->province &&
            $customer->province->description
        ) {
            $province = $customer->province->description;
        }
    }
    return [
        'district' => $district,
        'department' => $department,
        'province' => $province,
    ];
}
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE PRODUCTOS</title>
    <style>
        html {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-spacing: 0;
            border: 1px solid black;
        }
        .celda {
            text-align: center;
            padding: 5px;
            border: 0.1px solid black;
            font-size: 9px;
        }
        th {
            padding: 5px;
            font-size: 9px;
            text-align: center;
            border-color: #0088cc;
            border: 0.1px solid black;
        }
        .title {
            font-weight: bold;
            padding: 5px;
            font-size: 20px !important;
            text-decoration: underline;
        }
        p > strong {
            margin-left: 5px;
            font-size: 13px;
        }
        thead {
            font-weight: bold;
            background: #0088cc;
            color: white;
            text-align: center;
        }
        @page {
            margin: 6px;
        }
    </style>
</head>
<body>
@if(!empty($records))
    <div class="">
        <div class=" ">
            <table class="" style="table-layout:fixed;">
                <thead>
                <?php
                $plus = 4;
                if ($document_type_id != '80' && $type == 'sale') {
                    // para añadir dpto, prov y dist se le resta 1 al width de 9 elementos
                    $plus = 3;
                }
                ?>
                <tr width="100%">
                    @include('report::general_items.partials.report_pdf_header',[
                                                  'document_type_id'=>$document_type_id,
                                                  'type'=>$type,
                                                  'plus'=>$plus,
                                              ])
                </tr>
                </thead>
                <tbody>
                @if($type == 'sale')
                    @if($document_type_id == '80')
                        @foreach($records as $key => $value)
                            <?php
                            if(isset($qty)) unset($qty);
                            /** @var \App\Models\Tenant\DocumentItem $value */
                            $series = '';
                            if (isset($value->item->lots)) {
                                $series_data = collect($value->item->lots)->where('has_sale', 1)->pluck('series')->toArray();
                                $series = implode(" - ", $series_data);
                            }
                            $total_item_purchase = \Modules\Report\Http\Resources\GeneralItemCollection::getPurchaseUnitPrice($value);
                            $utility_item = $value->total - $total_item_purchase;
                            /** @var \App\Models\Tenant\Item $item */
                            $item = $value->getModelItem();
                            $model = $item->model;
                            $document = $value->sale_note;
                            $platform = $item->getWebPlatformModel();
                            if ($platform !== null) {
                                $platform = $platform->name;
                            }
                            $pack = $item->getSetItems();
                            ?>
                            @include('report::general_items.partials.report_pdf_body_sale',[
                                      'document_type_id'=>$document_type_id,
                                      'document'=>$document,
                                      'type'=>$type,
                                      'value'=>$value,
                                      'key'=>$key,
                                      'item'=>$item,
                                  ])
                            @if($pack !== null)
                                @foreach($pack as $item_pack)
                                    <?php
                                    /** @var \App\Models\Tenant\ItemSet $item_pack */
                                    $value->item = $item_pack->individual_item;
                                    /** @var \App\Models\Tenant\Item $item */
                                    $item = $value->item;
                                    $qty = $item_pack->quantity;
                                    ?>
                                    @include('report::general_items.partials.report_pdf_body_sale',[
                                                                           'document_type_id'=>$document_type_id,
                                                                          'document'=>$document,
                                                                          'type'=>$type,
                                                                          'value'=>$value,
                                                                          'key'=>$key,
                                                                          'item'=>$item,
                                                                       ])
                                @endforeach
                            @endif
                        @endforeach
                    @else
                        @foreach($records as $key => $value)
                            <?php
                                if(isset($qty)) unset($qty);
                            /** @var \App\Models\Tenant\DocumentItem $value */
                            $series = '';
                            if (isset($value->item->lots)) {
                                $series_data = collect($value->item->lots)->where('has_sale', 1)->pluck('series')->toArray();
                                $series = implode(" - ", $series_data);
                            }
                            $total_item_purchase = \Modules\Report\Http\Resources\GeneralItemCollection::getPurchaseUnitPrice($value);
                            $utility_item = $value->total - $total_item_purchase;
                            $item = $value->getModelItem();
                            $model = $item->model;
                            /** @var  \App\Models\Tenant\Document $document */
                            $document = $value->document;
                            $purchseOrder = $document->purchase_order;
                            $platform = $item->getWebPlatformModel();
                            if ($platform !== null) {
                                $platform = $platform->name;
                            }
                            $pack = $item->getSetItems();
                            $item = $value->item;
                            $stablihsment = getLocationData($value, $type);
                            ?>
                            @include('report::general_items.partials.report_pdf_body_sale',[
                                                                  'document_type_id'=>$document_type_id,
                                                                  'document'=>$document,
                                                                  'type'=>$type,
                                                                  'value'=>$value,
                                                                  'key'=>$key,
                                                                  'item'=>$item,
                                                                  'stablihsment'=>$stablihsment,
                                                              ])
                            @if($pack !== null)
                                @foreach($pack as $item_pack)
                                    <?php
                                    /** @var \App\Models\Tenant\ItemSet $item_pack */
                                    $value->item = $item_pack->individual_item;
                                    /** @var \App\Models\Tenant\Item $item */
                                    $item = $value->item;
                                    $qty = $item_pack->quantity;
                                    // dd($item);
                                    ?>
                                    @include('report::general_items.partials.report_pdf_body_sale',
                                                                       [
                                                                           'document_type_id'=>$document_type_id,
                                                                           'document'=>$document,
                                                                           'type'=>$type,
                                                                           'value'=>$value,
                                                                           'key'=>$key,
                                                                           'item'=>$item,
                                                                           'qty'=>$qty,
                                                                           'stablihsment'=>$stablihsment,
                                                                       ])
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @else
                    @foreach($records as $key => $value)

                        <?php

                        /**@var \App\Models\Tenant\PurchaseItem $value */
                        /** @var  \App\Models\Tenant\Purchase $document */
                        $document = $value->purchase;
                        ?>
                        @include('report::general_items.partials.report_pdf_body_purchase',
                                                           [
                                                               'value'=>$value,
                                                               'document'=>$document,

                                                           ])

                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
@else
    <div>
        <p>No se encontraron registros.</p>
    </div>
@endif
</body>
</html>
