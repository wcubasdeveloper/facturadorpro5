<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    @if($format === 'pdf')
        <meta http-equiv="Content-Type"
              content="application/pdf; charset=utf-8"/>
    @else
        <meta http-equiv="Content-Type"
              content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    @endif
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>Inventario</title>
    <style>
        html {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        .title {
            font-weight: 500;
            text-align: center;
            font-size: 24px;
        }

        .label {
            width: 120px;
            font-weight: 500;
            font-family: sans-serif;
        }

        .table-records {
            margin-top: 24px;
        }

        .table-records tr th {
            font-weight: bold;
            background: #0088cc;
            color: white;
        }

        .table-records tr th,
        .table-records tr td {
            border: 1px solid #000;
            font-size: 9px;
        }
    </style>
</head>
<body>
<table style="width: 100%">
    <tr>
        <td colspan="13"
            class="title"><strong>Reporte Inventario</strong></td>
    </tr>
    <tr>
        <td colspan="2"
            class="label">Empresa:
        </td>
        <td>{{$company->name}}</td>
    </tr>
    <tr>
        <td colspan="2"
            class="label">RUC:
        </td>
        <td align="left">{{$company->number}}</td>
    </tr>
    <tr>
        <td colspan="2"
            class="label">Establecimiento:
        </td>
        <td>{{$establishment->address}} - {{$establishment->department->description}}
                                        - {{$establishment->district->description}}</td>
    </tr>
    <tr>
        <td colspan="2"
            class="label">Fecha:
        </td>
        <td>{{ date('d/m/Y')}}</td>
    </tr>
</table>
<table style="width: 100%"
       class="table-records">
    <thead>
    <tr>
        <th><strong>#</strong></th>
        <th><strong>Cod. de barras</strong></th>
        <th><strong>Cod. Interno</strong></th>
        <th><strong>Descripción</strong></th>
        <th><strong>Categoria</strong></th>

        <th align="right"><strong>Stock mínimo</strong></th>
        <th align="right"><strong>Stock actual</strong></th>
        <th align="right"><strong>Costo</strong></th>
        <th align="right"><strong>Costo Total</strong></th>
        <th align="right"><strong>Precio de venta</strong></th>

        <th align="right"><strong>Ganancia</strong></th>
        <th align="right"><strong>Ganancia Total</strong></th>
        <th><strong>Marca</strong></th>
        <th><strong>Modelo</strong></th>
        <th><strong>F. vencimiento</strong></th>

        <th><strong>Almacén</strong></th>
    </tr>
    </thead>
    <tbody>
    @php
        $total_purchase_unit_price = 0;
        $total_sale_unit_price = 0;
        $total = 0;
        $total_profit = 0;
        $total_all_profit = 0
    @endphp
    @foreach($records as $key => $row)
        @php
            $total_line = $row['stock'] * $row['purchase_unit_price'];
            $profit = $row['sale_unit_price'] - $row['purchase_unit_price'];
            $total += $total_line;
            $total_profit += $profit;
            $total_all_profit+= ($profit * $row['stock']);
            $profit = number_format($profit,2,'.','');

            $total_purchase_unit_price += $row['purchase_unit_price'];
            $total_sale_unit_price += $row['sale_unit_price'];
        @endphp
        <tr>
            <td>{{ $loop->iteration}}</td>
            <td>{{ $row['barcode'] }}</td>
            <td>{{ $row['internal_id'] }}</td>
            <td>{{ $row['name'] }}</td>
            <td>{{ $row['item_category_name'] }}</td>
            <td align="right">{{ $row['stock_min'] }}</td>
            <td align="right">{{ $row['stock'] }}</td>
            <td align="right">{{ $row['purchase_unit_price'] }}</td>
            <td align="right">{{ $total_line }}</td>
            <td align="right">{{ $row['sale_unit_price'] }}</td>
            <td align="right">{{ $profit }}</td>
            <td align="right">{{ number_format(abs($profit * $row['stock']),2,'.','')}}</td>
            <td>{{ $row['brand_name'] }}</td>
            <td>{{ $row['model'] }}</td>
            <td>{{ $row['date_of_due'] }}</td>
            <td>{{ $row['warehouse_name'] }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th align="right"></th>
        <th align="right"></th>
        <th align="right"><strong>Costo</strong></th>
        <th align="right"><strong>Costo Total de Inventario</strong></th>
        <th align="right"><strong>Precio de venta</strong></th>
        <th align="right"><strong>Ganancia</strong></th>
        <th align="right"><strong>Ganancia Total</strong></th>
        <th colspan="4"></th>
    </tr>
    <tr>
        <td colspan="7"
            class="celda"></td>
        <td class="celda">{{ number_format($total_purchase_unit_price, 2, '.','') }}</td>
        <td class="celda">{{ $total }}</td>
        <td class="celda">{{ number_format($total_sale_unit_price, 2, '.','') }}</td>
        <td class="celda">S/ {{number_format($total_profit,2,'.','')}}</td>
        <td class="celda">S/ {{number_format($total_all_profit,2,'.','')}}</td>
        <td colspan="4"
            class="celda"></td>
    </tr>
    </tfoot>
</table>
</body>
</html>
