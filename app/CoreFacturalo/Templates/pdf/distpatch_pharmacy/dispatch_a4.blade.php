@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    // $document_type_driver = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->driver->identity_document_type_id);
    $document_type_dispatcher = App\Models\Tenant\Catalogs\IdentityDocumentType::findOrFail($document->dispatcher->identity_document_type_id);
@endphp
<html>
<body>
<table class="full-width border-box mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom text-center">Código</th>
        <th class="border-top-bottom text-center">Cantidad</th>
        <th class="border-top-bottom text-center">Medida</th>
        <th class="border-top-bottom text-left">Descripción</th>
        <th class="border-top-bottom text-left">Lote</th>
        <th class="border-top-bottom text-left">Vencimiento</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center">{{ $row->item->internal_id }}</td>
            <td class="text-center">
                @if(((int)$row->quantity != $row->quantity))
                    {{ $row->quantity }}
                @else
                    {{ number_format($row->quantity, 0) }}
                @endif
            </td>
            <td class="text-center">{{ $row->item->unit_type_id }}</td>
            <td class="text-left">
                @if($row->name_product_pdf)
                    {!!$row->name_product_pdf!!}
                @else
                    {!!$row->item->description!!}
                @endif

                @if (!empty($row->item->presentation)) {!!$row->item->presentation->description!!} @endif

                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        <br/><span style="font-size: 9px">{!! $attr->description !!} : {{ $attr->value }}</span>
                    @endforeach
                @endif
                @if($row->discounts)
                    @foreach($row->discounts as $dtos)
                        <br/><span style="font-size: 9px">{{ $dtos->factor * 100 }}% {{$dtos->description }}</span>
                    @endforeach
                @endif
                @if($row->relation_item->is_set == 1)
                    <br>
                    @inject('itemSet', 'App\Services\ItemSetService')
                    @foreach ($itemSet->getItemsSet($row->item_id) as $item)
                        {{$item}}<br>
                    @endforeach
                @endif

                @if($document->has_prepayment)
                    <br>
                    *** Pago Anticipado ***
                @endif
            </td>
            <td class="text-left">
                @inject('itemLotGroup', 'App\Services\ItemLotsGroupService')
                {{ $itemLotGroup->getLote($row->item->IdLoteSelected) }}
            </td>
            <td>
                <?php
                // la fecha de vencimiento por lote se modifica aqui app/CoreFacturalo/Requests/Inputs/DispatchInput.php
                try {
                    $date_of_due = $row->item->lot_group->date_of_due;
                } catch (ErrorException $e) {
                    try {
                        $date_of_due = $row->item->date_of_due;
                    } catch (ErrorException $e) {
                        $date_of_due = '';
                    }
                }

                ?>

                {{$date_of_due}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
