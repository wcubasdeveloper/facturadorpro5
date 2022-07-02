<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kardex</title>
    <style>

    </style>
</head>
<body>
<div>
    <h3 align="center" class="title"><strong>Reporte Kardex</strong></h3>
</div>
<br>
<div style="margin-top:20px; margin-bottom:15px;">
    <table>
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
                <p><strong>{{date('d/m/Y')}}</strong></p>
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
        <?php
        /**
         * @var \App\Models\Tenant\Item $item
         * @var \App\Models\Tenant\ItemWarehousePrice $wprice
         */
        $producto_name = $item->internal_id ? $item->internal_id . ' - ' . $item->description : $item->description;
        $warehousePrices = $item->warehousePrices;

        ?>
        <tr>
            <td><p><strong>Producto: </strong></p></td>
            <td align="center"><p> {{$producto_name}}</p></td>
            <td>
            </td>
            <td align="center">
                @if(!empty($warehousePrices)&& count($warehousePrices)> 0)
                    <p><strong>Precios por almacenes:</strong></p>
                    @foreach($warehousePrices as $wprice)
                        <br><strong>{{$wprice->getWarehouseDescription() }}:</strong> {{ $wprice->getPrice() }}
                    @endforeach
                @endif
            </td>
        </tr>
    </table>
</div>
<br>
@if(!empty($records))
    <div class="">
        <div class=" ">
            <table class="">
                <thead>
                <tr>
                    <th>#</th>
                    @if(!$item_id)
                        <th>Producto</th>
                    @endif
                    <th>Fecha y hora transacción</th>
                    <th>Tipo transacción</th>
                    <th>Número</th>
                    <th>NV. Asociada</th>
                    <th>Pedido</th>
                    <th>CPE. Asociado</th>
                    <th>Feha emisión</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    @if($item_id)
                        <th>Saldo</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <?php $balance = 0; ?>
                @foreach($records as $key => $value)
                    <?php
                    /** @var \Modules\Inventory\Models\InventoryKardex $value */
                    $itemKardex = $value->getKardexReportCollection($balance);
                    ?>
                    <tr>
                        <td class="celda">{{$loop->iteration}}</td>
                        @if(!$item_id)
                            <td class="celda">{{$itemKardex['item_name']}}</td>
                        @endif
                        <td class="celda">{{$itemKardex['date_time']}}
                        </td>
                        <td class="celda">{{$itemKardex['type_transaction']}}</td>
                        <td class="celda">{{$itemKardex['number']}}</td>
                        <td class="celda">
                            {{$itemKardex['sale_note_asoc']}}
                        </td>
                        <td class="celda">
                            {{$itemKardex['order_note_asoc']}}
                        </td>
                        <td class="celda">
                            {{$itemKardex['doc_asoc']}}
                        </td>

                        <td class="celda">
                            {{$itemKardex['date_of_issue']}}
                        </td>
                        <td class="celda">
                            {{$itemKardex['input']}}
                        </td>
                        <td class="celda">
                            {{$itemKardex['output']}}
                        </td>
                        {{-- @php
                            $balance += $value->quantity;
                        @endphp --}}

                        @if($item_id)
                            <td class="celda">{{number_format($itemKardex['balance'], 4)}}</td>
                            {{-- <td class="celda">{{number_format($balance, 4, ".", "")}}</td> --}}
                        @endif
                    </tr>
                @endforeach
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
