<?php

use App\Models\Tenant\ItemSet;

$purchseOrder = $document->purchase_order;
$stablihsment = $stablihsment ?? [
        'district' => '',
        'department' => '',
        'province' => '',
    ];
$discount = '';
$unit_price = '';
$unit_value = '';
$total = '';
$total_value = '';
/*
$item = $value->getModelItem();
$model = $item->model;
$platform = $item->getWebPlatformModel();
if ($platform !== null) {
 $platform = $platform->name;
}*/
$unit_price = number_format($value->unit_price, 2);
$total = number_format($value->total, 2);
$total_item_purchase = round($total_item_purchase, 2);
$utility_item = number_format($utility_item, 2);
$web_platform = '';
$purchase_unit_price = '';
$igv = '';
$system_isc_type_id = '';
$total_isc = '';
$total_plastic_bag_taxes = '';
$pack_prefix = '';
$unit_type_id = $value->item->unit_type_id;
$pack_price_prefix = '';

$apply_conversion_to_pen = $request_apply_conversion_to_pen == 'true';
$description_apply_conversion_to_pen = null;


if (!isset($qty)) {

    /** Item normal */
    $discount = $value->total_discount;
    $relation_item = $value->relation_item;
    $unit_price = $value->unit_price;
    $unit_value = $value->unit_value;

    $total = $value->total;

    // aplicar conversión si es que esta habilitada la configuracion
    if($apply_conversion_to_pen && $value->isCurrencyTypeUsd())
    {
        $total = $value->getConvertTotalToPen();
        $utility_item = round($total - $total_item_purchase, 2);
        $unit_price = $value->getConvertUnitPriceToPen();
        $description_apply_conversion_to_pen = '(Conv.)';
    }
    // aplicar conversión si es que esta habilitada la configuracion


    $total_value = $value->total_value;
    $web_platform = optional($relation_item->web_platform)->name;
    $purchase_unit_price = ($relation_item) ? $relation_item->purchase_unit_price : 0;
    $igv = $value->system_isc_type_id;
    $total_isc = $value->total_isc;
    $system_isc_type_id = $value->system_isc_type_id;
    $total_plastic_bag_taxes = $value->total_plastic_bag_taxes;
    $category = $relation_item->category->name;
    $brand = $relation_item->brand->name;

} else {
    /** Item desde un pack */
    $item = \App\Models\Tenant\Item::find($item->id);
    $pack_prefix = "(Item de pack) - ";
    $pack_price_prefix = "(pck) ";
    $unit_price = $item->sale_unit_price;
    $total_item_purchase = '';
    $utility_item = '';
    $relation_item = $item;
    $purchase_unit_price = ($relation_item) ? $relation_item->purchase_unit_price : 0;
    $total =number_format( $qty * (float)$purchase_unit_price,2);
    /*
     $set = ItemSet::where('individual_item_id',$item->id)->first();
    if($set !== null){
        $qty = $set->quantity;
    }
    */
    $item_web_platform = $item->getWebPlatformModel();
    if ($item_web_platform) {
        $web_platform = $item_web_platform->name;
    }
    $category = $item->category->name;
    $brand = $item->brand->name;
}

// Se debe pasar al modelo
$qty = $qty ?? $value->quantity;
$qty = number_format($qty, 2);
$description = (strlen($value->item->description) > 50) ? substr($value->item->description, 0, 50) : $value->item->description;
$description = $pack_prefix . $description;
$isSaleNote = ($document_type_id != '80' && $type == 'sale') ? true : false;

    $seller = \App\CoreFacturalo\Helpers\Template\ReportHelper::getSellerData($value);
    try{
        $user = $seller->name;
    }catch (ErrorException $e){
        $user = '';
    }

?>
<tr>
    <td class="celda">{{ $document->date_of_issue->format('Y-m-d') }}</td>
    <td class="celda">{{ $user}}</td>
    @if($isSaleNote)
        <td class="celda">{{ $stablihsment['district'] }}</td>
        <td class="celda">{{ $stablihsment['department'] }}</td>
        <td class="celda">{{ $stablihsment['province'] }}</td>
    @endif
    <td class="celda">{{ $document->series }}</td>
    <td class="celda">{{ $document->number }}</td>
    @if( $type == 'sale')
        <td class="celda">{{ $purchseOrder }} </td>
        <td class="celda">{{ $web_platform }}</td>

    @endif
    <td class="celda">{{ $document->customer->identity_document_type_id }}</td>
    <td class="celda">{{ $document->customer->number }}</td>
    <td class="celda">{{ $document->customer->name }}</td>
    <td class="celda">{{ $document->currency_type_id }} {{ $description_apply_conversion_to_pen ?? ''}}</td>
    <td class="celda">{{ $unit_type_id }}</td>
    <td class="celda">{{ $brand }}</td>
    <td class="celda">{{ $description }}</td>
    {{-- @if($type == 'sale' && $document_type_id === '80')
        <td class="celda">{{ $model }}</td>
    @endif --}}
    <td class="celda">{{ $category }}</td>
    <td class="celda">{{ $qty }}</td>
    <td class="celda">{{ number_format($unit_price, 2) }}</td>
    <td class="celda">{{(!empty($total)?$pack_price_prefix:'')}}{{ number_format($total, 2) }}</td>
    <td class="celda">{{(!empty($total_item_purchase)?$pack_price_prefix:'')}}{{ $total_item_purchase }}</td>
    <td class="celda">{{ $utility_item }}</td>
</tr>
