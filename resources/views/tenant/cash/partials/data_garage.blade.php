
@php

    const UNIT_TYPE_ID_GLL = 'GLL';

    // solo se considera cpe y nv
    $group_items = $documents->whereIn('record_type', ['document_item', 'sale_note_item'])
                            ->where('unit_type_id', UNIT_TYPE_ID_GLL)
                            ->groupBy('description');

@endphp

<div class="">
    <div class=" ">
        <table class="">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>{{ UNIT_TYPE_ID_GLL }}</th>
                    <th>Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group_items as $item_description => $row)

                    <tr>
                        <td class="celda">{{ $loop->iteration }}</td>
                        <td class="celda">{{ $item_description }}</td>
                        <td class="celda">{{ $row->sum(function($item){return $item['quantity'];}) }}</td>
                        <td class="celda"> {{ $row->sum(function($item){return $item['total'];}) }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<br>
<br>