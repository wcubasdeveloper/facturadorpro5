<?php
use App\Models\Tenant\Company;

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
       class="title"><strong> Reporte de Embalaje {{$date->firstOfMonth()->format('d-m-Y')}}</strong></p>
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
        <th>Número de Registro</th>
        <th>Número de ficha</th>
        <th>Fecha de inicio</th>
        <th>Fecha de fin</th>
        <th>Producto</th>
        <th>Colaborador</th>
        <th>Establecimiento</th>
        <th>Cantidad</th>
        <th># Paquetes</th>
        <th>Lote</th>
        <th>Comentario</th>
    </tr>
    </thead>


    @foreach($records as $index =>$row)
        @php
            $color = null;
                if(isset($row['item_extra_data'])){
                    $row['item_extra_data'] = (array)$row['item_extra_data'];
                    $colorId = (int)$row['item_extra_data']['color'];
                    $itemColor =  \App\Models\Tenant\ItemColor::find($colorId);
                    $color = " - ".(!empty($itemColor)? $itemColor->getColor()->name:null);
                }
                $item =  $row['item'];
                // dd($item);

                	$name = $item->description. $color;
        @endphp
        <tr>
            <td class="celda"> {!! $index+1 !!}</td>


            <td  class="celda">000{{ $row['id'] }}</td>
            <td  class="celda">{{ $row['name'] }}</td>
            <td  class="celda">{{ $row['date_start']??null }} - {{ $row['time_start']??null }}</td>
            <td  class="celda">{{ $row['date_end']??null }} - {{ $row['time_end']??null }}</td>
            <td  class="celda">{{ $name }}</td>
            <td  class="celda">{{ $row['packaging_collaborator'] }}</td>
            <td  class="celda">{{ $row['stablishment'] }}</td>
            <td  class="celda">{{ $row['quantity']??null }}</td>
            <td  class="celda">{{ $row['number_packages']??null }}</td>
            <td  class="celda">{{ $row['lot_code'] }}</td>
            <td  class="celda">{{ $row['observation'] }}</td>



        </tr>

    @endforeach
</table>
</body>
</html>



