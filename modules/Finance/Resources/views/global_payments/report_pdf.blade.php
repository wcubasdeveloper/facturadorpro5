<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Pagos</title>
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
            <p align="center" class="title"><strong>Reporte pagos</strong></p>
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
                                <th class="">#</th>
                                <th class="">Adquiriente</th>
                                <th class="">N° Doc. Identidad</th>
                                <th class="">Tipo documento</th>
                                <th class="">Documento/Transacción</th>
                                <th class="">Moneda</th>
                                <th class="">Tipo</th>
                                <th class="">Destino</th>
                                <th class="">Cuenta/Caja</th>
                                <th class="">F. Pago</th>
                                <th class="">Método</th>
                                <th class="">Referencia</th>
                                <th class="">Responsable</th>
                                <th class="">Pago</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($records as $key => $value)

                                <tr>
                                    @php

                        // Se obtienen los datos de modules/Finance/Http/Resources/GlobalPaymentCollection.php
                                        $data_person = $value->data_person;
                                        $document_type = '';
                        $payment = ($value->payment) ? $value->payment : null;
                        if ($payment !== null && $value->payment->associated_record_payment !== null && $value->payment->associated_record_payment->document_type) {

                                            $document_type = $value->payment->associated_record_payment->document_type->description;

                                        }elseif($value->instance_type == 'technical_service'){

                                            $document_type = 'ST';

                        } elseif ($payment !== null && isset($value->payment->associated_record_payment->prefix)) {

                                            $document_type = $value->payment->associated_record_payment->prefix;

                                        }

                                $cci = $value->getCciAcoount();

            $personName= $data_person->name;
            if(!is_string($personName)){

                if(property_exists($personName,'description')){
                                // Los bancos con transacciones
                    $personName = $personName->description;
                }
            }

                        $payment_method_type_description = '';

                        if ($value->payment !== null && $value->payment->payment_method_type) {

                            $payment_method_type_description = $value->payment->payment_method_type->description;

                        } else {
                            $payment_method_type_description = ($value->payment !== null && $value->payment->expense_method_type !== null) ? $value->payment->expense_method_type->description : '';
                        }

                        $data = [
                            'id' => $value->id,
                            'destination_description' => $value->destination_description,
                            'cci' => $cci,
                            'date_of_payment' => ($payment !== null && $value->payment->date_of_payment !== null) ? $value->payment->date_of_payment->format('Y-m-d') : '',
                            'payment_method_type_description' => $payment_method_type_description,
                            'reference' => ($payment !== null) ? $value->payment->reference : '',
                            'total' => ($payment !== null) ? $value->payment->payment : 0,
                            'number_full' => ($payment !== null && $value->payment->associated_record_payment) ? $value->payment->associated_record_payment->number_full : '',
                            'currency_type_id' => ($payment !== null && $value->payment->associated_record_payment != null) ? $value->payment->associated_record_payment->currency_type_id : '',
                            // 'document_type_description' => ($value->payment->associated_record_payment->document_type) ? $value->payment->associated_record_payment->document_type->description:'NV',
                            'document_type_description' => $document_type,
                            'person_name' => $personName,
                            'person_number' => $data_person->number,
                            // 'payment' => $value->payment,
                            // 'payment_type' => $value->payment_type,
                            'instance_type' => $value->instance_type,
                            'instance_type_description' => $value->instance_type_description,
                            'user_id' => $value->user_id,
                            'user_name' => optional($value->user)->name,
                        ];

                                    @endphp
                                    <td class="celda">{{$loop->iteration}}</td>
                        <td class="celda">{{$data['person_name']}}</td>
                        <td class="celda">{{$data['person_number']}}</td>
                                    <td class="celda">{{ $document_type }}</td>
                        <td class="celda">{{ $data['number_full'] }}</td>
                        <td class="celda">{{ $data['currency_type_id'] }}</td>
                        <td class="celda">{{ $data['instance_type_description'] }}</td>
                        <td class="celda">{{ $data['destination_description'] }}</td>
                        <td class="celda">{{ $data['cci'] }}</td>
                        <td class="celda">{{ $data['date_of_payment'] }}</td>
                                    <td class="celda">{{$payment_method_type_description}}</td>
                        <td class="celda">{{ $data['reference'] }}</td>
                        <td class="celda">{{ $data['user_name'] }}</td>
                        <td class="celda">{{ $data['total'] }}</td>

                                </tr>


                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="celda" colspan="12"></td>
                                <td class="celda"><strong>Totales PEN</strong></td>
                                <td class="celda">{{ $records->where('payment.associated_record_payment.currency_type_id', 'PEN')->sum('payment.payment') }}</td>
                            </tr>
                            <tr>
                    <td colspan="12"></td>
                                <td class="celda"><strong>Totales USD</strong></td>
                                <td class="celda">{{ $records->where('payment.associated_record_payment.currency_type_id', 'USD')->sum('payment.payment') }}</td>

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
