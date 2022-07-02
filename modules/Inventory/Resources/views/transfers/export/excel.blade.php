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

    <title>Reporte</title>
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


?>

@include('inventory::transfers.export.partial.header')

<br>
@if($inventories->count()>0)
    @include('inventory::transfers.export.partial.items')
@else
    <div>
        <p>No se encontraron registros.</p>
    </div>
@endif
</body>
</html>


