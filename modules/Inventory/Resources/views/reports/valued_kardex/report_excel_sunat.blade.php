<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kardex valorizado</title>
</head>
<body>
<div>
    {{-- <h3 align="center" class="title"><strong>FORMATO 13.1: "REGISTRO DE INVENTARIO PERMANENTE VALORIZADO - DETALLE DEL INVENTARIO VALORIZADO"</strong></h3> --}}
</div>
<br>
<div style="margin-top:20px; margin-bottom:15px;">
    <table>
        <tr >
            <td colspan="8">
                <p><b>FORMATO 13.1: "REGISTRO DE INVENTARIO PERMANENTE VALORIZADO - DETALLE DEL INVENTARIO VALORIZADO" </b></p>
            </td>  
        </tr> 
        <tr>
            <td>
                <p><b>PERÍODO: </b> </p>
            </td>  
            <td>
                {{ $additionalData['period'] }}
            </td>
            @if($additionalData['month'])
            <td>
                <p><b>MES: </b> </p>
            </td> 
            <td>
                {{ $additionalData['month'] }}
            </td>
            @endif
        </tr> 
        <tr>
            <td>
                <p><b>RUC: </b></p>
            </td>  
            <td>
                {{ $company->number }}
            </td>
        </tr> 
        <tr>
            <td>
                <p><b>APELLIDOS Y NOMBRES, DENOMINACIÓN O RAZÓN SOCIAL:</b> </p>
            </td>  
            <td>
                {{ $company->name }}
            </td>
        </tr> 
        <tr>
            <td>
                <p><b>ESTABLECIMIENTO (1):</b>  </p>
            </td>  
            <td>
                {{ optional($establishment)->description }}
            </td>
        </tr> 
        <tr>
            <td>
                <p><b>CÓDIGO DE LA EXISTENCIA:</b></p>
            </td>  
            <td>
                {{ $additionalData['internal_id'] }}
            </td>
        </tr> 
        <tr>
            <td>
                <p><b>TIPO (TABLA 5):</b> </p>
            </td>  
            <td>
                01
            </td>
        </tr> 
        <tr>
            <td>
                <p><b>DESCRIPCIÓN:</b></p>
            </td>  
            <td>
                {{ $additionalData['description'] }}
            </td>
        </tr> 
        <tr>
            <td>
                <p><b>CÓDIGO DE LA UNIDAD DE MEDIDA (TABLA 6):</b> </p>
            </td> 
            <td>
                {{ $additionalData['unit_type_table_six']['code'] }} - {{ $additionalData['unit_type_table_six']['description'] }}
            </td> 
        </tr> 
        <tr>
            <td>
                <p><b>MÉTODO DE VALUACIÓN:</b> </p>
            </td>  
            <td>
                COSTO PROMEDIO
            </td>
        </tr> 
    </table>
    
    <table>
        <tr>
            <td colspan="4" align="center">
                <p><b>DOCUMENTO DE TRASLADO, COMPROBANTE DE PAGO, DOCUMENTO DE TRASLADO, COMPROBANTE DE PAGO, DOCUMENTO INTERNO O SIMILAR</b></p>
            </td> 
            <td rowspan="2" align="center">
                <p><b>TIPO DE OPERACIÓN (TABLA 12)</b></p>
            </td> 
            <td colspan="3" align="center">
                <p><b>ENTRADAS</b></p>
            </td> 
            <td colspan="3" align="center">
                <p><b>SALIDAS</b></p>
            </td> 
            <td colspan="3" align="center">
                <p><b>SALDO FINAL</b></p>
            </td> 
        </tr>  
        <tr>
            <td>
                <p><b>FECHA</b></p>
            </td>  
            <td>
                <p><b>TIPO (TABLA 10)</b></p>
            </td>  
            <td>
                <p><b>SERIE</b></p>
            </td>  
            <td>
                <p><b>NÚMERO</b></p>
            </td>  

            {{-- ENTRADAS --}}
            <td>
                <p><b>CANTIDAD</b></p>
            </td>  
            <td>
                <p><b>COSTO UNITARIO</b></p>
            </td>  
            <td>
                <p><b>COSTO TOTAL</b></p>
            </td>  

            
            {{-- SALIDAS --}}
            <td>
                <p><b>CANTIDAD</b></p>
            </td>  
            <td>
                <p><b>COSTO UNITARIO</b></p>
            </td>  
            <td>
                <p><b>COSTO TOTAL</b></p>
            </td>  

            
            {{-- SALDO --}}
            <td>
                <p><b>CANTIDAD</b></p>
            </td>  
            <td>
                <p><b>COSTO UNITARIO</b></p>
            </td>  
            <td>
                <p><b>COSTO TOTAL</b></p>
            </td>  


        </tr>  

        @php
            // dd($records);
            // $balance_quantity = 0;
            // $balance_total = 0;
            // $balance_cost = 0;

            //totals
            $totals = [
                'input_quantity' => 0,
                'input_unit_price' => 0,
                'input_total' => 0,
                'output_quantity' => 0,
                'output_unit_price' => 0,
                'output_total' => 0,
                'balance_quantity' => 0,
                'balance_total' => 0,
                'balance_cost' => 0,
            ];

        @endphp

        @foreach ($records as $row)
            <tr>
                <td>
                    {{ $row['date_of_issue'] }}
                </td>
                <td>
                    {{ $row['document_type_id'] }}     
                </td>
                <td>
                   {{ $row['series'] }}     
                </td>
                <td>
                   {{ $row['number'] }}     
                </td>
                <td>
                   {{ $row['operation_type'] }}     
                </td>

                {{-- ENTRADAS --}}
                <td>
                   {{ $row['input_quantity'] }}     
                </td>
                <td>
                   {{ $row['input_unit_price'] }}     
                </td>
                <td>
                   {{ $row['input_total'] }}     
                </td>

                {{-- SALIDAS --}}
                @if($row['type'] == 'output')
                <td>
                   {{ $row['output_quantity'] }}     
                </td>
                <td>
                   {{ $row['output_unit_price'] }}     
                </td>
                <td>
                   {{ $row['output_total'] }}     
                </td>
                @else
                <td></td>
                <td></td>
                <td></td>
                @endif

                {{-- SALDO --}}
                @php

                    // $balance_quantity +=  $row['quantity'] * $row['factor'];
                    // $balance_total += $row['total'] * $row['factor'];
                    // $balance_cost = ($balance_quantity != 0) ? round($balance_total / $balance_quantity, 4) : null; 

                    if($row['type'] == 'input'){

                        $totals['input_quantity'] += $row['input_quantity'];
                        $totals['input_unit_price'] += $row['input_unit_price'];
                        $totals['input_total'] += $row['input_total'];

                    }else{

                        $totals['output_quantity'] += $row['output_quantity'];
                        $totals['output_unit_price'] += $row['output_unit_price'];
                        $totals['output_total'] += $row['output_total'];

                    }


                    $totals['balance_quantity'] += $row['balance_quantity'];
                    $totals['balance_total'] += $row['balance_total_cost'];
                    $totals['balance_cost'] += $row['balance_unit_cost'];

                @endphp

                <td>
                    {{-- {{ $balance_quantity }} --}}
                   {{ $row['balance_quantity'] }}     
                </td>
                <td>
                    {{-- {{ $balance_cost }} --}}
                   {{ $row['balance_unit_cost'] }}     
                </td>
                <td>
                    {{-- {{ $balance_total }} --}}
                   {{ $row['balance_total_cost'] }}     
                </td>
            </tr>
        @endforeach
        <tr>
            
        </tr>
        <tr>
            <td colspan="5" align="right">
                TOTALES
            </td>
            <td>
                {{ $totals['input_quantity'] }}
            </td>
            <td>
                {{ $totals['input_unit_price'] }}
            </td>
            <td>
                {{ $totals['input_total'] }}
            </td>
            <td>
                {{ $totals['output_quantity'] }}
            </td>
            <td>
                {{ $totals['output_unit_price'] }}
            </td>
            <td>
                {{ $totals['output_total'] }}
            </td>
            <td>
                {{ $totals['balance_quantity'] }}
            </td>
            <td>
                {{ $totals['balance_cost'] }}
            </td>
            <td>
                {{ $totals['balance_total'] }}
            </td>
        </tr>

    </table>
</div>
<br> 
</body>
</html>
