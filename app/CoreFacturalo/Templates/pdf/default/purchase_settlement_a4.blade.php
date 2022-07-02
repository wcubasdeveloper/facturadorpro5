@php
    $establishment = $document->establishment;
    $supplier = $document->supplier;
    $operation_data = $document->operation_data;
    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
@endphp
<html>
<head>
    {{-- <title>{{ $document_number }}</title> --}}
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>
<body>
<table class="full-width">
    <tr>
        @if($company->logo)
            <td width="20%">
                <div class="company_logo_box">
                    <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo" style="max-width: 150px;">
                </div>
            </td>
        @else
            <td width="20%">
                <img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px">
            </td>
        @endif
        <td width="50%" class="pl-3">
            <div class="text-left">
                <h4 class="">{{ $company->name }}</h4>
                <h5>{{ 'RUC '.$company->number }}</h5>
                <h6>{{ ($establishment->address !== '-')? $establishment->address : '' }}</h6>
                <h6>{{ ($establishment->email !== '-')? $establishment->email : '' }}</h6>
                <h6>{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</h6>
            </div>
        </td>
        <td width="30%" class="border-box py-4 px-2 text-center">
            <h5 class="text-center">{{ $document->document_type->description }}</h5>
            <h3 class="text-center">{{ $document_number }}</h3>
        </td>
    </tr>
</table>
<table class="full-width mt-5">
    <tr>
        <td width="15%">Vendedor:</td>
        <td width="45%">{{ $supplier->name }}</td>
        <td width="25%">Fecha de emisión:</td>
        <td width="15%">{{ $document->date_of_issue->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td>{{ $supplier->identity_document_type->description }}:</td>
        <td>{{ $supplier->number }}</td> 
    </tr>
    <tr>
        <td class="align-top">Dirección:</td>
        <td colspan="3">
            {{ $supplier->address }}
            {{ ($supplier->district_id !== '-')? '- '.$supplier->district->description : '' }}
            {{ ($supplier->province_id !== '-')? '- '.$supplier->province->description : '' }}
            {{ ($supplier->department_id !== '-')? '- '.$supplier->department->description : '' }}
        </td>
    </tr>
    <tr>
        <td width="15%">Tipo de dirección:</td>
        <td colspan="3">{{ $operation_data->address_type->description }}</td>
    </tr> 
    <tr>
        <td width="15%">Moneda:</td>
        <td colspan="3">{{ $document->currency_type->description }}</td>
    </tr> 
</table>

<table class="full-width mt-2">
    <tr>
        <td colspan="4"><strong>Datos de la operación</strong></td>
    </tr>
    <tr>
        <td class="align-top">Dirección:</td>
        <td colspan="3">
            {{ $operation_data->address }} 
            {{ ($operation_data->district_id !== '-')? '- '.$operation_data->district->description : '' }}
            {{ ($operation_data->province_id !== '-')? '- '.$operation_data->province->description : '' }}
            {{ ($operation_data->department_id !== '-')? '- '.$operation_data->department->description : '' }}
        </td>
    </tr>
    <tr>
        <td width="15%">Tipo de dirección:</td>
        <td colspan="3">{{ $operation_data->address_type->description }}</td>
    </tr> 
</table>
 

<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr class="bg-grey">
        <th class="border-top-bottom text-center py-2" width="8%">COD.</th>
        <th class="border-top-bottom text-center py-2" width="8%">CANT.</th>
        <th class="border-top-bottom text-center py-2" width="8%">UNIDAD</th>
        <th class="border-top-bottom text-left py-2">DESCRIPCIÓN</th>
        <th class="border-top-bottom text-right py-2" width="12%">V.UNIT</th>
        <th class="border-top-bottom text-right py-2" width="12%">P.UNIT</th>
        <th class="border-top-bottom text-right py-2" width="12%">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center align-top">{{ $row->item->internal_id }}</td>
            <td class="text-center align-top">{{ $row->quantity }}</td>
            <td class="text-center align-top">{{ $row->item->unit_type_id }}</td>
            <td class="text-left">
                {!! $row->item->description !!} 
            </td>
            <td class="text-right align-top">{{ number_format($row->unit_value, 2) }}</td>
            <td class="text-right align-top">{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right align-top">{{ number_format($row->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="7" class="border-bottom"></td>
        </tr>
    @endforeach 
        @if($document->total_unaffected > 0)
            <tr>
                <td colspan="6" class="text-right font-bold">OP. INAFECTAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_unaffected, 2) }}</td>
            </tr>
        @endif
        @if($document->total_exonerated > 0)
            <tr>
                <td colspan="6" class="text-right font-bold">OP. EXONERADAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_exonerated, 2) }}</td>
            </tr>
        @endif
        @if($document->total_taxed > 0)
            <tr>
                <td colspan="6" class="text-right font-bold">OP. GRAVADAS: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total_taxed, 2) }}</td>
            </tr>
        @endif 
        <tr>
            <td colspan="6" class="text-right font-bold">IGV: {{ $document->currency_type->symbol }}</td>
            <td class="text-right font-bold">{{ number_format($document->total_igv, 2) }}</td>
        </tr> 
        <tr>
            <td colspan="6" class="text-right font-bold">IMPORTE TOTAL: {{ $document->currency_type->symbol }}</td>
            <td class="text-right font-bold">{{ number_format($document->total, 2) }}</td>
        </tr>
    </tbody>
</table> 
<table class="full-width">
    <tr>
        <td width="65%">
            @foreach($document->legends as $row)
                <p>Son: <span class="font-bold">{{ $row->value }} {{ $document->currency_type->description }}</span></p>
            @endforeach 
        </td>
    </tr>
</table>
</body>
</html>