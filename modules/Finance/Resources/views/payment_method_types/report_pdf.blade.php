<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Ingresos y Egresos - Métodos de pago</title>
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

            p>strong {
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
            <p align="center" class="title"><strong>Ingresos y Egresos - Métodos de pago</strong></p>
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
                        <p><strong>Establecimiento: </strong>{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</p>
                    </td>
                </tr>
            </table>
        </div>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Descripción</th>
                                <th class="text-center">CPE</th>
                                <th class="text-center">N. Venta</th>
                                <th class="text-center">Cotización</th>
                                <th class="text-center">Contrato</th>
                                <th class="text-center">S. Técnico</th>
                                <th class="text-center">Ingresos</th>
                                <th class="text-center">Compras</th>
                                <th class="text-center">Gastos</th>
                                <th class="text-center">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($records['records'] as $key => $value)
                            <?php

                            $iteracion = $loop->iteration;
                            $description = $value['description'];
                            $document_payment = $value['document_payment'];
                            $sale_note_payment = $value['sale_note_payment'];
                            $quotation_payment = $value['quotation_payment'];
                            $contract_payment = $value['contract_payment'];
                            $purchase_payment = $value['purchase_payment'];
                            $expense_payment = $value['expense_payment'];
                            $technical_service_payment = $value['technical_service_payment'];
                            $income_payment = $value['income_payment'];
                            $balance = $value['balance'];
                            ?>
                            <tr>
                                <td class="celda">
                                    {{$iteracion}}
                                </td>
                                <td class="celda">
                                    {{$description}}
                                </td>
                                <td class="celda">
                                    S/ {{$document_payment}}
                                </td>
                                <td class="celda">
                                    S/ {{$sale_note_payment}}
                                </td>
                                <td class="celda">
                                    S/ {{$quotation_payment}}
                                </td>
                                <td class="celda">
                                    S/ {{$contract_payment}}
                                </td>
                                <td class="celda">
                                    S/ {{$technical_service_payment}}
                                </td>
                                <td class="celda">
                                    S/ {{$income_payment}}
                                </td>
                                <td class="celda">
                                    S/ {{$purchase_payment}}
                                </td>
                                <td class="celda">
                                    S/ {{$expense_payment}}
                                </td>
                                <td class="celda">S/ {{$balance}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td class="text-center celda" colspan="2">Totales</td>
                            <td class="text-center celda">S/ {{ $records['totals']['t_documents'] }}</td>
                            <td class="text-center celda">S/ {{ $records['totals']['t_sale_notes']}}</td>
                            <td class="text-center celda">S/ {{ $records['totals']['t_quotations']}}</td>
                            <td class="text-center celda">S/ {{ $records['totals']['t_contracts']}}</td>
                            <td class="text-center celda">S/ {{ $records['totals']['t_technical_services']}}</td>
                            <td class="text-center celda">S/ {{ $records['totals']['t_income']}}</td>
                            <td class="text-center celda">S/ {{ $records['totals']['t_purchases']}}</td>
                            <td class="text-center celda">S/ {{ $records['totals']['t_expenses']}}</td>
                            <td class="text-center celda">S/ {{ $records['totals']['t_balance']}}</td>
                        </tr>
                        </tfoot>
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
