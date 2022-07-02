<?php

    use Carbon\Carbon;
    use Modules\DocumentaryProcedure\Models\DocumentaryGuidesNumber;

    $key = 0;
    $value = $records ?? null;
    $sender = $value['sender'] ?? null;
    $documentary_process = $value['documentary_process'] ?? null;
    /** @var DocumentaryGuidesNumber $lastGudie */
    $lastGudie = $records['last_guide'] ?? null;
    $guides = $records['guides'] ?? null;
    $dateDocumentary = isset($records['date_register']) && !empty($records['date_register']) ? Carbon::createFromFormat('Y-m-d', $records['date_register']) : Carbon::now();
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>Document</title>
</head>
<body>
<div>

    <h3 align="center"
        class="title"><strong>
            @if($company->logo)
                <div class="company_logo_box">
                    <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}"
                         alt="{{$company->name}}"
                         class="company_logo"
                         style="max-height: 50px; max-width: 400px;">
                </div>
            @endif
        </strong>
        <br>DETALLE DE SEGUIMIENTO DE TRAMITE
        <br>NÚMERO DE SEGUIMIENTO: {{ ($lastGudie!== null)? $lastGudie->guide :''}}
        <br>NÚMERO DE EXPEDIENTE: {{$records['invoice']}}
        <br>AÑO DE TRAMITE: {{$dateDocumentary->format('Y')}}
    </h3>
</div>
<br>
<br>
@if(!empty($guides))
    @foreach($guides as $guide)
        <table width="100%" >
            <tr>
                <td width="35%">Secuencia</td>
                <td>{{$guide->guide}}</td>
            </tr>
            <tr>
                <td width="35%">Etapa</td>
                <td>{{($guide->doc_office_id!== null)?$guide->office->name:'-'}}</td>
            </tr>
            <tr>
                <td width="35%">Observación</td>
                <td>{{$guide->observation}}</td>
            </tr>

            <tr>
                <td width="35%">Fecha de registro</td>
                <td>{{ $guide->created_at }} </td>
            </tr>
            <tr>
                <td width="35%">Fecha que se toma el tramite</td>
                <td>{{ $guide->date_take }} </td>
            </tr>

            <tr>
                <td width="35%">Fecha de Finalización</td>
                <td>{{ $guide->date_end }} </td>
            </tr>


            <tr>
                <td width="35%">Estado</td>
                <td>{{($guide->documentary_guides_number_status_id!== null)?$guide->documentary_guides_number_status->name:'-'}}</td>
            </tr>

            <tr>
                <td width="35%">Responsable</td>
                <td>{{ $guide->user->name }}  </td>
            </tr>
            <tr  >
                <td style="border-top: 1px solid black;">&nbsp;</td>
                <td style="border-top: 1px solid black;">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table >
    @endforeach
@endif

</body>
</html>
