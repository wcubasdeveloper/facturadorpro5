<?php
use App\Models\Tenant\Establishment;use App\Models\Tenant\Item;
?><!DOCTYPE html>
<html lang="es">
<head>
</head>
<body><?php
/** @var Establishment $establishment */
$establishment = isset($establishment) ? $establishment : Auth::user()->establishment;
$establishment_description = $establishment->description;
/** @var Item $item */
$border = "border: 1px solid black;";
// $border = null;
$style = " font-size: 8px; vertical-align: top;"; // . $border;
?>
@if($item !== null)

    <?php
    $data = $item->getBarCodeData(15);
    ?>

    <table class="table"
           width="100%"
           style="text-align: center; {!! $style !!}">
        <tr>
            <td
                class="celda"
                colspan="3"
                style="text-align: center; {!! $style !!}"
            >
                <p>
                    @if(empty($data['short_name']) ) &nbsp; @else {{ $data['short_name'] }}@endif
                </p>
            </td>
        </tr>
        <tr>
            <td
                width="20%"
                style="text-align: left; {!! $style !!}"
            >
                @if(empty($data['talla']) ) &nbsp; @else {{ $data['talla'] }}@endif

            </td>
            <td
                width="40%"
                style="text-align: center; {!! $style !!}"
            >
                @if(empty($data['short_model']) ) &nbsp; @else {{ $data['short_model'] }}@endif
            </td>
            <td
                width="40%"
                style="text-align: right; {!! $style !!}"
            >
                @if(empty($data['price']) ) &nbsp; @else {{ $data['price'] }}@endif
            </td>

        </tr>
        <tr>
            <td class="celda"
                width="100%"
                colspan="3"
                style="text-align: center; {!! $style !!}">
                <p>
                    @if (empty($data['code']))
                        &nbsp;
                    @else
                        <img style="width:150px; max-height: 15px;"
                             src="{{ $data['bar_code'] }}">
                    @endif
                </p>
                <p style="font-size: 5px">
                    @if(empty($data['code']) ) &nbsp; @else {{ $data['code'] }}@endif

                </p>
            </td>
        </tr>
        <tr>

            <td class="celda"
                width="100%"
                colspan="3"
                style="text-align: center; {!! $style !!}"
            >
                <p>
                    @if(empty($establishment_description) ) &nbsp; @else {{ $establishment_description }}@endif
                </p>
            </td>

        </tr>
    </table>

@else
    <div>
        <p>No se encontraron registros.</p>
    </div>
@endif
</body>
</html>
