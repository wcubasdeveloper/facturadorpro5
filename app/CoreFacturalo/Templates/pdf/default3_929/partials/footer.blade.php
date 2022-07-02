<?php
use App\CoreFacturalo\Helpers\Template\TemplateHelper;use App\Models\Tenant\Company;
use App\Models\Tenant\Document;use App\Models\Tenant\DocumentItem;use App\Models\Tenant\PaymentMethodType;use Illuminate\Database\Eloquent\Collection;
/**
 * @var Document $document
 * @var Company  $company
 */


$debug = [];
$company = isset($company) ? $company : new Company();
$establishment = $document->establishment;

$establishment_address = ($establishment->address !== '-') ? $establishment->address : null;
$establishment_district = ($establishment->district_id !== '-') ? $establishment->district->description : null;
$establishment_province = ($establishment->province_id !== '-') ? ' - ' . $establishment->province->description : null;
$establishment_department = ($establishment->department_id !== '-') ? ' - ' . $establishment->department->description : null;

$establishment_ubi = $establishment_district . $establishment_province . $establishment_department;
$establishment_trade_address = ($establishment->trade_address !== '-') ? 'D. Fiscal: ' . $establishment->trade_address : null;
$establishment_telephone = ($establishment->telephone !== '-') ? 'Central telefónica: ' . $establishment->telephone : null;
$establishment_aditional_information = ($establishment->aditional_information !== '-') ? $establishment->aditional_information : null;
$establishment_web_address = ($establishment->web_address !== '-') ? 'Web: ' . $establishment->web_address : null;
$establishment_email = ($establishment->email !== '-') ? 'Email: ' . $establishment->email : null;


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
$three_borders = "border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000";

$igv = 18;
$anchoTotal = (100 / 745);
$customer_name = $customer->name;
$customer_dni = $customer->number;
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

$date = $document->date_of_issue->format('d/m/Y');
$currency = $document->currency_type->description;
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
// Condicion de pago
$condition = TemplateHelper::getDocumentPaymentCondition($document);
// Pago/Coutas detalladas
$paymentDetailed = TemplateHelper::getDetailedPayment($document);
foreach ($items as $item_obj) {
    /** @var DocumentItem $item_obj */
    $item = $item_obj->toArray();
    $it = (array)$item_obj->item;
    $item['code'] = isset($it['internal_id']) ? $it['internal_id'] : null;
    $item['description'] = isset($it['description']) ? $it['description'] : null;
    if ($item_obj->name_product_pdf && !empty($item_obj->name_product_pdf)) {
        $item['description'] = $item_obj->name_product_pdf;
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
    $item['dscto'] = (empty($item['dscto'])) ? null : TemplateHelper::setNumber($item['dscto'], 2) . "%";
    $item['dscto1'] = (empty($total_discount_line)) ? null : TemplateHelper::setNumber($total_discount_line, 2);

    $item['qty'] = TemplateHelper::setNumber($item['qty'], 1);

    // El precio Unitario
    $item['p_unit'] = TemplateHelper::setNumber($item_obj->unit_price);
    // Valor Unitario
    $item['p_unit'] = TemplateHelper::setNumber($item_obj->unit_price + ($total_discount_line / (float)$item['qty']));
    // $item['p_unit'] = TemplateHelper::setNumber( $item_obj->total_base_igv / $item_obj->quantity);
    $item['neto'] = TemplateHelper::setNumber($item_obj->unit_price);
    // Total de la linea
    $item['total'] = TemplateHelper::setNumber((float)$item_obj->unit_price * (float)$item['qty']);
    $array_items[] = $item;
    // dd($item);

}

$total_word = '';
$extra_info = '';
$currency_symbol = $document->currency_type->symbol;

$total_gravado = $currency_symbol . " " . TemplateHelper::setNumber($document->total_taxed);
$total_inefacta = $currency_symbol . " " . TemplateHelper::setNumber($document->total_unaffected);
$total_exonerada = $currency_symbol . " " . TemplateHelper::setNumber($document->total_exonerated);
$total_gratuita = $currency_symbol . " " . TemplateHelper::setNumber($document->total_free);
$total_descuento = $currency_symbol . " " . TemplateHelper::setNumber($document->total_discount);
$total_igv = $currency_symbol . " " . TemplateHelper::setNumber($document->total_igv);
$total_importe = $currency_symbol . " " . TemplateHelper::setNumber($document->total);

$total_items = count($array_items);
$cantidad_linea = 25;

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



$array_chunk = array_chunk($array_items, $cantidad_linea);
$total_array_chunk = count($array_chunk);

    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
?>
<head>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>
<body>

<table class="full-width">
    <tr>
        <td
            height="17"
            align="left"><br></td>
    </tr>
    <tr>
        <td height="17"
            align="left"
            valign=top>{!! $total_word !!}
        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td style="{{ $four_borders }}" valign="top" width="48%">
                        {{ $extra_info }}
                        <br>
                        <strong>Condición de pago: </strong>{{ $condition }}
                        <br>
                        @if(!empty($paymentDetailed))
                            @foreach($paymentDetailed as $detailed)
                                {{ isset($paymentDetailed['CUOTA'])?'Cuotas:':'Pagos:' }}<br>
                                @foreach($detailed as $row)
                                    &#8226;
                                    {{ $row['description']  }}
                                    {{ isset($paymentDetailed['CUOTA'])?' / FECHA: ':' - ' }}
                                    {{ $row['reference']  }}
                                    {{ isset($paymentDetailed['CUOTA'])?' / Monto: ':'' }}
                                    {{ $row['symbol']  }}
                                    {{ number_format( $row['amount'], 2) }}
                                    <br>
                                @endforeach
                            @endforeach
                        @endif
                    </td>
                    <td style="{{ $four_borders }} ; padding:0px;" width="26%" class="text-center">
                        <img src="data:image/png;base64, {{ $document->qr }}" style="max-width: 90px;"/>
                        <p style="font-size: 9px">{{ $document->hash }}</p>
                    </td>
                    <td style="padding: 0px;" valign=top>
                        <table style="padding: 0px; border: none;" width="100%">
                            <tr>
                                <td style="{{ $three_borders }}"
                                    valign=top><b>Total Op. Gravada</b></td>
                                <td style="{{ $three_borders }}"
                                    valign=top>{{ $total_gravado }}</td>
                            </tr>
                            <tr
                            >
                                <td style="{{ $three_borders }}"
                                    valign=top><b>Total Op. Inefacta</b></td>
                                <td style="{{ $three_borders }}"
                                    valign=top>{{ $total_inefacta }}</td>
                            </tr>
                            <tr>
                                <td style="{{ $three_borders }}"
                                    valign=top><b>Total Op. Exonerada</b></td>
                                <td style="{{ $three_borders }}"
                                    valign=top>{{ $total_exonerada }}</td>
                            </tr>
                            <tr>
                                <td style="{{ $three_borders }}"
                                    valign=top><b>Total Op. Gratuita</b></td>
                                <td style="{{ $three_borders }}"
                                    valign=top>{{ $total_gratuita }}</td>
                            </tr>
                            <tr>
                                <td style="{{ $three_borders }}"
                                    valign=top><b>Total IGV {{ $igv }}%</b></td>
                                <td style="{{ $three_borders }}"
                                    valign=top>{{ $total_igv }}</td>
                            </tr>
                            <tr>
                                <td style="{{ $three_borders }}"
                                    valign=top><b>Importe Total</b></td>
                                <td style="{{ $three_borders }}"
                                    valign=top>{{ $total_importe }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table class="full-width">
    <tr>
        <td class="text-center desc font-bold"><br> Para consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
    </tr>
</table>
</body>
