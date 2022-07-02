<?php
use App\Models\Tenant\Company;

$inProcess = $inProcess??false;
$code_plant = '';
$debug = false;
$min_space = 5;
$date = Carbon\Carbon::now();
$company = Company::first();
$half = 50;
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>Reporte</title>
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
        }

        th {
            padding: 5px;
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
            font-size: 12px;
        }

        thead {
            font-weight: bold;
            background: #0088cc;
            color: white;
            text-align: center;
        }

        .td-custom {
            line-height: 0.1em;
        }

        .width-custom {
            width: 50%
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
<div>
    <p align="center"
       class="title"><strong> Reporte de Producción {{$inProcess?'En proceso':''}} {{$date->firstOfMonth()->format('d-m-Y')}}</strong></p>
</div>

<div style="margin-top:20px; margin-bottom:20px;">
<table>
    <tr>
            <td class="width-custom">
                <p><strong>Empresa: </strong>{{$company->name}}</p>
            </td>
            <td class="td-custom">
                <p><strong>Fecha reporte: </strong>{{date('Y-m-d')}}</p>
            </td>
            <td class="td-custom">
                <p><strong>Ruc: </strong>{{$company->number}}</p>
            </td>
    </tr>


    </table>
</div>
<table>
    <thead>
    <tr>

        <th>#</th>
        <th>Número de Ficha</th>
        @if($inProcess)
        <th>Tipo de proceso</th>
        @endif
        <th>Cód. Interno</th>
        <th>Prod. F. de inicio </th>
        <th>Prod. F. de Fin </th>
        <th>Colaborador de producción</th>
        <th>Cantidad</th>
        <th>Conf.</th>
        <th>Def.</th>
        <th>Maquinaria</th>
        <th>Lote</th>
        <th>Color</th>
        <th>Producto</th>
        <th>Orden de Producción</th>
        <th>Mez. F. de inicio </th>
        <th>Mez. F. de Fin </th>
        <th>Colaborador de mezcla</th>
        <th>Materia Prima </th>
        <th>Orden de producción</th>
    </tr>
    </thead>


    @foreach($records as $index =>$row)

        <tr>
            <td class="celda"> {!! $index+1 !!}</td>

            <td class="celda">{{ $row['name']??null }}</td>
                   @if($inProcess)
         <td class="celda">{{ $row['proccess_type']??null }}</td>
                    @endif
        <td class="celda">000{{ $row['id']??null }}</td>
            <td class="celda">{{ $row['date_start']??null }} - {{$row['time_start']??null}}</td>
            <td class="celda">{{ $row['date_end']??null }} - {{$row['time_end']??null}}</td>
            <td class="celda">{{ $row['production_collaborator']??null }}</td>
            <td class="celda">{{ $row['quantity']??null }}</td>
            <td class="celda">{{ $row['agreed']??null }}</td>
            <td class="celda">{{ $row['imperfect']??null }}</td>

            <td class="celda">
                @if(isset($row['machine']) )
                    {{ $row['machine']->name??null }}
                @endif
            </td>
            <td class="celda">{{ $row['lot_code']??null }}</td>
            <td class="celda">
                @php
                $color = null;
                    if(isset($row['item_extra_data'])){
                        $row['item_extra_data'] = (array)$row['item_extra_data'];
                        $colorId = (int)$row['item_extra_data']['color'];
                        $itemColor =  \App\Models\Tenant\ItemColor::find($colorId);
                        if(!empty($itemColor)){
                        	$color = $itemColor->getColor()->name;
                        }
                    }
                @endphp
                {{$color}}
            </td>
            <td class="celda">{{ $row['item_name']??null }}</td>
            <td class="celda">{{ $row['production_order']??null }}</td>
            <td class="celda">{{ $row['mix_date_start']??null }} - {{$row['mix_time_start']??null}}</td>
            <td class="celda">{{ $row['mix_date_end']??null }} - {{$row['mix_time_end']??null}}</td>
            <td class="celda">{{ $row['mix_collaborator']??null }}</td>
<!--            <td class="celda">{{ $row['comment']??null }}</td>-->

            <td class="celda">
                @if($row['item_supply']->count()> 0)
                    @foreach($row['item_supply'] as $item_supply)
                        <?php
                        /** @var \App\Models\Tenant\ItemSupply  $item_supply */
                        $item = $item_supply->individual_item;

                        ?>
                        {{$item->internal_id}} @if(!empty($item->internal_id))-@endif {{$item->description}}  ({{$item_supply->quantity}})<br>
                    @endforeach

                @endif
            </td>
                <td class="celda">{{ $row['production_order']??null }}</td>
        </tr>

    @endforeach
</table>
</body>
</html>



