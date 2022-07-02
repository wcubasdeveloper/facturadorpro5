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
</head>
<body>
<table class="full-width">
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
<table class="full-width mt-10 mb-10">
    <thead>
    <tr class="bg-grey">
        <th  class="border-top-bottom text-center py-2 text-left" width="3%"><strong>#</strong></th>
        <!-- <th class="border-top-bottom text-center py-2" width="8%"><strong>Cod. de barras</strong></th>-->
        <th class="border-top-bottom text-center py-2 text-left" width="8%"><strong>Cod. Interno</strong></th>
        <th class="border-top-bottom text-center py-2 text-left" width="8%"><strong>Descripción</strong></th>
        <th class="border-top-bottom text-center py-2" width="8%"><strong>Categoria</strong></th>
        <th class="border-top-bottom text-center py-2" width="6%"><strong>Stock mínimo</strong></th>

        <th class="border-top-bottom text-center py-2" width="6%"><strong>Stock actual</strong></th>
        <th class="border-top-bottom text-center py-2" width="6%"><strong>Costo</strong></th>
        <th class="border-top-bottom text-center py-2" width="6%"><strong>Costo Total</strong></th>
        <th class="border-top-bottom text-center py-2" width="7%"><strong>Precio de venta</strong></th>
        <th class="border-top-bottom text-center py-2" width="7%"><strong>Ganancia</strong></th>

        <th class="border-top-bottom text-center py-2" width="7%"><strong>Ganancia Total</strong></th>
        <th class="border-top-bottom text-center py-2 text-right" width="8%" ><strong>Marca</strong></th>
        <th class="border-top-bottom text-center py-2" width="8%"><strong>Modelo</strong></th>
        <th class="border-top-bottom text-center py-2" width="8%"><strong>F. vencimiento</strong></th>
        <th class="border-top-bottom text-center py-2 text-right" width="8%"><strong>Almacén</strong></th>
        
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
            <!--<td>{{ $row['barcode'] }}</td> -->
            <td>{{ $row['internal_id'] }}</td>
            <td>{{ $row['name'] }}</td>
            <td>{{ $row['item_category_name'] }}</td>
            <td>{{ $row['stock_min'] }}</td>
            <td >{{ $row['stock'] }}</td>
            <td >{{ number_format($row['purchase_unit_price'],2,'.','')}} </td>
            <td >{{ number_format($total_line,2,'.','')}}</td>
            <td >{{ number_format($row['sale_unit_price'],2,'.','')}} </td>
            <td >{{ $profit }}</td>
            <td >{{ number_format(abs($profit * $row['stock']),2,'.','')}}</td>
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
        <th></th>
        <th ></th>
        <th><strong>Costo</strong></th>
        <th><strong>Costo Total de Inventario</strong></th>
        <th ><strong>Precio de venta</strong></th>
        <th ><strong>Ganancia</strong></th>
        <th><strong>Ganancia Total</strong></th>
        <th colspan="4"></th>
    </tr>
    <tr>
        <td colspan="7"
            class="celda"></td>
        <td class="celda">{{ number_format($total_purchase_unit_price, 2, '.','') }}</td>
        <td class="celda">{{ $total}}</td>
        <td class="celda">{{ number_format($total_sale_unit_price, 2, '.','') }}</td>
        <td class="celda">S/ {{ number_format($total_profit,2,'.','') }} </td>
        <td class="celda">S/ {{number_format($total_all_profit,2,'.','')}}</td>
        <td colspan="4"
            class="celda"></td>
    </tr>
    </tfoot>
</table>
</body>
</html>
