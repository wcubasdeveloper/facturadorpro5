<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Comisión vendores</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte de comisión de vendedores - utilidades detallado</strong></h3>
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
                        <p><strong>{{date('Y-m-d')}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Ruc: </strong></p>
                    </td>
                    <td align="center">{{$company->number}}</td>
                  
                </tr>
            </table>
        </div>
        <br>
        @if(!empty($records))
            @php
                $acum_unit_gain = 0;
                $acum_overall_profit = 0;

            @endphp
            <div class="">
                <div class=" "> 
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th class="text-center">Comprobante</th>
                                <th class="text-center">Serie</th>
                                <th class="text-center">Ruc/Dni</th>

                                <th class="text-center">Comercial</th>
                                <th class="text-center">Detalle</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Precio compra</th>
                                <th class="text-center">Precio venta</th>

                                <th class="text-center">Ganancia unidad</th>
                                <th class="text-center">Ganancia total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $row)
                                @php

                                    $type_document = '';
                                    $relation = $row->document_id ? $row->document : $row->sale_note;

                                    if($row->document_id) {
                                        $type_document =  $row->document->document_type_id == '01' ? 'FACTURA' : 'BOLETA';
                                    }
                                    else if ($row->sale_note_id) {
                                        $type_document = 'NOTA DE VENTA';
                                    }

                                    $purchase_unit_price = 0;
                                    if(isset($row->item->purchase_unit_price)){
                                        $purchase_unit_price = $row->item->purchase_unit_price;
                                    }
                                    $unit_gain = ((float)$row->unit_price - (float)$purchase_unit_price);
                                    $overall_profit = (((float)$row->unit_price * $row->quantity ) - ((float)$purchase_unit_price * $row->quantity));

                                    $acum_unit_gain += (float) $unit_gain;
                                    $acum_overall_profit += (float) $overall_profit;

                                @endphp
                                
                                <tr>
                                <td class="celda" >{{$loop->iteration}}</td>
                                    <td class="celda">{{$relation->date_of_issue->format('Y-m-d')}}</td>
                                    <td class="celda">{{$type_document}}</td>
                                    <td class="celda">{{$relation->number_full}}</td> 
                                    <td class="celda">{{ $relation->customer->number}}</td> 

                                    <td class="celda">{{$relation->customer->name}}</td> 
                                    <td class="celda">{{$row->relation_item->description}}</td> 

                                    <td class="celda">{{$row->quantity}}</td> 
                                    <td class="celda">{{$purchase_unit_price}}</td> 
                                    <td class="celda">{{$row->unit_price}}</td> 

                                    <td class="celda">{{ $unit_gain }}</td> 
                                    <td class="celda">{{ $overall_profit }}</td> 
                                </tr>
                            @endforeach
                            <tr>
                                <td class="celda"  style="text-align:right;" colspan="10">TOTAL:</td>
                                <td class="celda">{{ number_format($acum_unit_gain, 2, ".", "") }}</td>
                                <td class="celda">{{ number_format($acum_overall_profit, 2, ".", "") }}</td>
                            </tr>
                        </tbody>
                        {{-- <tfoot>
                            <tr>
                                <td style="text-align:right;" colspan="10">TOTAL:</td>
                                <td class="text-center">{{ $acum_unit_gain }}</td>
                                <td class="text-center">{{ $acum_overall_profit }}</td>
                            </tr>
                        </tfoot> --}}
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
