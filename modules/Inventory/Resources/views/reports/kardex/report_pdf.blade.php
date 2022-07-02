<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kardex</title>
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
    <p align="center" class="title"><strong>Reporte Kardex</strong></p>
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
        <?php
        /**
         * @var \App\Models\Tenant\Item $item
         * @var \App\Models\Tenant\ItemWarehousePrice $wprice
         */
        $producto_name = $item->internal_id ? $item->internal_id . ' - ' . $item->description : $item->description;
        $warehousePrices = $item->warehousePrices;

        ?>
        <tr>
            <td>
                <p>
                    <strong>Producto: </strong>{{$producto_name}}
                </p>
            </td>
            <td>
                @if(!empty($warehousePrices)&& count($warehousePrices)> 0)
                    <strong>Precios por almacenes:</strong>
                    @foreach($warehousePrices as $wprice)
                        <br><strong>{{$wprice->getWarehouseDescription() }}:</strong> {{ $wprice->getPrice() }}
                    @endforeach
                @endif
            </td>
        </tr>
    </table>
</div>
@if(!empty($reports))
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
                @foreach($reports as $key => $value)
                    <?php
                    /** @var \Modules\Inventory\Models\InventoryKardex $value */
                    $itemKardex = $value->getKardexReportCollection($balance);
                    ?>
                    <tr>
                        <td class="celda">{{$loop->iteration}}
                        </td>
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
                            {{-- <td class="celda">{{number_format($balance, 4)}}</td> --}}
                        @endif
                    </tr>
                @endforeach
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
