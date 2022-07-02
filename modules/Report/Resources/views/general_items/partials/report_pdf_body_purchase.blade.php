<?php

    $apply_conversion_to_pen = $request_apply_conversion_to_pen == 'true';

    $unit_price = $value->unit_price;
    $total_isc = $value->total_isc;
    $total = $value->total;
    $description_apply_conversion_to_pen = null;

    // aplicar conversión si es que esta habilitada la configuracion
    if($apply_conversion_to_pen && $value->isCurrencyTypeUsd())
    {
        $total = $value->getConvertTotalToPen();
        $unit_price = $value->getConvertUnitPriceToPen();
        $total_isc = round($value->getConvertTotalIscToPen(), 2);
        $description_apply_conversion_to_pen = '(Conv.)';
    }
    // aplicar conversión si es que esta habilitada la configuracion


?>
<tr>
    <td class="celda">{{$document->date_of_issue->format('Y-m-d')}}</td>
    <td class="celda">{{ optional($document->user)->name}}</td>

    {{--
    @if($isSaleNote)
        <td class="celda">{{ $stablihsment['district'] }}</td>
        <td class="celda">{{ $stablihsment['department'] }}</td>
        <td class="celda">{{ $stablihsment['province'] }}</td>
    @endif
    --}}
    <td class="celda">{{$document->series}}</td>
    <td class="celda">{{$document->number}}</td>
    {{--
    @if( $type == 'sale')
        <td class="celda">{{ $purchseOrder }} </td>
    @endif
    --}}
    <td class="celda">{{$document->supplier->identity_document_type_id}}</td>
    <td class="celda">{{$document->supplier->number}}</td>
    <td class="celda">{{$document->supplier->name}}</td>
    <td class="celda">{{$document->currency_type_id}} {{ $description_apply_conversion_to_pen ?? ''}}</td>
    {{-- <td class="celda">{{$document->exchange_rate_sale}}</td> --}}
    <td class="celda">{{$value->item->unit_type_id}}</td>

    {{-- <td class="celda">{{$value->relation_item ? $value->relation_item->internal_id:''}}</td> --}}
    <td class="celda">{{$value->relation_item->brand->name}}</td>
    {{--
    @if($type == 'sale')
        <td class="celda">{{ $model }}</td>
    @endif
    --}}
    <td class="celda">{{$value->item->description}}</td>
    <td class="celda">{{$value->relation_item->category->name}}</td>
    <td class="celda">{{number_format($value->quantity, 2)}}</td>
    
    <td class="celda">{{round($unit_price, 6)}}</td>
    <td class="celda">{{optional($value->system_isc_type)->description}}</td>
    <td class="celda"> {{$total_isc > 0 ? $total_isc : ''}}</td>

    <td class="celda">{{round($total, 2)}}</td>
    {{-- <td class="celda"></td> --}}

</tr>
