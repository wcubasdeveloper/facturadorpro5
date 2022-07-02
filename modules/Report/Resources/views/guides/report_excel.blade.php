<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consolidado de items</title>
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
    <p align="center" class="title"><strong>Consolidado de items por cliente/vendedor</strong></p>
</div>
<div style="margin-top:20px; margin-bottom:20px;">
    <table>
        <tr>
            <td>
                <p><strong>Empresa: </strong>{{$company->name}}</p>
            </td>
            <td>
                <p><strong>Ruc: </strong>{{$company->number}}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><strong>Establecimiento: </strong>{{$establishment->address}}
                    - {{$establishment->department->description}} - {{$establishment->district->description}}</p>
            </td>
            @inject('reportService', 'Modules\Report\Services\ReportService')
            @if(isset($params['seller_id']))
            <td>
                <p><strong>Usuario: </strong>{{$reportService->getUserName($params['seller_id'])}}</p>
            </td>
            @endif
            @if(isset($params['sellers']))
                @php
                    $sellers = json_decode($params['sellers'])
                @endphp
                @if(count($sellers) > 0)
                    <td>
                        <p><strong>Usuario(s): </strong>
                            @foreach ($sellers as $seller_id)
                                - {{$reportService->getUserName($seller_id)}}
                            @endforeach
                        </p>
                    </td>
                @endif
            @endif


            @if(isset($params['person_id']))
                <td>
                    <p><strong>Cliente: </strong>{{$reportService->getPersonName($params['person_id'])}}</p>
                </td>
            @endif
        </tr>
    </table>
</div>
@if(!empty($records))
    <div class="">
        <div class=" ">
            @php
                $acum_total=0;
                
            @endphp
            <table class="">
                <thead>
                <tr>
                    <th>#</th>
                    <th class="text-center">Fecha Emisión</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Número</th>
                    <th>Estado</th>
                    <th class="text-center">Fecha Envío</th>
                    <th class="text-center">Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Motivo de Traslado</th>
                    <th class="text-center">Descripcion de Motivo de Traslado</th>

                    <th class="text-center">Transportista Tipo Doc</th>
                    <th class="text-center"># Documento</th>
                    <th class="text-center">Nombre de Transportista</th>
                    
                    <th class="text-center"># Pedido</th>
                    <th class="text-center">O.Pedido</th>
                </tr>
                </thead>
                <tbody>
                @foreach($records as $key => $value)
                    <tr>
                        <?php
                        /** @var \App\Models\Tenant\DispatchItem $value */
                        $order_note=0;
                        $transfer_description=0;
                        $type_doc=0;
                        $num_doc=0;
                        $name_dispatcher=0;
                        $transfer_description=0;
                        $data = $value->getCollectionData();
                        /* dd($data); */
                        $qty = $data['quantity'];
                        $item = $data['item'];
                        $item_description = $item['description'];
                        $dispatches = $data['dispatches'];
                        $date_of_issue = $dispatches['date_of_issue'];
                        $customer_number = $dispatches['customer_number'];
                        $customer_name = $dispatches['customer_name'];
                        $date_of_shipping = $dispatches['date_of_shipping'];
                        $user_name = $dispatches['user_name'];
                        $number = $dispatches['number'];
                        $state_type_description = $dispatches['state_type_description'];
                        $state_type_id = $dispatches['state_type_id'];
                        /* $order_note = $order['state_type_id']; */
                        if($dispatches['order_notes']){
                            $order_note_id = $dispatches['order_notes']['id'];
                            $order_note_prefix = $dispatches['order_notes']['prefix'];
                            $order_note=$order_note_prefix.'-'.$order_note_id;
                        }
                        
                        if($dispatches['transfer_reason_type']){
                            $transfer_reason=$dispatches['transfer_reason_type']['description'];
                        }
                        $type_doc=$dispatches['type_disparcher'][0]['description'];
                        $dispatcher=(array)$dispatches['dispatcher'];
                        $num_doc=$dispatcher['number'];
                        $name_dispatcher=$dispatcher['name'];
                        $transfer_description = $dispatches['transfer_reason_description']? $dispatches['transfer_reason_description'] : 0;
                        $order_form_description = $dispatches['order_form_description'];
                        ?>

                        <td class="celda">{{$loop->iteration}}</td>

                        <td class="celda">{{ $date_of_issue }}</td>
                        <td class="celda">{{ $customer_name }} <br/> <small>{{ $customer_number }}</small></td>
                        <td class="celda">{{ $user_name }}</td>
                        <td class="celda">{{ $number }}</td>
                        <td class="celda"> {{$state_type_description}} </td>
                        <td class="celda">{{ $date_of_shipping }}</td>
                        <td class="celda"> {{$item_description}} </td>
                        <td class="celda"> {{$value->getQtyFormated()}} </td>
                        <td class="celda">{{$transfer_reason}}</td>
                        <td class="celda">{{$transfer_description}}</td>
                        <td class="celda">{{$type_doc}}</td>
                        <td class="celda">{{$num_doc}}</td>
                        <td class="celda">{{$name_dispatcher}}</td>
                        <td class="celda">{{$order_note}}</td>
                        <td class="celda">{{ $order_form_description }}</td>
                    @php
                        $acum_total += $qty
                    @endphp
                @endforeach
                <tr>
                    <td class="celda" colspan="8"></td>
                    <td class="celda"><strong>Total</strong></td>
                    <td class="celda">{{number_format($acum_total,2)}}</td>
                </tr>
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
