<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>REPORTE GENERAL PEDIDOS</title>
    </head>
    <body>
        <div>
            <p align="center" class="title"><strong>REPORTE GENERAL PEDIDOS</strong></p>
        </div>
        <br>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">Número</th>
                                <th  class="celda">Cliente</th>
                                <th  class="celda">Dirección</th>
                                <th  class="celda">Total</th>
                                <th  class="celda" >Estado</th>
                                <th  class="celda">Descuento</th>
                                <th  class="celda" >Total venta</th>
                                <th  class="celda">Tipo de pago</th>
                                <th  class="celda">Vendedor</th>
                                <th  class="celda" >Motivo</th>
                                <th  class="celda">Detalle</th>
                                <th  class="celda">Codigo interno</th>
                                <th  class="celda">Nombre de producto</th>
                                <th  class="celda">Categoria</th>
                                <th  class="celda">Marca</th>
                                <th  class="celda">Cantidad de producto</th>
                                <th  class="celda">Precio unitario</th>
                                
                                <th  class="celda">Documento Asociado</th>
                                <th  class="celda">Guia de remision</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                
                                
                                $total_prev=0;
                                

                                $acum_total_taxed=0;
                                $acum_total_igv=0;
                                $acum_total=0;
                            
                                $serie_affec = '';
                                $acum_total_exonerado=0;
                                $acum_total_inafecto=0;

                                $acum_total_free=0;
                            @endphp
                            @foreach($records as $key => $value)
                            @php
                                $serie_document=0;
                                $discount_description=0;
                                $guide_remision=0;
                                $brand_items=0;
                                $category_items=0;
                                $name_items=0;
                                $obs=0;
                                $data = $value->getCollectionData();
                                /* dd($value); */
                                $items_order=0;
                                $identifier_order=0;
                                $guide_remision=0;
                                $acum_price=0;
                                $acum_discount=0;

                                $acum_total = $value->total_value;
                                $acum_total_taxed = $value->total_taxed;
                                $acum_total_igv = $value->total_igv;

                                
                                $acum_total_exonerado = $value->total_exonerated;
                                $acum_total_inafecto = $value->total_unaffected;
                                $acum_total_free = $value->total_free;

                                $total_prev = $acum_total+$acum_total_igv+$acum_total_exonerado+$acum_total_free+$acum_total_inafecto;

                                $state_type_description=$data['state_type_description'];
                                $guide_remision=empty($data['dispatches'][0])?0:$data['dispatches'][0]['number'];
                                $identifier_order=$data['identifier'];
                                /* dd(empty($data['sale_notes'][0])); */
                                $documents_order_id = empty($data['documents'][0]) ? 0 : $data['documents'][0]['series'].'-'.$data['documents'][0]['id'];
                                $sale_order_id= empty($data['sale_notes'][0]) ? 0 : $data['sale_notes'][0]['series'].'-'.$data['sale_notes'][0]['id'];
                                /* dd($guide_remision); */
                                $items_brand='';
                                $category_string='';
                                $discount_string='';
                                $name_string='';
                                $serie_document=(!$documents_order_id) ? $sale_order_id : $documents_order_id;
                                foreach ($value->items as $itm) {
                                    $discount=(array)$itm['discounts'];
                                    $items_order+=$itm['quantity'];
                                    $item=(array)$itm['item'];
                                    if (!empty($item['brand'])) {
                                        $items_brand= str_contains($brand_items,$item['brand']) ? $item['brand'].' - ' : $items_brand.=$item['brand'].' - ';
                                        $brand_items = trim($items_brand,' - ');
                                    }

                                    if (!empty($item['category'])) {
                                        $category_string= str_contains($category_items,$item['category']) ? $item['category'].' - ' : $category_string.=$item['category'].' - ';
                                        $category_items = trim($category_string,' - ');
                                    }

                                    $acum_price += ($itm['unit_price']*$itm['quantity']);
                                    $acum_discount += $itm['total_discount'];
                                    if (!empty($discount)) {
                                        $discount_format=(array) $discount[0];
                                        $discount_string = str_contains($discount_description, $discount_format['description']) ? $discount_format['description'] : $discount_string.=$discount_format['description'].' - ';
                                        $discount_description = trim($discount_string,' - ');
                                    }
                                    /* dd(empty($itm['item_details'][0])); */
                                    if(!empty($itm['item_details'][0]['name'])&& !is_null($itm['item_details'][0]['name'])){
                                        $name_string = str_contains($name_items, $itm['item_details'][0]['name']) ? $itm['item_details'][0]['name'].' - ' : $name_string.=$itm['item_details'][0]['name'].' - ';
                                    
                                        $name_items=trim($name_string,' - ');
                                    }

                                }
                                $obs=$value->observation;
                            @endphp
                                <tr>
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda" >{{ $value->customer->name }}</td>
                                    <td  class="celda">{{$value->shipping_address}}</td>
                                    <td  class="celda" >{{$total_prev}}</td>
                                    <td  class="celda">{{$state_type_description}}</td>
                                    <td  class="celda">{{number_format($acum_discount,2)}}</td>
                                    <td class="celda" >{{ $value->total }}</td>
                                    <td class="celda" >{{ $value->payment_method_type->description }}</td>
                                    <td class="celda" >{{ $value->user->name }}</td>
                                    <td  class="celda">{{$discount_description}}</td>
                                    <td  class="celda">{{$obs}}</td>
                                    <td  class="celda">{{$identifier_order}}</td>
                                    <td  class="celda">{{$name_items}}</td>
                                    <td  class="celda">{{$category_items}}</td>
                                    <td  class="celda">{{$brand_items}}</td>
                                    <td  class="celda">{{$items_order}}</td>
                                    <td  class="celda">{{number_format($acum_price,2)}}</td>
                                    
                                    <td  class="celda">{{$serie_document}}</td>
                                    <td  class="celda">{{$guide_remision}}</td>
                                    
                                </tr>
 
                            @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="callout callout-info">
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
