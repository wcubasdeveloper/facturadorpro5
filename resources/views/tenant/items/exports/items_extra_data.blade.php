<?php
// dd($records);
//stock_by_extra
?>
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>Productos</title>
</head>
<body>
<div>
    <h3 align="center"
        class="title"><strong>Reporte Productos</strong></h3>

</div>
<br>
@if(!empty($records))
    <div class="">
        <div class=" ">
            <table class="">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Código interno</th>
                    <th>Nombre</th>
                    <th>Nombre alternativo</th>
                    <th>Descripción</th>
                    <th>Modelo</th>
                    <th>Unidad de medida</th>
                    <th>Posee IGV</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Precio</th>
                    <th>Fecha de vencimiento</th>
                    @if($field == 'colors')
                        <th>Color</th>
                    @elseif($field == 'CatItemMoldProperty')
                        <th>Propiedades de molde</th>
                    @elseif($field == 'CatItemUnitBusiness')
                        <th>Unidades de negocio</th>
                    @elseif($field == 'CatItemStatus')
                        <th>Status</th>
                    @elseif($field == 'CatItemPackageMeasurement')
                        <th>Unidades de medida</th>
                    @elseif($field == 'CatItemProductFamily')
                        <th>Familia de productos</th>
                    @elseif($field == 'CatItemSize')
                        <th>Tamaño/Talla</th>
                    @elseif($field == 'CatItemUnitsPerPackage')
                        <th>Cantidad de unidades por empaque</th>
                    @elseif($field == 'CatItemMoldCavity')
                        <th>Cavidades del molde</th>
                    @endif

                </tr>
                </thead>
                <tbody>
                @foreach($records as $key => $value)
                    <?php
                    $stock_by_extra = $value['stock_by_extra'];
                    $dataToPrint = isset($stock_by_extra[$field]) ? $stock_by_extra[$field]['total'] : 0;
                    // $value = (object)$value;

                    // $item_unit_types = $value->item_unit_types->toArray()
                    ?>
                    <tr>
                        <td class="celda">{{$loop->iteration ?? '' }}</td>
                        <td class="celda">{{$value['internal_id'] ?? '' }}</td>
                        <td class="celda">{{$value['name'] ?? '' }}</td>
                        <td class="celda">{{$value['second_name']  ?? '' }}</td>
                        <td class="celda">{{$value['description']  ?? '' }}</td>
                        <td class="celda">{{$value['model']  ?? '' }}</td>
                        <td class="celda">{{$value['unit_type_id']  ?? '' }}</td>
                        <td class="celda">{{$value['has_igv']  ?? '' }}</td>
                        <td class="celda">{{$value['category_id']  ?? '' }}</td>
                        <td class="celda">{{$value['brand_id']  ?? '' }}</td>

                        <td class="celda">{{$value['sale_unit_price']  ?? '' }}</td>
                        <td class="celda">{{$value['date_of_due']  ?? '' }}</td>
                        @if($field == 'colors' || $field == 'CatItemMoldProperty' || $field == 'CatItemUnitBusiness' || $field == 'CatItemStatus' || $field == 'CatItemPackageMeasurement' || $field == 'CatItemProductFamily' || $field == 'CatItemSize' || $field == 'CatItemUnitsPerPackage' || $field == 'CatItemMoldCavity')
                            <td style="text-align: right">{{$dataToPrint}}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
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
