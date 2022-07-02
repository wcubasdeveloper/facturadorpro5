<table> 
    <tr>
        <td>FECHAE</td>
        <td>TIPO</td>
        <td>SERIE</td>
        <td>NUMERO</td>
        <td>DOCUMENTO</td>
        <td>CLIENTE</td>
        <td>SUBTOTAL</td>
        <td>IGV</td>
        <td>EXO</td>
        <td>TOTAL</td>
        <td>ANULADO</td>
        <td>PRODUCTO</td>
        <td>CANTIDAD GALON</td>
        <td>PRECIO POR GALON</td>
    </tr>
    @foreach($records as $record)

        @php
            $row = $record->getDataSaleGarageGll();
        @endphp
        
        <tr> 
            <td>
                {{ $row['date_of_issue'] }}
            </td>
            <td>
                {{ $row['document_type_id'] }}
            </td>
            <td>
                {{ $row['series'] }}
            </td>
            <td>
                {{ $row['number'] }}
            </td>
            <td>
                {{ $row['customer_number'] }}
            </td>
            <td>
                {{ $row['customer_name'] }}
            </td>
            <td>
                {{ $row['total_value'] }}
            </td>
            <td>
                {{ $row['total_igv'] }}
            </td>
            <td>
                {{ $row['total_exonerated'] }}
            </td>
            <td>
                {{ $row['total'] }}
            </td>
            <td>
                {{ $row['voided_description'] }}
            </td>
            <td>
                {{ $row['item_description'] }}
            </td>
            <td>
                {{ $row['quantity'] }}
            </td>
            <td>
                {{ $row['unit_price'] }}
            </td>

        </tr>
    @endforeach
</table>
