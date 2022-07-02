<?php

    use App\Models\Tenant\Document;
    use App\Models\Tenant\ItemSet;
    use App\CoreFacturalo\Helpers\Template\TemplateHelper;
    use App\Models\Tenant\SaleNote;

    $data = \Modules\Report\Http\Resources\GeneralItemCollection::getDocument($value);
    if ($document_type_id == '80') {
        $observation = $data['observation']?$data['observation']:'';
    } else {
        $observation = $data['additional_information']?$data['additional_information'][0]:'';
    }
    
    
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
$total_item_purchase = round($total_item_purchase, 2);
$utility_item = number_format($utility_item, 2);
$relation_item = $value->relation_item;
$web_platform = '';
$purchase_unit_price = '';
$igv = '';
$system_isc_type_id = '';
$total_isc = '';
$total_plastic_bag_taxes = '';
$pack_prefix = '';
$pack_price_prefix = '';
$apply_conversion_to_pen = $request_apply_conversion_to_pen == 'true';
$description_apply_conversion_to_pen = null;

if (!isset($qty)) {
    /** Item normal */
    $discount = $value->total_discount;
    $unit_price = $value->unit_price;
    $unit_value = $value->unit_value;
    $total = $value->total;
    $total_value = $value->total_value;
    $web_platform = optional($relation_item->web_platform)->name;
    $purchase_unit_price = ($relation_item) ? $relation_item->purchase_unit_price : 0;
    $igv = $value->total_igv;
    // $igv = $value->system_isc_type_id;
    $total_isc = $value->total_isc;
    $system_isc_type_id = $value->system_isc_type_id;
    $total_plastic_bag_taxes = $value->total_plastic_bag_taxes;
    $category = $relation_item->category->name;
    $brand = $relation_item->brand->name;

    
    // aplicar conversión si es que esta habilitada la configuracion
    if($apply_conversion_to_pen && $value->isCurrencyTypeUsd())
    {
        $total = round($value->getConvertTotalToPen(), 2);
        $utility_item = round($total - $total_item_purchase, 2);
        $unit_price = round($value->getConvertUnitPriceToPen(), 6);
        $unit_value = round($value->getConvertUnitValueToPen(), 6);
        $total_value = round($value->getConvertTotalValueToPen(), 2);
        $igv = round($value->getConvertTotalIgvToPen(), 2);
        $total_isc = round($value->getConvertTotalIscToPen(), 2);
        $description_apply_conversion_to_pen = '(Se aplicó conversión a soles)';
    }
    // aplicar conversión si es que esta habilitada la configuracion


} else {
    /** Item desde un pack */
    $item = \App\Models\Tenant\Item::find($item->id);
    $pack_prefix = "(Item de pack) - ";
    $pack_price_prefix = "(pck) ";
    $unit_price = $item->sale_unit_price;
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
$isSaleNote = ($document_type_id != '80' && $type == 'sale') ? true : false;


    $payments= [];
    if(
        get_class($document) == Document::class ||
        get_class($document) == SaleNote::class
    ){
        $payments = TemplateHelper::getDetailedPayment($document);
    }


    $seller = \App\CoreFacturalo\Helpers\Template\ReportHelper::getSellerData($value);
    try{
        $user = $seller->name;
    }catch (ErrorException $e){
        $user = '';
    }

    $warehouse_description = \App\CoreFacturalo\Helpers\Template\ReportHelper::getWarehouseDescription($value, $document);

?>
<tr>
    <td class="celda">{{ $loop->iteration }}</td>
    <td class="celda">{{ $document->date_of_issue->format('Y-m-d') }}</td>
    <td class="celda">{{-- {{ $user}} --}}
        @if($type==='sale')
            {{ $document->seller_id == null ? $document->user->name : $document->seller->name }}
        @else
            {{$user}}
        @endif
    </td>
    @if($isSaleNote)
        <td class="celda">{{ $stablihsment['district'] }}</td>
        <td class="celda">{{ $stablihsment['department'] }}</td>
        <td class="celda">{{ $stablihsment['province'] }}</td>
    @endif
    <td class="celda">
        @if($isSaleNote)
            {{ $document->document_type->description }}
        @else
            NOTA DE VENTA
        @endif
    </td>
    <td class="celda">
        @if($isSaleNote)
            {{ $document->document_type_id }}
        @else
            80
        @endif
    </td>
    <td class="celda">{{ $document->series }}</td>
    <td class="celda">{{ $document->number }}</td>
    <td class="celda">{{ $purchseOrder }}</td>
    <td class="celda">{{ $web_platform }}</td>
    <td class="celda">{{ $document->state_type_id == '11' ? 'SI':'NO' }}</td>
    <td class="celda">{{ $document->customer->identity_document_type->description }}</td>
    <td class="celda">{{ $document->customer->number }}</td>
    <td class="celda">{{ $document->customer->name }}</td>
    <td class="celda">
        @if($isSaleNote)
            {{ $document->seller_id == null ? $document->user->name : $document->seller->name }}
        @else
            {{$document->user->name}}
        @endif
    </td>
    <td class="celda">{{ $observation }} </td>
    <td class="celda">{{ $document->currency_type_id }} {{ $description_apply_conversion_to_pen ?? ''}}</td>
    <td class="celda">{{ $document->exchange_rate_sale }}</td>
    <td class="celda">
        @if($isSaleNote)
            {{ $item->unit_type_id }}
        @else
            {{$relation_item->unit_type->description}}
        @endif
    </td>
    <td class="celda">
        @if($isSaleNote)
            {{ $item->internal_id }}
        @else
            {{$relation_item->internal_id}}
        @endif
    </td>
    {{--TODO renombrar correctamente isSaleNote, deberia hacer referencia a nv, no a otros tipos de docs --}}
    @if($type == 'sale')
    <td class="celda">
        {{-- {{ $document->additional_information ? implode(' | ', $document->additional_information) : '' }}  --}}
        {{ $document->reference_data }}
    </td>
    @endif
    <td class="celda">{{ $pack_prefix }}{{ $item->description }}</td>
    <td class="celda">{{ $qty }}</td>
    <td>
        @foreach ($payments as $payment)
            @foreach ($payment as $pay)
                {{ $pay['description'] }}
                @if ($loop->count > 1 && !$loop->last)
                    <br>
                @endif
            @endforeach
        @endforeach
    </td>
    <td class="celda">{{ $series }}</td>
    <td class="celda">{{ $model }}</td>
    <td class="celda">{{(!empty($purchase_unit_price)?$pack_price_prefix:'')}}{{ $purchase_unit_price }}</td>
    <td class="celda">{{ $unit_value }}</td>
    <td class="celda">{{ $unit_price }}</td>
    <td class="celda">{{ $discount }}</td>
    <td class="celda">{{ $total_value }}</td>
    <td class="celda">{{ $value->affectation_igv_type_id }}</td>
    <td class="celda">{{ $igv }}</td>
    <td class="celda">{{ $system_isc_type_id }}</td>
    <td class="celda">{{ $total_isc }}</td>
    <td class="celda">{{ $total_plastic_bag_taxes }}</td>
    <td class="celda">{{(!empty($total)?$pack_price_prefix:'')}}{{ $total }}</td>
    <td class="celda">{{(!empty($total_item_purchase)?$pack_price_prefix:'')}}{{ $total_item_purchase }}</td>
    <td class="celda">{{ $utility_item }}</td>
    <td class="celda">{{ $brand }}</td>
    <td class="celda">{{ $category }}</td>

    <td class="celda">{{ $document->exchange_rate_sale }}</td>
    <td class="celda">{{ $warehouse_description }}</td>

</tr>
