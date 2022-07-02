<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <div class="right-wrapper pull-right">
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">{{ title }}</h3>
            </div>
            <div class="card-body">

                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th class="text-center">Unidad</th>
                        <th class="text-center">Unidades físicas vendidas</th>
                        <th class="text-center">Costo unitario</th>
                        <th class="text-center">Valor de ventas</th>
                        <th class="text-center">Costo de producto</th>
                        <th class="text-center">Unidad valorizada</th>
                        <th>Stock</th>
                        <th class="text-center">Exportar</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.item_description }}</td>
                        <td>{{ row.category_description }}</td>
                        <td>{{ row.brand_description }}</td>
                        <td class="text-center">{{ row.unit_type_id }}</td>
                        <td class="text-center">{{ row.quantity_sale }}</td>
                        <td class="text-center">{{ row.purchase_unit_price }}</td>
                        <td class="text-center">{{ row.total_sales }}</td>
                        <td class="text-center">{{ row.item_cost }}</td>
                        <td class="text-center">{{ row.valued_unit }}</td>
                        <td>
                            <el-popover
                                placement="right"
                                :style="{ width: 360 }"
                                trigger="click">
                                <el-table :data="row.warehouses">
                                    <el-table-column :style="{ width: 220 }" property="warehouse_description"
                                                     label="Almacén"></el-table-column>
                                    <el-table-column :style="{ width: 90 }" property="stock"
                                                     label="Stock"></el-table-column>
                                    <el-table-column :style="{ width: 30 }" property="sale_unit_price"
                                                     label="Precio"></el-table-column>
                                </el-table>
                                <el-button slot="reference"><i class="fa fa-eye"></i></el-button>
                            </el-popover>

                        </td>
                        <td class="text-center">

                            <el-tooltip class="item"
                                        content="Exportar Formato SUNAT 13.1"
                                        effect="dark"
                                        placement="top">

                                <el-button type="success" @click.prevent="clickDownloadFormatSunat(row.id)" size="small"><i
                                    class="fa fa-file-excel"></i> 
                                </el-button>
                            </el-tooltip>
                        </td>

                    </tr>
                </data-table>

            </div>


        </div>
    </div>
</template>

<script>


import DataTable from '../../components/DataTableValuedKardex.vue'

export default {
    components: {DataTable},
    data() {
        return {
            title: null,
            resource: 'reports/valued-kardex',
        }
    },
    created() {
        this.title = 'Kardex valorizado'
    },
    methods: {
        clickDownloadFormatSunat(item_id) {        
            this.$eventHub.$emit('exportFormatSunat', item_id)
        },
    }
}
</script>
