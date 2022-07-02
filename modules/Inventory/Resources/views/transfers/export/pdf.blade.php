<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type"
          content="application/pdf; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>Reporte</title>
    @include('inventory::transfers.export.partial.style')


</head>
<body>

<?php

use Modules\Inventory\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;

/** @var Collection|Inventory[] $inventories */
$inventories = !empty($data['inventories']) ? $data['inventories'] : null;
if ($inventories == null) {
    $inventories = new Collection();
    $inventories->push(new Inventory());
}
$pdf = true;

$inventories = !empty($data['inventories']) ? $data['inventories'] : null;
$newInventory = new Collection();

if ($inventories == null) {
    $inventories = new Collection();
    $inventories->push(new Inventory());
}
/*
$e = $inventories->first();
for($i=1;$i<50;$i++){
    $inventories->push($e);
}
*/
if ($inventories->count() > 0) {
    // 18 en horizontal
    $newInventory = $inventories->chunk(31);
}
$totalInv = $newInventory->count();
?>
@if($newInventory->count()>0)

    @foreach ($newInventory as $invIndex => $inventories)
        @include('inventory::transfers.export.partial.header')

        <br>
        @include('inventory::transfers.export.partial.items')
        @if(($invIndex+1) < $totalInv  )
            <div class="page-break"></div>
        @endif
    @endforeach
@else
    @include('inventory::transfers.export.partial.header')

    <br>
    <div>
        <p>No se encontraron registros.</p>
    </div>
@endif
</body>
</html>


