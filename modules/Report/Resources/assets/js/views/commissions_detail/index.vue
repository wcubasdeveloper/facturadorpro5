<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Utilidades detallado
                <el-tooltip class="item" effect="dark" content="Total ventas (CPE - NV) - Total compras" placement="top-end">
                    <i class="fa fa-info-circle"></i>
                </el-tooltip>
            </h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th>#</th>
                            <th>Fecha</th>
                            <th class="text-center">Comprobante</th>
                            <th class="text-center">Serie</th>
                            <th class="text-center">Ruc/Dni</th>

                            <th class="text-center">Comercial</th>
                            <th class="text-center">Detalle</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Precio compra</th>
                            <th class="text-center">Precio venta</th>

                            <th class="text-center">Ganancia unidad</th>
                            <th class="text-center">Ganancia total</th>
                        </tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>  
                            <td>{{row.date_of_issue}}</td>
                            <td class="text-center">{{row.type_document}}</td>
                            <td class="text-center">{{row.serie}}</td> 
                            <td class="text-center">{{row.customer_number}}</td> 

                            <td class="text-center">{{row.customer_name}}</td> 
                            <td class="text-center">{{row.name}}</td> 
                            <td class="text-center">{{row.quantity}}</td> 
                            <td class="text-center">{{row.purchase_unit_price}}</td> 
                            <td class="text-center">{{row.unit_price}}</td> 

                            <td class="text-center">{{row.unit_gain}}</td> 
                            <td class="text-center">{{row.overall_profit}}</td> 

                        </tr>
                        
                    </data-table>
                     
                    
                </div> 
        </div>
 
    </div>
</template>

<script>
 
    import DataTable from '../../components/DataTableReportsComissionDetail.vue'

    export default { 
        components: {DataTable},
        data() {
            return {
                resource: 'reports/commissions-detail',                 
                form: {}, 

            }
        },
        async created() { 
        },
        methods: { 
             
            
        }
    }
</script>