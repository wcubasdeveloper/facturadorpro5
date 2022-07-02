<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Nota de ventas</title>
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
            <p align="center" class="title"><strong>Reporte Nota de Venta</strong></p>
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
                                <th>Nombres y Apellidos</th>
                                <th> Habitación</th>
                                <th>Status de pago</th>
                                <th>Status checkout</th>
                                <th>Fecha de entrada</th>
                                <th>Hora de entrada</th>
                                <th>Fecha de salida</th>
                                <th>Hora de salida</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($records as $key => $value)
                            <?php
                            /** @var \Modules\Hotel\Models\HotelRent $value */


                            $customer = $value->customer;
                            $room = $value->room;
                            ?>
                            <tr>

                                <td>{{ $key+1 }}</td>
                                <td>{{$customer->description}}</td>
                                <td>{{$room->name}}</td>
                                <td>{{ $value ->payment_status === "PAID" ? "Pagado" : "Debe" }}</td>
                                <td>{{$value ->status}}</td>
                                <td>{{$value ->input_date}}</td>
                                <td>{{$value ->input_time}}</td>
                                <td>{{$value ->output_date}}</td>
                                <td>{{$value ->output_time}}</td>


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
