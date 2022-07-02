<?php
use App\Models\Tenant\Item;
$type = isset($type) ? $type : '';
?>
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>Document</title>
</head>
<body>
<div>
    <h3 align="center"
        class="title"><strong>REPORTE POR PRODUCTO</strong></h3>
</div>
<br>
<div style="margin-top:20px; margin-bottom:15px;">
    <table>
        @if(!empty($records))
            <tr>
                <td>
                    <p><b>Producto: </b></p>
                </td>
                <td align="center">
                    <?php
                    $internal_id = null;
                    $description = null;
                    if ($type === 'purchase') {
                        $item = Item::find($records[0]->item->id);
                        $internal_id = $item->internal_id;
                        $description = $item->description;
                    } else {
                        $internal_id = $records[0]->item->internal_id;
                        $description = $records[0]->item->description;
                    }
                    ?>
                    <p><strong>
                            {{($internal_id) ? $internal_id.' -':''}}
                            {{$description}}
                        </strong></p>
                </td>
            </tr>
        @endif
        <tr>
            <td>
                <p><b>Empresa: </b></p>
            </td>
            <td align="center">
                <p><strong>{{$company->name}}</strong></p>
            </td>
            <td>
                <p><strong>Fecha: </strong></p>
            </td>
            <td align="center">
                <p><strong>{{date('Y-m-d')}}</strong></p>
            </td>
        </tr>
        <tr>
            <td>
                <p><strong>Ruc: </strong></p>
            </td>
            <td align="center">{{$company->number}}</td>
            <td>
                <p><strong>Establecimiento: </strong></p>
            </td>
            <td align="center">{{$establishment->address}} - {{$establishment->department->description}}
                                                           - {{$establishment->district->description}}</td>
        </tr>
    </table>
</div>
<br>
@if(!empty($records))
    <div class="">
        <div class=" ">
            @php
                $acum_total_taxed=0;
                $acum_total_igv=0;
                $acum_total=0;

                $serie_affec = '';
                $acum_total_exonerado=0;
                $acum_total_inafecto=0;

                $acum_total_free=0;

                $acum_total_taxed_usd = 0;
                $acum_total_igv_usd = 0;
                $acum_total_usd = 0;
                $acum_quantity=0
            @endphp
            <table class="">
                <thead>
                <tr>
                    <th class="">#</th>
                    <th class="">Fecha</th>
                    <th class="">Tipo Documento</th>
                    <th class="">Serie</th>
                    <th class="">Número</th>
                    <th class="">N° Documento</th>
                    <th class="">Cliente</th>
                    @if($type !== 'purchase')
                        <th class="">Plataforma</th>
                    @endif
                    <th class="">Datos Complementarios</th>
                    <th class="">Cantidad</th>
                    <th class="">Monto</th>
                </tr>
                </thead>
                <tbody>
                @foreach($records as $key => $value)
                    <?php

                    if ($type === 'purchase') {
                        $document = $value->purchase;
                        $customer_name = $document->supplier->name;
                        $customer_number = $document->supplier->number;
                    } else {
                        $document = $value->document;
                        $customer_name = $document->customer->name;
                        $customer_number = $document->customer->number;
                    }
                    $printExtraData = '';
                    $extraData= $value->getPrintExtraData();
                    $indexExclude = [
                        'image_url',
                        'image_url_medium',
                        'image_url_small',
                    ];
                    if(count($extraData)> 0){
                        foreach($extraData as $index=>$data) {
                            if(!empty($data)) {
                                if(!in_array($index,$indexExclude)) {
                                    $printExtraData .= "$index : $data<br>";
                                }
                            }
                        }
                    }

                    ?>


                    <tr>
                        <td class="celda">{{$loop->iteration}}</td>
                        <td class="celda">{{$document->date_of_issue->format('Y-m-d')}}</td>
                        <td class="celda">{{$document->document_type->description}}</td>
                        <td class="celda">{{$document->series}}</td>
                        <td class="celda">{{$document->number}}</td>
                        <td class="celda">{{$customer_number}}</td>
                        <td class="celda">{{$customer_name}}</td>
                        @if($type !== 'purchase')
                            <td class="celda">{{ optional($value->relation_item->web_platform)->name }}</td>
                        @endif
                        <td class="celda">{!! $printExtraData !!}</td>
                        <td class="celda">{{$value->quantity}}</td>
                        <td class="celda">{{$value->total}}</td>

                        @php
                            $signal = $document->document_type_id;
                            $state = $document->state_type_id
                        @endphp



                        @php
                            $value->total = (in_array($document->document_type_id,['01','03']) && in_array($document->state_type_id,['09','11'])) ? 0 : $value->total
                        @endphp

                        @php

                            $serie_affec =  ''

                        @endphp

                    </tr>
                    @php

                        if(($signal == '07' && $state !== '11')){

                            $acum_total += -$value->total;

                        }elseif($signal != '07' && $state == '11'){

                            $acum_total += 0;

                        }else{

                            $acum_total += $value->total;

                        }

                        $acum_quantity += $value->quantity

                    @endphp
                @endforeach
                <tr>
                    <td colspan="8"></td>
                    <td>TOTALES</td>
                    <td>{{$acum_quantity}}</td>
                    <td>{{$acum_total}}</td>
                </tr>
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
