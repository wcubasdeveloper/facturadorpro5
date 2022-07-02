<!DOCTYPE html>
<html lang="es">
<head>
</head>
<body>
@if(!empty($records))
    <div class="">
        <div class=" ">
            <table class="table" width="100%">
                <tr>
                    <td>CodEstab</td>
                    <td>CodProd</td>
                    @for($i=0;$i<$max_price;$i++)
                        <td>Precio {{$i+1}}</td>
                    @endfor
                </tr>
                @foreach($records as $digemid )
                    <?php
                    /** @var \Modules\Digemid\Models\CatDigemid $digemid */
                    $cod_prod = $digemid->getCodDigemid();
                    $precios = $digemid->getArrayPrices();
                    ?>
                    <tr>
                        <td>{{$company_cod_digemid}}</td>
                        <td>{{$cod_prod}}</td>
                        @for($i=0;$i<$max_price;$i++)
                            <td>{{isset($precios[$i])?$precios[$i]:"0,00"}}</td>
                        @endfor

                    </tr>
                @endforeach
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
