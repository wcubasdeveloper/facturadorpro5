<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/pdf; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>Consolidado de items</title>
    <style>
        html {
            font-family: sans-serif;
            font-size: 9px;
        }

        table {
            width: 100%;
            border-spacing: 0;
        }

        .celda {
            text-align: center;
            padding: 5px;
        }

        .celda-item {
            text-align: left;
            padding: 5px;
        }

        th {
            padding: 5px;
            text-align: center;

        }

        .title {
            font-weight: bold;
            padding: 5px;
            font-size: 10px !important;
            text-decoration: underline;
        }

        p > strong {
            margin-left: 5px;
            font-size: 9px;
        }

        thead {
            font-weight: bold;
            text-align: center;
        }

        @page {
            margin: 0px 1px;
        }
    </style>
</head>
<body>
<div>
    <p align="center"
       class="title"><strong>Consolidado de items por cliente/vendedor - Totales</strong></p>
</div>
<div>

    <strong>Empresa: </strong>{{$company->name}}<br>
    <strong>Ruc: </strong>{{$company->number}}<br>
    <strong>Establecimiento: </strong>{{$establishment->address}}
    - {{$establishment->department->description}} - {{$establishment->district->description}}<br>
    @inject('reportService', 'Modules\Report\Services\ReportService')
    @if(isset($params['sellers']))
        @php
            $sellers = json_decode($params['sellers'])
        @endphp
        @if(count($sellers) > 0)

            <strong>Usuario(s): </strong>
                @foreach ($sellers as $seller_id)
                    - {{$reportService->getUserName($seller_id)}}
                @endforeach
            <br>

        @endif
    @endif
    @if(isset($params['person_id']))
        <strong>Cliente: </strong>{{$reportService->getPersonName($params['person_id'])}}<br>

    @endif
</div>
@if(!empty($records))
    <div class="">
        <div class=" ">
            @php
                        $acum_total=0;
                        $acu_total_sale = 0;
                @endphp
            <table class="">
                <thead>
                <tr>
                    <th class="text-center">#</th>
<!--                    <th class="text-center">Cod. Interno</th>-->
                    <th class="celda-item">Producto</th>
                    <th class="text-center">Unidad</th>
                    <th class="text-center">Categoria</th>
                    <th class="text-center">Cantidad Total</th>
                    <th class="text-center">Total de venta</th>
                </tr>
                </thead>
                <tbody>
                @foreach($records as $key => $value)

                    <tr>
                        <td class="celda">{{ $loop->iteration }}</td>
<!--                        <td class="celda">{{$value['item_internal_id']}}</td>-->
                        <td class="celda-item">{{$value['item_description']}}</td>
                        <td class="celda">{{$value['item_unit_type_id']}}</td>
                        <td class="celda">{{$value['category']}}</td>
                        <td class="celda">{{$value['quantity']}}</td>
                        <td class="celda">{{$value['total_sale']}}</td>
                    </tr>
                    @php
                        $acum_total += $value['quantity']??0;
                        $acu_total_sale += $value['total_sale']??0;

                    @endphp
                @endforeach

                            <tr>
                                <td class="celda" colspan="3"></td>
                                <td class="celda" ><strong>Total</strong></td>
                                <td class="celda">{{$acum_total}}</td>
                                <td class="celda">{{$acu_total_sale}}</td>
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
