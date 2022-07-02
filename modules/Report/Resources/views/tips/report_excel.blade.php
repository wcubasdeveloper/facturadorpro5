<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Propinas</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte de propinas</strong></h3>
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
            <div class="">
                <div class=" "> 
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Empleado</th>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Documento</th>
                                <th class="text-center">Estado</th>
                                <th>Tipo documento</th>
                                <th class="text-center">Total propina</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                                @php
                                    $row = $record->getRowResource();
                                @endphp
                                
                                <tr>
                                    <td class="celda" >{{$loop->iteration}}</td>
                                    <td class="celda">{{ $row['worker_full_name'] }}</td>
                                    <td class="celda">{{ $row['origin_date_of_issue'] }}</td>
                                    <td class="celda">{{ $row['document_number_full'] }}</td> 
                                    <td class="celda">{{ $row['state_type_description'] }}</td> 
                                    <td class="celda">{{ $row['document_type_description'] }}</td> 
                                    <td class="celda">{{ $row['total'] }}</td> 
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
