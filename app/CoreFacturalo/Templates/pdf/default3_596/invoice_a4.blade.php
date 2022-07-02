<?php
use App\Models\Tenant\Company;use App\Models\Tenant\Document;use App\Models\Tenant\DocumentItem;use App\Models\Tenant\PaymentMethodType;use Illuminate\Database\Eloquent\Collection;
/**
 * @var Document $document
 * @var Company  $company
 */
$cantidad_linea = 30;
$debug = [];
$company = isset($company) ? $company : new Company();
$establishment = $document->establishment;
$establishment_address = ($establishment->address !== '-') ? $establishment->address : null;
$establishment_district = ($establishment->district_id !== '-') ? $establishment->district->description : null;
$establishment_province = ($establishment->province_id !== '-') ? $establishment->province->description : null;
$establishment_department = ($establishment->department_id !== '-') ? $establishment->department->description : null;
$establishment_ubi = $establishment_district . $establishment_province . $establishment_department;
$establishment_trade_address = ($establishment->trade_address !== '-') ? $establishment->trade_address : null;
$establishment_telephone = ($establishment->telephone !== '-') ? $establishment->telephone : null;
$establishment_aditional_information = ($establishment->aditional_information !== '-') ? $establishment->aditional_information : null;
$establishment_web_address = ($establishment->web_address !== '-') ? $establishment->web_address : null;
$establishment_email = ($establishment->email !== '-') ? $establishment->email : null;
$establishment_urbanization = ($establishment->urbanization !== null) ? $establishment->urbanization : null;

$customer = $document->customer;
// $debug[] = $customer->address;
$invoice = $document->invoice;
$document_base = ($document->note) ? $document->note : null;
//$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
$document_number = $document->series . '-' . str_pad($document->number, 8, '0', STR_PAD_LEFT);
$accounts = \App\Models\Tenant\BankAccount::all();
if ($document_base) {
    $affected_document_number = ($document_base->affected_document) ? $document_base->affected_document->series . '-' . str_pad($document_base->affected_document->number, 8, '0', STR_PAD_LEFT) : $document_base->data_affected_document->series . '-' . str_pad($document_base->data_affected_document->number, 8, '0', STR_PAD_LEFT);
} else {
    $affected_document_number = null;
}
$payments = $document->payments;
$document->load('reference_guides');
$total_payment = $document->payments->sum('payment');
$balance = ($document->total - $total_payment) - $document->payments->sum('change');
//calculate items
$allowed_items = 94 - (\App\Models\Tenant\BankAccount::all()->count()) * 2;
$quantity_items = $document->items()->count();
$cycle_items = $allowed_items - ($quantity_items * 3);
$total_weight = 0;
$border_st = "border:1px solid black;";
$four_borders = "border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000";
$igv = 18;
$anchoTotal = (100 / 745);
$customer_name = $customer->name;
$customer_dni = $customer->number;
$customer_phone = $customer->telephone;
$customer_address = "";
$address_clean = true;
$address_length = 50;
$max_address_length = 150;
$blank_line = '';
for ($i = 1; $i < $address_length; $i++) {
    $blank_line .= "&nbsp;";
}
if ($customer->address) {
    $customer_address = $customer->address;
    $customer_address .= ($customer->district_id !== '-') ? ', ' . $customer->district->description : '';
    $customer_address .= ($customer->province_id !== '-') ? ', ' . $customer->province->description : '';
    $customer_address .= ($customer->department_id !== '-') ? ', ' . $customer->department->description : '';
} else {
    $address_clean = false;
    for ($j = 0; $j < 3; $j++) {
        $customer_address .= $blank_line;
    }
}
$customer_address = str_pad($customer_address, $max_address_length, ' ');
$due_date = '';
if (isset($invoice->date_of_due)) {
    $due_date = $invoice->date_of_due->format('d/m/Y');
}
$date = $document->date_of_issue->format('d/m/Y');
$currency = $document->currency_type->description;
$currency_symbol = $document->currency_type->symbol . " ";
$gudie = "";
if ($document->guides) {
    foreach ($document->guides as $item) {
        $gudie .= $item->document_type_description . " : " . $item->number . "<br>";
    }
}
if ($document->payment_condition_id === '01') {
    $paymentCondition = PaymentMethodType::where('id', '10')->first();
} else {
    $paymentCondition = PaymentMethodType::where('id', '09')->first();
}
$sale_condition = $paymentCondition->getDescription();
/** @var Collection $items */
$items = $document->items;
$array_items = [];
function setNubmer($number, $decimal = 2, $mil = ',', $dec = '.')
{
    return number_format($number, $decimal, $mil, $dec);
}
foreach ($items as $item_obj) {
    /** @var DocumentItem $item_obj */
    $item = [];
    $it = (array)$item_obj->item;
    $item['code'] = isset($it['internal_id']) ? $it['internal_id'] : null;
    $item['description'] = isset($it['description']) ? $it['description'] : null;
    if ($item_obj->name_product_pdf && !empty($item_obj->name_product_pdf)) {
        $item['description'] = $item_obj->name_product_pdf;
    }
    if (isset($it['extra'])) {
        $workingItem = $it['extra'];
        if ($workingItem->string) {
            $workingItem = $workingItem->string;
            if ($workingItem->colors) {
                $item['description'] .= " - " . $workingItem->colors;
            }
        }
    }
    $item['qty'] = $item_obj->quantity;
    $item['unit'] = isset($it['unit_type_id']) ? $it['unit_type_id'] : null;
    $item['p_unit'] = ($item_obj->unit_value);
    $total_discount_line = 0;
    $item['dscto'] = 0;
    $dsc = [];
    if ($item_obj->discounts) {
        foreach ($item_obj->discounts as $disto) {
            $dsc[] = $disto;
            $total_discount_line += $disto->amount;
            $item['dscto'] += ($disto->factor * 100);
            // <br/><span style="font-size: 9px">{{ $dtos->factor * 100 }}% {{$dtos->description }}</span>
        }
    }
    $item_obj->dstto = $total_discount_line;
    $item['dsc'] = $dsc;
    $item['dscto1'] = (empty($total_discount_line)) ? null : $total_discount_line;
    $item['neto'] = $item['p_unit'] - ($item['p_unit'] * ($item['dscto'] / 100));
    $item['total'] = ($item['neto'] * $item['qty']);
    $item['dscto'] = (empty($item['dscto'])) ? null : setNubmer($item['dscto'], 2) . "%";
    $item['dscto1'] = (empty($total_discount_line)) ? null : $currency_symbol . setNubmer($total_discount_line, 2);
    $item['qty'] = setNubmer($item['qty'], 1);
    $item['p_unit'] = $currency_symbol . setNubmer($item['p_unit']);
    $item['neto'] = $currency_symbol . setNubmer($item['neto']);
    $item['total'] = $currency_symbol . setNubmer($item['total']);
    $array_items[] = $item;
}
$total_word = '';
$extra_info = '';
$currency_symbol = $document->currency_type->symbol;
$total_gravado = $currency_symbol . " " . setNubmer($document->total_taxed);
$total_inefacta = $currency_symbol . " " . setNubmer($document->total_unaffected);
$total_exonerada = $currency_symbol . " " . setNubmer($document->total_exonerated);
$total_gratuita = $currency_symbol . " " . setNubmer($document->total_free);
$total_descuento = $currency_symbol . " " . setNubmer($document->total_discount);
$total_igv = $currency_symbol . " " . setNubmer($document->total_igv);
$total_importe = $currency_symbol . " " . setNubmer($document->total);
$total_items = count($array_items);
foreach (array_reverse((array)$document->legends) as $row) {
    $total_word .= ($row->code == "1000") ? $row->value . " " . $document->currency_type->description : $row->code . " " . $row->value;
    $total_word .= "<br>";
}
foreach ($document->additional_information as $information) {
    if ($information) {
        $extra_info .= $information . "<br>";
    }
}
$company_name = $company->name;
$company_number = $company->number;
$company_logo = null;
if ($company->logo) {
    $company_logo = $company->logo;
    
}

$logo = "storage/uploads/logos/{$company->logo}";
    if($establishment->logo) {
        $logo = "{$establishment->logo}";
    }


$array_chunk = array_chunk($array_items, $cantidad_linea);
$total_array_chunk = count($array_chunk);
?>
@for($items_in_array = 0; $items_in_array < $total_array_chunk; $items_in_array++)
    @php
        $array_to_work = $array_chunk[$items_in_array] ?? null
    @endphp
    <html>
    <head>
        {{--<title>{{ $document_number }}</title>--}}
        {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
        {{--
        <style>
            td {
                border: 1px solid black;
            }
        </style>
        --}}
    </head>
    <body>
    @if($document->state_type->id == '11')
        <div class="company_logo_box"
             style="position: absolute; text-align: center; top:30%;">
            <img
                src="data:{{mime_content_type(public_path("status_images".DIRECTORY_SEPARATOR."anulado.png")) }};base64, {{base64_encode(file_get_contents(public_path("status_images".DIRECTORY_SEPARATOR."anulado.png"))) }}"
                alt="anulado"
                class=""
                style="opacity: 0.6; {{ $border_st }}">
        </div>
    @endif
    <table border="0"
           height="100px"
           width="100%">
        <tr>
            <td
                align="center"
                valign=middle
                height="84"
                width="25%"
            >
                <img @if($company_logo!=null)
                     src="data:{{mime_content_type(public_path("{$company_logo}")) }};base64,{{base64_encode(file_get_contents(public_path("{$logo"))) }}"
                     @endif alt="{{ $company_name }}"
                     class="company_logo"
                     style="max-height: 50px; max-width: 200px;">

            </td>
            <td height="84"
                width="50%"
                align="center"
                valign=top>
                @if(!empty($company_name))
                    <b style="font-size: 12px">{{ $company_name }}</b>
                    <br>
                    <span style="font-size: 9px">
                    @endif
                        @if(!empty($establishment_department)) Departamento: {{$establishment_department}}, @endif
                        @if(!empty($establishment_province)) Provincia: {{$establishment_province}}, @endif
                        @if(!empty($establishment_district)) Distrito: {{$establishment_district}} @endif
                    <br>
                    @if(!empty($establishment_urbanization))Urbanización: {{ $establishment_urbanization }},  @endif
                        @if(!empty($establishment_address))Dirección: {{ $establishment_address }} <br> @endif
                        {{-- @if(!empty($establishment_trade_address)){{ $establishment_trade_address }} <br> @endif --}}
                        @if(!empty($establishment_telephone))Telf: {{ $establishment_telephone }} <br> @endif


                        @if(!empty($establishment_email))
                            {{ $establishment_email }}
                                                               - @if(!empty($establishment_web_address)){{ $establishment_web_address }} @endif
                        @else
                            @if(!empty($establishment_web_address)){{ $establishment_web_address }} @endif
                        @endif
                    </span>

            </td>
            <td align="left"
                valign=middle
                height="84"
                width="25%">

                <b style="font-size: 12px">
                    R.U.C. N&deg; {{  $company_number }}<br>
                    {{ $document->document_type->description }}<br>
                    {{ $document_number }}<br>
                </b>
            </td>
        </tr>
        <tr>
            <td height="14"
                colspan="34">
                &nbsp;
            </td>
        </tr>
    </table>
    <table border="0"
           width="100%">
        <tr>
            <td colspan=5
                height="14"
                align="right"
                valign=middle
                bgcolor="#CCCCCC">SEÑORES:
            </td>
            <td style="border-top: 1px solid #b2b2b2; border-bottom: 1px solid #b2b2b2; border-left: 1px solid #b2b2b2; border-right: 1px solid #b2b2b2"
                colspan=23
                align="left"
                valign=middle>{{ $customer_name }}</td>
            <td colspan=6
                align="center"
                valign=middle
                bgcolor="#CCCCCC">F. DE EMISIÓN
            </td>
        </tr>
        <tr>
            <td colspan=5
                rowspan=2
                height="28"
                align="right"
                valign=top
                bgcolor="#CCCCCC">DIRECCIÓN:
            </td>
            <td style="border-top: 1px solid #b2b2b2; border-bottom: 1px solid #b2b2b2; border-left: 1px solid #b2b2b2; border-right: 1px solid #b2b2b2"
                colspan=23
                rowspan=2
                align="left"
                valign=top>{{ $customer_address }}</td>
            <td style="border-top: 1px solid #b2b2b2; border-bottom: 1px solid #b2b2b2; border-left: 1px solid #b2b2b2; border-right: 1px solid #b2b2b2"
                colspan=6
                align="center"
                valign=middle>{{ $date }}</td>
        </tr>
        <tr>
            <td colspan=6
                align="center"
                valign=middle
                bgcolor="#CCCCCC">F. DE VENCIMIENTO
            </td>
        </tr>
        <tr>
            <td colspan=5
                height="14"
                align="right"
                valign=middle
                bgcolor="#CCCCCC">TELÉFONO
            </td>
            <td style="border-top: 1px solid #b2b2b2; border-bottom: 1px solid #b2b2b2; border-left: 1px solid #b2b2b2; border-right: 1px solid #b2b2b2"
                colspan=10
                align="left"
                valign=middle>{{ $customer_phone}}</td>
            <td colspan=3
                align="center"
                valign=middle
                bgcolor="#CCCCCC">RUC
            </td>
            <td style="border-top: 1px solid #b2b2b2; border-bottom: 1px solid #b2b2b2; border-left: 1px solid #b2b2b2; border-right: 1px solid #b2b2b2"
                colspan=10
                align="left"
                valign=middle>
                {{ $customer_dni }}
            </td>
            <td style="border-top: 1px solid #b2b2b2; border-bottom: 1px solid #b2b2b2; border-left: 1px solid #b2b2b2; border-right: 1px solid #b2b2b2"
                colspan=6
                align="center"
                valign=middle>{{$due_date }}</td>
        </tr>
        <tr>
            <td height="14"
                colspan="34">
                &nbsp;
            </td>
        </tr>
    </table>
    <table border="0"
           width="100%">
    @php
        $borderTop = "border-top: 1px solid #b2b2b2; border-left: 1px solid #b2b2b2; border-right: 1px solid #b2b2b2"
    @endphp
    <!-- CABECERA ITEMS -->
        <tr>
            <td style="{!! $borderTop !!}"
                height="14"
                width="8%"
                align="center"
                valign=middle
                bgcolor="#CCCCCC">Cant
            </td>
            <td style="{!! $borderTop !!}"
                width="8%"
                align="center"
                valign=middle
                bgcolor="#CCCCCC">U. de Medida
            </td>
            <td style="{!! $borderTop !!}"
                align="center"
                valign=middle
                bgcolor="#CCCCCC">Item
            </td>
            <td style="{!! $borderTop !!}"
                width="15%"
                align="center"
                valign=middle
                bgcolor="#CCCCCC">Valor unitario
            </td>
            <td style="{!! $borderTop !!}"
                width="8%"
                align="center"
                valign=middle
                bgcolor="#CCCCCC">Descuento
            </td>
            <td style="{!! $borderTop !!}"
                width="15%"
                align="center"
                valign=middle
                bgcolor="#CCCCCC">Total
            </td>
        </tr>
        <!-- CABECERA ITEMS -->
        <!-- ITEMS -->
        <!-- ITEMS -->
        @for($i = 0; $i < $cantidad_linea; $i++)
            <?php
            $border = '';
            if ($i == 0) {
                $border = "border-top: 1px solid #000000;";
            } elseif ($i == ($cantidad_linea - 1)) {
                $border = "border-bottom: 1px solid #000000;";
            }
            $item = [
                'code' => null,
                'description' => null,
                'qty' => null,
                'unit' => null,
                'p_unit' => null,
                'dscto' => null,
                'neto' => null,
                'total' => null,
            ];
            $item_blank_line = true;
            if (isset($array_to_work[$i]) && $array_to_work[$i] != null) {
                $item = $array_to_work[$i];
                $item_blank_line = false;
                $item['description'] = substr($item['description'], 0, 50);
            }
            $border_common = 'border-left: 1px solid #b2b2b2; border-right: 1px solid #b2b2b2; font-size: 10px';
            ?>
            <tr>
                <td style="{{ $border_common }}"
                    height="14"
                    width="8%"
                    align="center"
                    valign=middle>{{ $item['qty'] }}</td>
                <td style="{{ $border_common }}"
                    align="left"
                    width="8%"
                    valign=middle>{{ $item['unit'] }}</td>
                <td style="{{ $border_common }}"
                    align="left"
                    width="46%"
                    valign=middle>@if($item_blank_line==true)
                        {!! $item['description'] !!}
                    @else
                        {{ $item['description'] }}
                    @endif</td>
                <td style="{{ $border_common }}"
                    align="right"
                    width="15%"
                    valign=right>{{ $item['p_unit'] }}</td>
                <td style="{{ $border_common }}"
                    align="right"
                    width="8%"
                    valign=middle>{{ $item['dscto'] }}</td>
                <td style="{{ $border_common }}"
                    align="right"
                    width="15%"
                    valign=middle>{{ $item['total'] }}  </td>
            </tr>
    @endfor
    <!-- Tabla de items -->
        <!-- ITEMS -->
        <!-- ITEMS -->
        <tr>
            <td
                height="15"
                colspan="6"
                width="100%"
                align="left"
                style="border-top: 1px solid #b2b2b2;"
                valign=middle>{!! $total_word !!}</td>
        </tr>
        <!-- LETRAS -->
    </table>
    <table border="0"
           width="100%">
        <colgroup span="34"></colgroup>
        <tr>
            <td height="14"
                rowspan=3
                colspan="34"><br>
            </td>
        </tr>
    </table>
    <table border="0"
           width="100%">
        <!-- QR -->
        <tr>
            <td
                height="110"
                width="20%"
                align="center"
                valign=middle>
                <img src="data:image/png;base64, {{ $document->qr }}"
                     style="margin-right: -10px;"/>
                <p style="font-size: 9px">Código Hash: {{ $document->hash }}</p>
            </td>
            <td
                width="80%"
                align="left"
                valign=middle
            >
                Moneda: {{$currency }}<br>
                Fecha y hora de emision : {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>
    <table border="0"
           width="100%">
        <tr>
            <td
                height="112"
                width="70%"
                align="left"
                valign=top>
                {{ $extra_info }}
                <br>
            </td>
            <td
                width="30%"
                align="right">
                <table
                    width="100%"
                    border="0">
                    <tr>
                        <td align="right"
                            width="50%"
                            valign=middle>Importe de venta
                        </td>
                        <td
                            align="right"
                            width="50%"
                            valign=middle>{{ $total_importe }}</td>
                    </tr>
                    <tr>
                        <td
                            align="right"
                            width="50%"
                            valign=middle>Op. gravada
                        </td>
                        <td
                            align="right"
                            width="50%"
                            valign=middle>{{ $total_gravado }}</td>
                    </tr>
                    <tr>
                        <td
                            align="right"
                            width="50%"
                            valign=middle>Op. inafecta
                        </td>
                        <td
                            align="right"
                            width="50%"
                            valign=middle>{{ $total_inefacta }}</td>
                    </tr>
                    <tr>
                        <td
                            align="right"
                            width="50%"
                            valign=middle>Op. exonerada
                        </td>
                        <td
                            align="right"
                            width="50%"
                            valign=middle>{{ $total_exonerada }}</td>
                    </tr>
                    <tr>
                        <td
                            align="right"
                            width="50%"
                            valign=middle>IGV (18.00%)
                        </td>
                        <td
                            align="right"
                            width="50%"
                            valign=middle>{{ $total_igv }}</td>
                    </tr>
                    <tr>
                        <td
                            align="right"
                            width="50%"
                            bgcolor="#CCCCCC"
                            valign=middle>Total
                        </td>
                        <td
                            align="right"
                            width="50%"
                            bgcolor="#CCCCCC"
                            valign=middle>{{ $total_importe }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table border="0"
           width="100%">
        <colgroup span="34"></colgroup>
        <tr>
            <td colspan=34
                rowspan=2
                height="14"
                align="right"
                valign=middle
            ><br>
            </td>
        </tr>
    </table>
    <table border="0"
           width="100%">
        <colgroup span="34"></colgroup>
        <tr>
            <td colspan=34
                rowspan=2
                align="right"
                valign=middle
            > {{$items_in_array +1 }}/{{$total_array_chunk}}
            </td>
        </tr>
    </table>
    @endfor
    {{--    <pre>{{ var_export($debug,true) }}</pre>--}}
    </body>
    </html>
