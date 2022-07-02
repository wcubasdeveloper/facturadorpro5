@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');

    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    // $document_type_driver = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->driver->identity_document_type_id);
    $document_type_dispatcher = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->dispatcher->identity_document_type_id);

    $logo = "storage/uploads/logos/{$company->logo}";
    if($establishment->logo) {
        $logo = "{$establishment->logo}";
    }

@endphp
<html>
<head>
    {{--<title>{{ $document_number }}</title>--}}
    {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
</head>
<body>
@if($company->logo)
    <div class="text-center company_logo_box pt-5">
        <img src="data:{{mime_content_type(public_path("{$logo}"))}};base64, {{base64_encode(file_get_contents(public_path("{$logo}")))}}" alt="{{$company->name}}" class="company_logo_ticket contain">
    </div>
{{--@else--}}
    {{--<div class="text-center company_logo_box pt-5">--}}
        {{--<img src="{{ asset('logo/logo.jpg') }}" class="company_logo_ticket contain">--}}
    {{--</div>--}}
@endif

<table class="full-width">
    <tr>
        <td class="text-center"><h4>{{ $company->name }}</h4></td>
    </tr>
    <tr>
        <td class="text-center"><h5>{{ 'RUC '.$company->number }}</h5></td>
    </tr>
    <tr>
        <td class="text-center" style="text-transform: uppercase;">
            {{ ($establishment->address !== '-')? $establishment->address : '' }}
            {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
            {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }}
            {{ ($establishment->department_id !== '-')? '- '.$establishment->department->description : '' }}
        </td>
    </tr>
    <tr>
        <td class="text-center">{{ ($establishment->email !== '-')? $establishment->email : '' }}</td>
    </tr>
    <tr>
        <td class="text-center pb-3">{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</td>
    </tr>
    <tr>
        <td class="text-center pt-3 border-top"><h4>{{ $document->document_type->description }}</h4></td>
    </tr>
    <tr>
        <td class="text-center pb-3 border-bottom"><h3>{{ $document_number }}</h3></td>
    </tr>
</table>

<table class="full-width border-box mt-10 mb-10">
    <thead>
    <tr>
        <th class="border-bottom text-left">DESTINATARIO</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Razón Social: {{ $customer->name }}</td>
    </tr>
    <tr>
        <td>RUC: {{ $customer->number }}
        </td>
    </tr>
    <tr>
        <td>Dirección: {{ $customer->address }}
            {{ ($customer->district_id !== '-')? ', '.$customer->district->description : '' }}
            {{ ($customer->province_id !== '-')? ', '.$customer->province->description : '' }}
            {{ ($customer->department_id !== '-')? '- '.$customer->department->description : '' }}
        </td>
    </tr>
    @if ($customer->telephone)
    <tr>
        <td>Teléfono:{{ $customer->telephone }}</td>
    </tr>
    @endif
    <tr>
        <td>Vendedor: {{ $document->user->name }}</td>
    </tr>
    </tbody>
</table>
<table class="full-width border-box mt-10 mb-10">
    <thead>
    <tr>
        <th class="border-bottom text-left" colspan="2">ENVIO</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Fecha Emisión: {{ $document->date_of_issue->format('Y-m-d') }}</td>
        <td>Fecha Inicio de Traslado: {{ $document->date_of_shipping->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td>Motivo Traslado: {{ $document->transfer_reason_type->description }}</td>
        <td>Modalidad de Transporte: {{ $document->transport_mode_type->description }}</td>
    </tr>
    
    @if($document->related)
    <tr>
        <td>Número de documento (DAM): {{ $document->related->number }}</td>
        <td>Tipo documento relacionado: {{ $document->getRelatedDocumentTypeDescription() }}</td>
    </tr>
    @endif

    <tr>
        <td>Peso Bruto Total({{ $document->unit_type_id }}): {{ $document->total_weight }}</td>
        @if($document->packages_number)
        <td>Número de Bultos: {{ $document->packages_number }}</td>
        @endif
    </tr>
    <tr>
        <td>P.Partida: {{ $document->origin->location_id }} - {{ $document->origin->address }}</td>
        <td>P.Llegada: {{ $document->delivery->location_id }} - {{ $document->delivery->address }}</td>
    </tr>
    </tbody>
</table>
<table class="full-width border-box mt-10 mb-10">
    <thead>
    <tr>
        <th class="border-bottom text-left" colspan="2">TRANSPORTE</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Nombre y/o razón social: {{ $document->dispatcher->name }}</td>
        <td>{{ $document_type_dispatcher->description }}: {{ $document->dispatcher->number }}</td>
    </tr>
    <tbody>
    <tr>
        @if($document->license_plate)
        <td>Número de placa del vehículo: {{ $document->license_plate }}</td>
        @endif
        @if($document->driver->number)
        <td>Conductor: {{ $document->driver->number }}</td>
        @endif
    </tr>
    <tr>
        @if($document->secondary_license_plates)
            @if($document->secondary_license_plates->semitrailer)
                <td>Número de placa semirremolque: {{ $document->secondary_license_plates->semitrailer }}</td>
            @endif
        @endif
        @if($document->driver->license)
            <td>Licencia del conductor: {{ $document->driver->license }}</td>
        @endif
    </tr>
</table>
<table class="full-width border-box mt-10 mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom text-center">Item</th>
        <th class="border-top-bottom text-center">Código</th>
        <th class="border-top-bottom text-left">Descripción</th>
        <th class="border-top-bottom text-left">Modelo</th>
        <th class="border-top-bottom text-center">Unidad</th>
        <th class="border-top-bottom text-right">Cantidad</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center">{{ $row->item->internal_id }}</td>
            <td class="text-left">
                @if($row->name_product_pdf)
                    {!!$row->name_product_pdf!!}
                @else
                    {!!$row->item->description!!}
                @endif

                @if (!empty($row->item->presentation)) {!!$row->item->presentation->description!!} @endif

                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        <br/><span style="font-size: 9px">{!! $attr->description !!} : {{ $attr->value }}</span>
                    @endforeach
                @endif
                @if($row->discounts)
                    @foreach($row->discounts as $dtos)
                        <br/><span style="font-size: 9px">{{ $dtos->factor * 100 }}% {{$dtos->description }}</span>
                    @endforeach
                @endif
                @if($row->relation_item->is_set == 1)
                    <br>
                    @inject('itemSet', 'App\Services\ItemSetService')
                    @foreach ($itemSet->getItemsSet($row->item_id) as $item)
                        {{$item}}<br>
                    @endforeach
                @endif

                @if($document->has_prepayment)
                    <br>
                    *** Pago Anticipado ***
                @endif
            </td>
            <td class="text-left">{{ $row->item->model ?? '' }}</td>
            <td class="text-center">{{ $row->item->unit_type_id }}</td>
            <td class="text-right">
                @if(((int)$row->quantity != $row->quantity))
                    {{ $row->quantity }}
                @else
                    {{ number_format($row->quantity, 0) }}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@if($document->observations)
<table class="full-width border-box mt-10 mb-10">
    <tr>
        <td class="text-bold border-bottom font-bold">OBSERVACIONES</td>
    </tr>
    <tr>
        <td>{{ $document->observations }}</td>
    </tr>
</table>
@endif

@if ($document->reference_document)
<table class="full-width border-box">
    @if($document->reference_document)
    <tr>
        <td class="text-bold border-bottom font-bold">{{$document->reference_document->document_type->description}}</td>
    </tr>
    <tr>
        <td>{{ ($document->reference_document) ? $document->reference_document->number_full : "" }}</td>
    </tr>
    @endif
</table>
@endif
@if ($document->data_affected_document)
    @php
        $document_data_affected_document = $document->data_affected_document;

    $number = (property_exists($document_data_affected_document,'number'))?$document_data_affected_document->number:null;
    $series = (property_exists($document_data_affected_document,'series'))?$document_data_affected_document->series:null;
    $document_type_id = (property_exists($document_data_affected_document,'document_type_id'))?$document_data_affected_document->document_type_id:null;

    @endphp
    @if($number !== null && $series !== null && $document_type_id !== null)

        @php
            $documentType  = App\Models\Tenant\Catalogs\DocumentType::find($document_type_id);
            $textDocumentType = $documentType->getDescription();
        @endphp
        <table class="full-width border-box">
            <tr>
                <td class="text-bold border-bottom font-bold">{{$textDocumentType}}</td>
            </tr>
            <tr>
                <td>{{$series }}-{{$number}}</td>
            </tr>
        </table>
    @endif
@endif
@if ($document->reference_order_form_id)
<table class="full-width border-box">
    @if($document->order_form)
    <tr>
        <td class="text-bold border-bottom font-bold">ORDEN DE PEDIDO</td>
    </tr>
    <tr>
        <td>{{ ($document->order_form) ? $document->order_form->number_full : "" }}</td>
    </tr>
    @endif
</table>

@elseif ($document->order_form_external)
<table class="full-width border-box">
    <tr>
        <td class="text-bold border-bottom font-bold">ORDEN DE PEDIDO</td>
    </tr>
    <tr>
        <td>{{ $document->order_form_external }}</td>
    </tr>
</table>

@endif

@if ($document->reference_sale_note_id)
<table class="full-width border-box">
    @if($document->sale_note)
    <tr>
        <td class="text-bold border-bottom font-bold">NOTA DE VENTA</td>
    </tr>
    <tr>
        <td>{{ ($document->sale_note) ? $document->sale_note->number_full : "" }}</td>
    </tr>
    @endif
</table>
@endif
</body>
</html>
