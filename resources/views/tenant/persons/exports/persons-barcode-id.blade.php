<!DOCTYPE html>
<html lang="es">
    <head>
    </head>
    <body>
        @if(!empty($record))
            <div class="">
                <div class=" ">
                    <table class="table" width="100%">
                        @php
                            /* dd($record); */
                        @endphp
                        <tr>
                            <td class="celda" width="33%" style="text-align: center; padding-top: 10px; padding-bottom: 10px; font-size: 9px; vertical-align: top;">
                                <p>{{ $record->name }}</p>
                                <p>
                                    @php
                                        $colour = [0,0,0];
                                        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                        echo '<img style="width:110px; max-height: 40px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($record->barcode, $generator::TYPE_CODE_128, 1, 60, $colour)) . '">';
                                    @endphp
                                </p>
                                <p>{{$record->barcode}}</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        @else
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
