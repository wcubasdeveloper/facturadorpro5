<?php
use App\Models\Tenant\Company;

$code_plant = '';
$debug = false;
$min_space = 5;
$date = Carbon\Carbon::createFromFormat('Y-m', $dateReport);
$company = Company::first();
$half = 50;
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>Reporte</title>
    <style>
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
<table>
    <thead>
    <tr>
        <th colspan="9"
            style="text-align: center; font-size: 12px; font-weight: bold">

            {{ $company->name  }} {{ 'RUC '.$company->number }}

        </th>
    </tr>
    <tr>
        <th colspan="9"
            style="text-align: center; font-size: 10px; font-weight: bold">

            Libro mayor desde {{$date->firstOfMonth()->format('d-m-Y')}} hasta {{$date->lastOfMonth()->format('d-m-Y')}}
        </th>
    </tr>
    <tr>

        <th colspan="{{$min_space}}" style="width: {{$half}}%;">Cuenta contable</th>
        <th style="width: {{$half / 4}}%">Saldo anterior</th>
        <th style="width: {{$half / 4}}%">Débito</th>
        <th style="width: {{$half / 4}}%">Crédito</th>
        <th style="width: {{$half / 4}}%">Saldo final</th>
    </tr>
    </thead>


    @foreach($records as $index =>$firstLevel)
        <?php

        $after = count(explode('.', $firstLevel["code_account"])) - 1;
        $before = ($min_space - $after);

        ?>

        <tr>
            @if(!empty($after) )
                @for($i=0;$i<$after;$i++)
                    <td style="width: 3%"></td>
                @endfor
            @endif
            <td             @if(!empty($before) ) colspan="{{$before}}"            @endif

            > {!! $firstLevel["name"] !!}</td>



            <td class="text-right">{!! \App\CoreFacturalo\Helpers\Template\ReportHelper::setNumber($firstLevel["last_month_total"]) !!}</td>
            <td class="text-right">{!! \App\CoreFacturalo\Helpers\Template\ReportHelper::setNumber($firstLevel["credits"]) !!}</td>
            <td class="text-right">{!! \App\CoreFacturalo\Helpers\Template\ReportHelper::setNumber($firstLevel["debs"]) !!}</td>
            <td class="text-right"> {!! \App\CoreFacturalo\Helpers\Template\ReportHelper::setNumber($firstLevel["final_total"]) !!}</td>


        </tr>
        <?php $code_plant = $index;
        $currentIndex = $index;
        $arrayData = $firstLevel; ?>

        {{--{!!  \App\CoreFacturalo\Helpers\Template\ReportHelper::getAccountLedgerDataName ($code_plant,$arrayData,$currentIndex)!!}--}}


    @endforeach
</table>
</body>
</html>
