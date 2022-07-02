function calculateRowItem(row_old, currency_type_id_new, exchange_rate_sale) {
    // console.log(currency_type_id_new, exchange_rate_sale)

    let currency_type_id_old = row_old.item.currency_type_id
    let unit_price = parseFloat(row_old.item.unit_price)
    // } else {
    //     unit_price = parseFloat(row_old.item.unit_price) * 1.18
    // }
    let warehouse_id = row_old.warehouse_id

    // console.log(row_old)

    if (currency_type_id_old === 'PEN' && currency_type_id_old !== currency_type_id_new) {
        unit_price = unit_price / exchange_rate_sale;
    }

    if (currency_type_id_new === 'PEN' && currency_type_id_old !== currency_type_id_new) {
        unit_price = unit_price * exchange_rate_sale;
    }

    // unit_price = _.round(unit_price, 4);


    // fixed for update sale_note
    let record_id = (row_old.record_id) ? row_old.record_id : (row_old.id ? row_old.id : null)

    let has_isc = row_old.has_isc

    // console.log(row_old)

    let row = {
        item_id: row_old.item.id,
        // item_description: row_old.item.description,
        item: row_old.item,
        currency_type_id: currency_type_id_new,
        quantity: row_old.quantity,
        unit_value: 0,
        affectation_igv_type_id: row_old.affectation_igv_type_id,
        affectation_igv_type: row_old.affectation_igv_type,
        total_base_igv: 0,
        percentage_igv: 18,
        total_igv: 0,
        system_isc_type_id: has_isc ? row_old.system_isc_type_id : null,
        // system_isc_type_id: null,
        total_base_isc: 0,
        percentage_isc: has_isc ? parseFloat(row_old.percentage_isc) : 0,
        // percentage_isc: 0,
        total_isc: 0,
        total_base_other_taxes: 0,
        percentage_other_taxes: 0,
        total_other_taxes: 0,
        total_plastic_bag_taxes: 0,
        total_taxes: 0,
        price_type_id: '01',
        unit_price: unit_price,
        input_unit_price_value: row_old.input_unit_price_value,
        total_value: 0,
        total_discount: 0,
        total_charge: 0,
        total: 0,
        attributes: row_old.attributes,
        charges: row_old.charges,
        discounts: row_old.discounts,
        warehouse_id: warehouse_id,
        name_product_pdf: row_old.name_product_pdf,
        record_id: record_id, // fixed for update sale_note

        //valores sin redondeo, se usa en los calculos (invoice) para mayor precision
        total_value_without_rounding: 0,
        total_base_igv_without_rounding: 0,
        total_igv_without_rounding: 0,
        total_taxes_without_rounding: 0,
        total_without_rounding: 0,
    };

    // console.log(row)

    let percentage_igv = 18
    let unit_value = row.unit_price

    if (row.affectation_igv_type_id === '10') {
        unit_value = row.unit_price / (1 + percentage_igv / 100)
    }

    // row.unit_value = _.round(unit_value, 4)

    row.unit_value = unit_value

    let total_value_partial = unit_value * row.quantity


    /* Discounts */
    let discount_base = 0
    let discount_no_base = 0
    // row.discounts.forEach((discount, index) => {
    //     discount.percentage = parseFloat(discount.percentage)
    //     discount.factor = discount.percentage / 100
    //     discount.base = _.round(total_value_partial, 2)
    //     discount.amount = _.round(discount.base * discount.factor, 2)
    //     if (discount.discount_type.base) {
    //         discount_base += discount.amount
    //     } else {
    //         discount_no_base += discount.amount
    //     }
    //     row.discounts.splice(index, discount)
    // })
    if (row.discounts.length > 0) {
        row.discounts.forEach((discount, index) => {

            let affectation_igv_type_exonerated = ['20', '21', '30', '31', '32', '33', '34', '35', '36', '37']

            if (discount.is_amount) {
                if (discount.discount_type.base) {

                    discount.base = _.round(total_value_partial, 2)
                    //amount and percentage are equals in input
                    discount.amount = _.round(discount.percentage, 2)

                    discount.percentage = _.round(100 * (parseFloat(discount.amount) / parseFloat(discount.base)), 5)
                    // discount.percentage =  _.round(100 * (parseFloat(discount.amount) / parseFloat(discount.base)),2)

                    discount.factor = _.round(discount.percentage / 100, 5)
                    // discount.factor = _.round(discount.percentage / 100, 2)

                    discount_base += discount.amount

                } else {

                    let aux_total_line = row.unit_price * row.quantity

                    // if (!affectation_igv_type_exonerated.includes(row.affectation_igv_type_id)) {
                    //     total_value_partial = (aux_total_line - discount.percentage) / (1 + percentage_igv / 100)
                    // } else {
                    //     total_value_partial = aux_total_line - discount.percentage
                    // }

                    discount.base = _.round(aux_total_line, 2)
                    //amount and percentage are equals in input
                    discount.amount = _.round(discount.percentage, 2)
                    discount.percentage = _.round(100 * (parseFloat(discount.amount) / parseFloat(discount.base)), 2)
                    discount.factor = _.round(discount.percentage / 100, 5)
                    // discount.factor = _.round(discount.percentage / 100, 2)
                    // discount_no_base += discount.amount
                }

            } else {

                if (discount.discount_type.base) {

                    discount.percentage = parseFloat(discount.percentage)
                    discount.factor = discount.percentage / 100
                    discount.base = _.round(total_value_partial, 2)
                    discount.amount = _.round(discount.base * discount.factor, 2)
                    // if (discount.discount_type.base) {
                    discount_base += discount.amount
                    // } else {
                    //     discount_no_base += discount.amount
                    // }

                } else {


                    let aux_total_line = row.unit_price * row.quantity
                    discount.factor = _.round(discount.percentage / 100, 5)
                    discount.amount = _.round(aux_total_line * discount.factor, 2)

                    // if (!affectation_igv_type_exonerated.includes(row.affectation_igv_type_id)) {
                    //     total_value_partial = (aux_total_line - discount.amount) / (1 + percentage_igv / 100)
                    // } else {
                    //     total_value_partial = aux_total_line - discount.amount
                    // }

                    discount.base = _.round(aux_total_line, 2)

                }

            }

            row.discounts.splice(index, discount)
        })
    }
    // console.log('total base discount:'+discount_base)
    // console.log('total no base discount:'+discount_no_base)


    /* Charges */
    let charge_base = 0
    let charge_no_base = 0
    if (row.charges.length > 0) {
        row.charges.forEach((charge, index) => {
            charge.percentage = parseFloat(charge.percentage)
            charge.factor = charge.percentage / 100
            charge.base = _.round(total_value_partial, 2)
            charge.amount = _.round(charge.base * charge.factor, 2)
            if (charge.charge_type.base) {
                charge_base += charge.amount
            } else {
                charge_no_base += charge.amount
            }
            row.charges.splice(index, charge)
        })
    }
    // console.log('total base charge:'+charge_base)
    // console.log('total no base charge:'+charge_no_base)

    let total_isc = 0
    let total_other_taxes = 0

    let total_discount = discount_base + discount_no_base
    let total_charge = charge_base + charge_no_base
    let total_value = total_value_partial - total_discount + total_charge
    let total_base_igv = total_value_partial - discount_base + total_isc

    // console.log(total_base_igv, total_value)

    let total_igv = 0

    if (row.affectation_igv_type_id === '10') {
        total_igv = total_base_igv * percentage_igv / 100
    }
    if (row.affectation_igv_type_id === '20') { //Exonerated
        total_igv = 0
    }
    if (row.affectation_igv_type_id === '30') { //Unaffected
        total_igv = 0
    }


    //impuesto bolsa - icbper
    
    let total_plastic_bag_taxes = 0

    if (row_old.has_plastic_bag_taxes) {

        total_plastic_bag_taxes = _.round(row.quantity * row.item.amount_plastic_bag_taxes, 1)
        row.total_plastic_bag_taxes = total_plastic_bag_taxes

    }

    // icbper


    // let total_taxes = total_igv + total_isc + total_other_taxes
    let total_taxes = total_igv + total_isc + total_other_taxes + total_plastic_bag_taxes

    let total = total_value + total_taxes

    row.total_charge = _.round(total_charge, 2)
    row.total_discount = _.round(total_discount, 2)
    row.total_charge = _.round(total_charge, 2)
    row.total_value = _.round(total_value, 2)
    row.total_base_igv = _.round(total_base_igv, 2)
    row.total_igv = _.round(total_igv, 2)
    row.total_taxes = _.round(total_taxes, 2)
    row.total = _.round(total, 2)

    
    //procedimiento para agregar isc
    if(has_isc){

        row.total_base_isc = _.round(total_value, 2) //total valor antes de aplicar isc
        row.total_isc = _.round(total_value * (row.percentage_isc / 100), 2)
        // row.total_isc = _.round(row.total_base_isc * (row.percentage_isc / 100), 2)

        //calcular nueva base incrementando el valor actual + isc
        total_base_igv += row.total_isc  
        row.total_base_igv = _.round(total_base_igv, 2)  
        
        total_igv = total_base_igv * (percentage_igv / 100)
        row.total_igv = _.round(total_igv, 2)

        //asignar nuevo total impuestos, si tiene descuentos se usa total_taxes para calcular el precio unitario
        total_taxes = total_igv + row.total_isc + total_plastic_bag_taxes
        // total_taxes = total_igv + row.total_isc 
        row.total_taxes = _.round(total_taxes, 2)

        total = total_value + total_taxes
        row.total = _.round(total, 2)

        //calcular nuevo precio unitario
        row.unit_price = _.round(total / row.quantity, 6)


        // // console.log("apply isc")
        // row.total_base_isc = total_value //total valor antes de aplicar isc
        // // row.total_base_isc = total_value_partial //total valor antes de aplicar isc
        // row.total_isc = _.round(row.total_base_isc * (row.percentage_isc / 100), 2)
        // row.total_base_igv += row.total_isc  //calcular nueva base incrementando el valor actual + isc
        // row.total_igv = row.total_base_igv * (percentage_igv / 100)

        // //asignar nuevo total impuestos, si tiene descuentos se usa total_taxes para calcular el precio unitario
        // total_taxes = row.total_igv + row.total_isc 
        // row.total_taxes = total_taxes

        // row.total = row.total_value + row.total_taxes
        
        // //calcular nuevo precio unitario
        // row.unit_price = _.round(row.total / row.quantity, 6)

    }
    //procedimiento para agregar isc


    // descuentos, se modifica precio unitario y total descuentos
    if (row.discounts.length > 0) {

        let sum_discount_no_base = 0
        let sum_discount_base = 0

        row.discounts.forEach(discount => {
            sum_discount_no_base += (discount.discount_type_id == '01') ? discount.amount : 0
            sum_discount_base += (discount.discount_type_id == '00') ? discount.amount : 0
        })

        //obs 4287
        // monto dscto que no afecta a la base segun fila 180, hoja factura2_0 excel validaciones (20210902)
        row.unit_price = (total_value + total_taxes - sum_discount_no_base) / row.quantity

        //obs 4288
        // let exist_discount_no_base = _.find(row.discounts, {discount_type_id: '01'})
        // if (exist_discount_no_base) {
        //     row.unit_value = (total_value + total_taxes) / row.quantity
        //     if (row.affectation_igv_type_id === '10') {
        //         row.unit_value = row.unit_value / (1 + percentage_igv / 100)
        //     }
        // }

        let total_discounts = sum_discount_no_base + sum_discount_base;
        row.total_discount = _.round(total_discounts, 2)
    }
    // descuentos


    //valores sin redondeo, se usa en los calculos para mayor precision (método calculateTotal - Invoice)
    row.total_value_without_rounding = total_value
    row.total_base_igv_without_rounding = total_base_igv
    row.total_igv_without_rounding = total_igv
    row.total_taxes_without_rounding = total_taxes
    row.total_without_rounding = total
    

    if (row.affectation_igv_type.free) {
        row.price_type_id = '02'
        row.unit_value = 0
        // row.total_value = 0
        // row.total = 0
        row.total = 0 + total_plastic_bag_taxes

        //valor sin redondeo
        row.total_without_rounding = 0
    }

    //impuesto bolsa
    // if (row_old.has_plastic_bag_taxes) {
    //     row.total_plastic_bag_taxes = total_plastic_bag_taxes
    // }

    // console.log(row)
    return row
}

function getUniqueArray(arr, keyProps) {
    if (arr == null) return null
    return Object.values(
        arr.reduce((uniqueMap, entry) => {
            const key = keyProps.map((k) => entry[k]).join('|')
            if (!(key in uniqueMap)) uniqueMap[key] = entry
            return uniqueMap
        }, {})
    )
}

function showNamePdfOfDescription(item, show_pdf_name) {
    if (show_pdf_name !== undefined &&
        show_pdf_name === true &&
        item !== undefined &&
        item.name_product_pdf !== undefined &&
        item.name_product_pdf !== null
    ) {
        let temn = item.name_product_pdf;
        temn = temn.substring(3)
        temn = temn.slice(0, -4)
        if (temn.length > 0) {
            return temn;
        }
    }
    return item.description
}

function sumAmountDiscountsNoBaseByItem(row) {

    let sum_discount_no_base = 0

    if (row.discounts) {
        // if(row.discounts.length > 0){
        sum_discount_no_base = _.sumBy(row.discounts, function (discount) {
            return (discount.discount_type_id == '01') ? discount.amount : 0
        })
        // }
    }

    return sum_discount_no_base
}

function FormatUnitPriceRow(unit_price){
    return _.round(unit_price, 6)
    // return unit_price.toFixed(6)
}

export {calculateRowItem, getUniqueArray, showNamePdfOfDescription, sumAmountDiscountsNoBaseByItem, FormatUnitPriceRow}
