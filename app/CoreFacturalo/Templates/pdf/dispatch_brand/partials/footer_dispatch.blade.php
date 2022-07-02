@php
    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
@endphp
<head>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>
<body>
<table class="full-width">
    <tr>
        <td width="35%">
            Motivos de Traslados
        </td>
        <td width="35%"><br></td>
        <td class="text-center"> Representación impresa de Guía de Remisión Electrónica</td>
    </tr>
    <tr>
        <td class="border-box">
            <table class="full-width">
                <tr>
                    <td valign="top" style="font-size: 9px;">
                        1. Venta <br>
                        2. Venta a Confirmación <br>
                        3. Consignación <br>
                        4. Devolución <br>
                        5. Exportación <br>
                        6. Muestras <br>
                    </td>
                    <td valign="top" style="font-size: 9px;">
                        7. Baja por Vencimiento <br>
                        8. Baja por Deterioro <br>
                        9. Traslado/Almacenes <br>
                        10. Otros <br>
                    </td>
                </tr>
            </table>
        </td>
        <td class="border-box text-center" valign="top">
            RECIBO CONFORME
            <hr>
        </td>
        <td  valign="top">
            {{ $document->hash }}
        </td>
    </tr>
</table>
<table class="full-width">
    <tr>
        <td class="text-center desc font-bold">Para consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
    </tr>
</table>
</body>