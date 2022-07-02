<html>
@if(!empty($records))

        <span>ID</span>,
        <span>SKU</span>,
        <span>Nombre</span>,
        <span>Publicado</span>,
        <span>Descripción</span>,
        <span>Categoría</span>,

        @foreach($extra_data as $item)
            <?php
            $txt = $item;
            if($item == 'sanitary'){
                $txt = 'R.S.';
            }elseif($item == 'cod_digemid'){
                $txt = 'Cod: DIGEMID';
            }
            ?>
            <th>{{$txt}}</th>

        @endforeach
        <span>Precio</span>,
        <span>Inventario</span>
        @foreach($records as $record)
        <br>
            {{$record->id}},
            {{$record->internal_id}},
            {{$record->second_name}},
            {{$record->description}},
            {{$record->name }},
            {{$record->category_id != '' ? $record->category->name : '' }},
        @foreach($extra_data as $item)
            <?php
            $txt = $record->{$item} ;
            if($item == 'sanitary'){
                $txt = $record->getSanitary();
            }elseif($item == 'cod_digemid'){
                $txt = $record->getCodDigemid();
            }
            ?>
            {{$txt }},
        @endforeach
        {{$record->sale_unit_price }},
            {{$record->stock}}
        @endforeach
        <br>
@endif

</html>
