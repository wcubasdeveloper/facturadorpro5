
/**
 * Devuelve una estructura unica para la descripcion del item
 */
function ItemOptionDescription(item) {
    let data = '';
    if (item !== undefined
        && item.full_description !== undefined
    ) {
        data += item.full_description ;
    }
    /*
    if (item !== undefined
        && item.stock !== undefined
    ) {
        data += item.stock;
    }
    */

    return data;
}

/**
 *
 * Devuelve una estructura unica para el tooltip de agregar/editar el item
 *
 */function ItemSlotTooltip(item) {
    let data = '';

    if (item !== undefined
        && item.warehouse_description !== undefined
        && item.warehouse_description !== null
    ) {
        data += "Almacen: " + item.warehouse_description + "<br>";
    }
    if (item !== undefined
        && item.brand !== undefined
        && item.brand !== null
        && item.brand.length > 0
    ) {
        data += "Marca: " + item.brand + "<br>";
    }
    if (item !== undefined
        && item.category !== undefined
        && item.category !== null
        && item.category.length > 0
    ) {
        data += "Categoría: " + item.category + "<br>";
    }
    if (item !== undefined
        && item.stock !== undefined
        && item.stock !== null
    ) {
        data += "Stock: " + item.stock + "<br>";
    }
    if (item !== undefined
        && item.currency_type_symbol !== undefined
        && item.currency_type_symbol !== null
        && item.sale_unit_price !== undefined
        && item.sale_unit_price !== null
    ) {
        data += "Precio: " + item.currency_type_symbol + " " + item.sale_unit_price + "<br>";
    }
    return data;

}

export {
    ItemOptionDescription,
    ItemSlotTooltip,

}
