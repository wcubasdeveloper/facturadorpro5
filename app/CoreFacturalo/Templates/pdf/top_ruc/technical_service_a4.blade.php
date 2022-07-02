<?php

use App\Models\Tenant\Establishment;
use App\Models\Tenant\Person;
use Modules\Sale\Models\TechnicalService;use Modules\Sale\Models\TechnicalServicePayment;

/** @var TechnicalService $document
 * @var Establishment $establishment
 * @var Person        $customer
 */

$element = $document->getPdfData();
$balance = ($document->cost - $document->prepayment);
$total_items = 0;
$establishment = $document->user->establishment;
$customer = $document->customer;
$tittle = str_pad($document->id, 8, '0', STR_PAD_LEFT);
$prepayment = $document->technical_service_payments->sum('payment');
?>
<html>
<head>
    {{--<title>{{ $tittle }}</title>--}}
    {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
</head>
<body>
<table class="full-width">
    <tr>
        @if($company->logo)
            <td width="20%">
                <div class="company_logo_box">
                    <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}"
                         alt="{{$company->name}}"
                         class="company_logo"
                         style="max-width: 150px;">
                </div>
            </td>
        @else
            <td width="20%">
                {{--<img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px">--}}
            </td>
        @endif
        <td width="50%"
            class="pl-3">
            <div class="text-left">
                <h4 class="">{{ $company->name }}</h4>
                <h5>{{ 'RUC '.$company->number }}</h5>
                <h6>
                    {{ ($establishment->address !== '-')? $establishment->address : '' }}
                    {{ ($establishment->district_id !== '-')? ', '.$establishment->district->description : '' }}
                    {{ ($establishment->province_id !== '-')? ', '.$establishment->province->description : '' }}
                    {{ ($establishment->department_id !== '-')? '- '.$establishment->department->description : '' }}
                </h6>

                @isset($establishment->trade_address)
                    <h6>{{ ($establishment->trade_address !== '-')? 'D. Comercial: '.$establishment->trade_address : '' }}</h6>
                @endisset
                <h6>{{ ($establishment->telephone !== '-')? 'Central telefónica: '.$establishment->telephone : '' }}</h6>

                <h6>{{ ($establishment->email !== '-')? 'Email: '.$establishment->email : '' }}</h6>

                @isset($establishment->web_address)
                    <h6>{{ ($establishment->web_address !== '-')? 'Web: '.$establishment->web_address : '' }}</h6>
                @endisset

                @isset($establishment->aditional_information)
                    <h6>{{ ($establishment->aditional_information !== '-')? $establishment->aditional_information : '' }}</h6>
                @endisset
            </div>
        </td>
        <td width="30%"
            class="border-box py-4 px-2 text-center">
            <h5 class="text-center">SERVICIO TÉCNICO</h5>
            <h3 class="text-center">{{ $tittle }}</h3>
        </td>
    </tr>
</table>
<table class="full-width mt-5">
    <tr>
        <td width="15%">Cliente:</td>
        <td width="45%">{{ $customer->name }}</td>
        <td width="25%">Fecha de emisión:</td>
        <td width="15%">{{ $document->date_of_issue->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td>{{ $customer->identity_document_type->description }}:</td>
        <td>{{ $customer->number }}</td>

    </tr>
    @if ($customer->address !== '')
        <tr>
            <td class="align-top">Dirección:</td>
            <td colspan="">
                {{ $customer->address }}
                {{ ($customer->district_id !== '-')? ', '.$customer->district->description : '' }}
                {{ ($customer->province_id !== '-')? ', '.$customer->province->description : '' }}
                {{ ($customer->department_id !== '-')? '- '.$customer->department->description : '' }}
            </td>
        </tr>
    @endif
    <tr>
        <td class="align-top">Celular:</td>
        <td colspan="3">
            {{ $document->cellphone }}
        </td>
    </tr>
    <tr>
        <td class="align-top">N° Serie:</td>
        <td colspan="3">
            {{ $document->serial_number }}
        </td>
    </tr>
</table>


<table class="full-width mt-4 mb-5">
    <tr>
        <td><b>Descripción:</b></td>
    </tr>
    <tr>
        <td>{{ $document->description }}</td>
    </tr>
    <tr>
        <td><b> Estado:</b></td>
    </tr>
    <tr>
        <td>{{ $document->state }}</td>
    </tr>

    <tr>
        <td><b>Motivo:</b></td>
    </tr>
    <tr>
        <td>{{ $document->reason }}</td>
    </tr>
    @if($document->activities)
        <tr>
            <td><b>Actividades realizadas:</b></td>
        </tr>
        <tr>
            <td>{{ $document->activities }}</td>
        </tr>
    @endif
</table>

<!-- items -->
<!-- items -->
@if(count($element['items'])>0)
    <table class="full-width mt-10 mb-10">
        <thead class="">
        <tr class="bg-grey">
            <th class="border-top-bottom text-center py-2"
                width="8%">CANT.
            </th>
            <th class="border-top-bottom text-center py-2"
                width="8%">UNIDAD
            </th>
            <th class="border-top-bottom text-left py-2">DESCRIPCIÓN</th>
            <th class="border-top-bottom text-right py-2"
                width="12%">P.UNIT
            </th>
            <th class="border-top-bottom text-right py-2"
                width="8%">DTO.
            </th>
            <th class="border-top-bottom text-right py-2"
                width="12%">TOTAL
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($document->items as $row)
            <tr>
                <td class="text-center align-top">
                    @if(((int)$row->quantity != $row->quantity))
                        {{ $row->quantity }}
                    @else
                        {{ number_format($row->quantity, 0) }}
                    @endif
                </td>
                <td class="text-center align-top">{{ $row->item->unit_type_id }}</td>
                <td class="text-left align-top">
                    {!!$row->item->description!!} @if (!empty($row->item->presentation)) {!!$row->item->presentation->description!!} @endif

                    @foreach($row->additional_information as $information)
                        @if ($information)
                            <br/><span style="font-size: 9px">{{ $information }}</span>
                        @endif
                    @endforeach

                    @isset($row->item->lots)
                        @foreach($row->item->lots as $lot)
                            <br/><span style="font-size: 9px">Serie : {{ $lot->series }}</span>
                        @endforeach
                    @endisset
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

                    @if($row->item->is_set == 1)
                        <br>
                        @inject('itemSet', 'App\Services\ItemSetService')
                        {{join( "-", $itemSet->getItemsSet($row->item_id) )}}
                    @endif
                </td>
                <td class="text-right align-top">{{ number_format($row->unit_price, 2) }}</td>
                <td class="text-right align-top">
                    @if($row->discounts)
                        @php
                            $total_discount_line = 0;
                            foreach ($row->discounts as $disto) {
                                $total_discount_line = $total_discount_line + $disto->amount;
                            }
                        @endphp
                        {{ number_format($total_discount_line, 2) }}
                    @else
                        0
                    @endif
                </td>
                <td class="text-right align-top">{{ number_format($row->total, 2) }}</td>
            </tr>
            @php
                $total_items += $row->total
            @endphp
            <tr>
                <td colspan="6"
                    class="border-bottom"></td>
            </tr>
        @endforeach



        @if ($document->prepayments)
            @foreach($document->prepayments as $p)
                <?php
                $total = $p->total;
                $text = 'BOLETA';
                if(($p->document_type_id == '02')){
                    $text = 'FACTURA';
                }
                $text .= " NRO. ".$p->number;
                if (get_class($p) == TechnicalServicePayment::class) {
                    $total = $p->getTotal();
                    $text = '';
                    $text .= " NRO. ".$p->reference;

                }
                ?>
                <tr>
                    <td class="text-center align-top">
                        1
                    </td>
                    <td class="text-center align-top">NIU</td>
                    <td class="text-left align-top">
                        ANTICIPO: {{ $text }}
                    </td>
                    <td class="text-right align-top">-{{ number_format($total, 2) }}</td>
                    <td class="text-right align-top">
                        0
                    </td>
                    <td class="text-right align-top">-{{ number_format($total, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="6"
                        class="border-bottom"></td>
                </tr>
            @endforeach
        @endif


        {{--
                @if($document->perception)

                    <tr>
                        <td colspan="5"
                            class="text-right font-bold"> IMPORTE TOTAL: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-bold">{{ number_format($document->total, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"
                            class="text-right font-bold">PERCEPCIÓN: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-bold">{{ number_format($document->perception->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"
                            class="text-right font-bold">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
                        <td class="text-right font-bold">{{ number_format(($document->total + $document->perception->amount), 2) }}</td>
                    </tr>
        @else
            <tr>
                <td colspan="5"
                    class="text-right font-bold">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format($document->total, 2) }}</td>
            </tr>
        @endif
        --}}
        @php
            $balance +=  $total_items
        @endphp
        @if($balance < 0)

            {{--
            <tr>
                <td colspan="5"
                    class="text-right font-bold">VUELTO: {{ $document->currency_type->symbol }}</td>
                <td class="text-right font-bold">{{ number_format(abs($balance),2, ".", "") }}</td>
            </tr>
            --}}

        @endif


        </tbody>
    </table>
@endif
<!-- items -->
<!-- items -->

<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr class="bg-grey">
    </tr>
    </thead>
    <tbody>
    <tr>
    </tr>
    @if($total_items != 0)
        <tr>
            <td colspan="4"
                class="text-right font-bold mb-3">Total de prodcutos
            </td>
            <td class="text-right font-bold">{{ number_format($total_items, 2) }}</td>
        </tr>
    @endif
    <tr>
        <td colspan="4"
            class="text-right font-bold mb-3">COSTO DEL SERVICIO:
        </td>
        <td class="text-right font-bold">{{ number_format($document->cost, 2) }}</td>
    </tr>
    <tr>
        <td colspan="4"
            class="text-right font-bold">PAGO ADELANTADO:
        </td>
        <td class="text-right font-bold">{{ number_format($prepayment, 2) }}</td>
    </tr>
    <tr>
        <td colspan="4"
            class="text-right font-bold">SALDO A PAGAR:
        </td>
        <td class="text-right font-bold">{{ number_format(($balance - $prepayment), 2) }}</td>
    </tr>
    </tbody>
</table>


</body>
</html>
