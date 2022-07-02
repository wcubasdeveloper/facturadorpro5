<?php

    use App\Models\Tenant\Company;

    $company = Company::first();
    $establishment = \Auth::user()->establishment;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
            font-size: 13px;
        }

        thead {
            font-weight: bold;
            background: #0088cc;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
<div>
    <h3 align="center" class="title"><strong>Reporte Tramite</strong></h3>
</div>
<div style="margin-top:20px; margin-bottom:20px;">
    <table>
        <tr>
            <td>
                <p><strong>Empresa: </strong>{{$company->name}}</p>
            </td>
            <td>
                <p><strong>Fecha: </strong>{{date('Y-m-d')}}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><strong>Ruc: </strong>{{$company->number}}</p>
            </td>
            <td>
                <p><strong>Establecimiento: </strong>{{$establishment->address}}
                    - {{$establishment->department->description}} - {{$establishment->district->description}}</p>
            </td>
        </tr>
    </table>
</div>
@if(!empty($records) && isset($records['statistic']) && !empty($records['statistic']))
    <div class="">
        <div class=" ">

            <table class="table">
                <tbody>
                <tr>
                    @foreach($records['statistic'] as $key => $item)

                        <td >
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ $item['month'] }}</th>
                            </tr>
                            </thead>
                            <tr>

                                <td>Total: {{ $item['total'] }}</td>
                            </tr>
                            <tr>
                                <td>Completados: {{ $item['complete'] }}</td>
                            </tr>
                            <tr>
                                <td>En Proceso: {{ $item['process'] }}</td>
                            </tr>
                            <tr>

                                <td>Porcentaje tramitado: {{
	                                 \App\CoreFacturalo\Helpers\Template\ReportHelper::setNumber(($item['complete'] * 100) / $item['total'])

 }}%

                                </td>
                            </tr>
                        </table>
                    </td>
                    @endforeach
                </tr>
                </tbody>
            </table>

        </div>
    </div>
@else
    <div class="callout callout-info">
        <p>No se encontraron registros.</p>
    </div>
@endif
</body>
</html>
