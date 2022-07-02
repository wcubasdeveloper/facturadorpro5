<?php
$document_type_id = $document_type_id ?? null;
$type = $type ?? 'sale';
$plus = $plus ?? 4;
?>
<th style="width:{{$plus+2}}%;">FECHA DE EMISIÓN</th>
<th style="width:{{$plus+2}}%;">USUARIO/VENDEDOR</th>
@if($document_type_id != '80' && $type == 'sale')
    <th style="width:3%;">DIST</th>
    <th style="width:3%;">DPTO</th>
    <th style="width:3%;">PROV</th>
@endif
<th style="width:4%;">SERIE</th>
<th style="width:4%;">NÚMERO</th>
@if( $type == 'sale')
    <th style="width:4%;">ORDEN DE COMPRA</th>
    <th style="width:{{$plus+1}}%;">PLATAFORMA</th>
@endif
<th style="width:{{$plus+2}}%;">DOC ENTIDAD TIPO DNI RUC</th>
<th style="width:{{$plus+4}}%;">DOC ENTIDAD NÚMERO</th>
<th style="width:11%;">DENOMINACIÓN ENTIDAD</th>
<th style="width:{{$plus}}%;">MONEDA</th>
<th style="width:{{$plus+1}}%;">UNIDAD DE MEDIDA</th>
<th style="width:{{$plus+1}}%;">MARCA</th>
<th style="width:{{$plus+8}}%;">DESCRIPCIÓN</th>
{{-- @if($type == 'sale')
    se comenta porque genera inconsistencia en columnas
    <th style="width:{{$plus+1}}%;">MODELO</th>
@endif --}}
<th style="width:{{$plus+1}}%;">CATEGORÍA</th>
<th style="width:{{$plus+1}}%;">CANTIDAD</th>
<th style="width:5%;">PRECIO UNITARIO</th>

@if($type == 'purchase')
<th> TIPO DE ISC</th>
<th> ISC</th>
@endif

<th style="width:5%;">TOTAL</th>
@if($type == 'sale')
    <th style="width:6%;">TOTAL COMPRA</th>
    <th style="width:7%;">GANANCIA</th>
@endif
