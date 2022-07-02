@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    // $document_type_driver = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->driver->identity_document_type_id);
    $document_type_dispatcher = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->dispatcher->identity_document_type_id);
@endphp
<html>
<body>
<table class="full-width">
    <tr>
        @if($company->logo)
            <td width="10%">
                <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" alt="{{ $company->name }}"  class="company_logo" style="max-width: 300px">
            </td>
        @else
            <td width="10%">
                {{--<img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px">--}}
            </td>
        @endif
        <td width="50%" class="pl-3 text-center">
            <div class="">
                <h3 class="">{{ $company->name }}</h3>
                <h5 style="text-transform: uppercase;">
                    {{ ($establishment->address !== '-')? $establishment->address : '' }}
                    {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
                    {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }}
                    {{ ($establishment->department_id !== '-')? '- '.$establishment->department->description : '' }}
                </h5>
                <h5>{{ ($establishment->email !== '-')? $establishment->email : '' }}</h5>
                <h5>{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</h5>
            </div>
        </td>
        <td width="40%" class="border-box p-4 text-center">
            <h5 class="">{{ 'RUC '.$company->number }}</h5><br>
            <h4 class="text-center">{{ $document->document_type->description }}</h4><br>
            <h5 class="pt-20 text-center">N° {{ $document_number }}</h5>
        </td>
    </tr>
</table>
<br>
<table class="full-width">
    <tr>
        <td width="22%">Fecha Inicio de Traslado: </td>
        <td>{{ $document->date_of_shipping->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td>RUC:  </td>
        <td>{{ $customer->number }}</td>
    </tr>
    <tr>
        <td>Razón Social:  </td>
        <td>{{ $customer->name }}</td>
    </tr>
    <tr>
        <td>Dirección física:  </td>
        <td>{{ $customer->address }}
            {{ ($customer->district_id !== '-')? ', '.$customer->district->description : '' }}
            {{ ($customer->province_id !== '-')? ', '.$customer->province->description : '' }}
            {{ ($customer->department_id !== '-')? '- '.$customer->department->description : '' }}
        </td>
    </tr>
    <tr>
        <td>Punto Partida: </td>
        <td>{{ $document->origin->location_id }} - {{ $document->origin->address }}</td>
    </tr>
    <tr>
        <td>Punto Llegada: </td>
        <td>{{ $document->delivery->location_id }} - {{ $document->delivery->address }}</td>
    </tr>
    <tr>
        <td colspan="2">
            <table class="full-width">
                <tr>
                    <td>Motivo Traslado: {{ $document->transfer_reason_type->description }}</td>
                    <td>
                        Nro. Factura:
                        @if ($document->reference_document)
                            {{$document->reference_document->document_type->description}}
                            {{$document->reference_document->number_full}}
                        @endif
                    </td>
                    <td>
                        O. Compra:
                        @if ($document->reference_document)
                            @if ($document->reference_document->purchase_order)
                                {{$document->reference_document->purchase_order}}
                            @endif
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="full-width border-box">
    <tr>
        <td valign="top" class="border-top">
            Datos de transportista <br>
            <table class="full-width">
                {{-- <tr>
                    <td width="15%">Nombre:</td>
                    <td>{{ $document->driver->name }}</td>
                </tr> --}}
                <tr>
                    <td>{{ $document->driver->identity_document_type_id }}:</td>
                    <td>{{ $document->driver->number }}</td>
                    <td>N° Licencia:</td>
                    <td>{{ $document->license_plate }}</td>
                </tr>
            </table>
        </td>
        <td class="border-top">
            Datos de transportista <br>
            <table class="full-width">
                <tr>
                    <td width="15%">Nombre:</td>
                    <td>{{ $document->dispatcher->name }}</td>
                    <td width="15%">{{ $document_type_dispatcher->description }}:</td>
                    <td>{{ $document->dispatcher->number }}</td>
                </tr>
                <tr>
                    <td>Placa:</td>
                    @if($document->license_plate)
                        <td>{{ $document->license_plate }}</td>
                    @endif
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>