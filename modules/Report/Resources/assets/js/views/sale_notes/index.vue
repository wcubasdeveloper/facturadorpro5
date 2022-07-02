<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info p-b-30">
            <h3 class="my-0">Consulta de Notas de venta</h3>

            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columns" :key="index">
                            <el-checkbox v-model="column.visible">{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
        </div>



        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource" :applyCustomer="true">
                        <tr slot="heading">
                            <th>#</th>
                            <th class="text-center">Fecha Emisión</th>
                            <th class="text-center">Hora Emisión</th>
                            <th class="">Usuario/Vendedor</th>
                            <th>Cliente</th>
                            <th>Nota de Venta</th>
                            <th class="text-center">Estado pago</th>
                            <th>Estado</th>
                            <th class="text-center">Moneda</th>
                            <th class="text-center" v-if="columns.web_platforms.visible">Plataforma</th>
                            <th>Orden de compra</th>
                            <th class="text-center">Comprobantes</th>
                            <th>Cotización</th>
                            <th>Caso</th>
                            <th class="text-right" >T.Exportación</th>
                            <th class="text-right" >T.Inafecta</th>
                            <th class="text-right" >T.Exonerado</th>

                            <th class="text-right">T.Gravado</th>
                            <th class="text-right">T.Igv</th>
                            <th class="text-right">Total</th>
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>
                            <td>{{row.date_of_issue}}</td>
                            <td>{{row.time_of_issue}}</td>
                            <td>{{row.user_name}}</td>
                            <td>{{row.customer_name}}</td>
                            <td>{{row.number_full}}</td>
                            <td class="text-center">
                                <span class="badge text-white" :class="{'bg-success': (row.total_canceled), 'bg-warning': (!row.total_canceled)}">{{row.total_canceled ? 'Pagado':'Pendiente'}}</span>
                            </td>
                            <td>{{row.state_type_description}}</td>
                            <td>{{row.currency_type_id}}</td>
                        <td  v-if="columns.web_platforms.visible">
                            <template v-for="(platform,i) in row.web_platforms" v-if="row.web_platforms !== undefined">
                                <label class="d-block"  :key="i">{{platform.name}}</label>
                            </template>
                        </td>
                        <td>{{ row.purchase_order }}</td>
                        <td>
                                <template v-for="(doc,i) in row.documents">
                                    <label class="d-block"  :key="i">{{doc.number_full}}</label>
                                </template>
                            </td>
                        <td>{{row.quotation_number_full}}</td>
                            <td>{{row.sale_opportunity_number_full}}</td>


                            <td >{{ (row.state_type_id == '11') ? "0.00" : row.total_exportation }}</td>
                            <td >{{ (row.state_type_id == '11') ? "0.00" : row.total_unaffected }}</td>
                            <td >{{ (row.state_type_id == '11') ? "0.00" : row.total_exonerated }}</td>

                            <td>{{ (row.state_type_id == '11') ? "0.00" : row.total_taxed}}</td>
                            <td>{{ (row.state_type_id == '11') ? "0.00" : row.total_igv}}</td>
                            <td>{{ (row.state_type_id == '11') ? "0.00" : row.total}}</td>

                        </tr>

                    </data-table>


                </div>
        </div>

    </div>
</template>

<script>

    import DataTable from '../../components/DataTableReports.vue'

    export default {
        components: {DataTable},
        data() {
            return {
                resource: 'reports/sale-notes',
                form: {},
                columns: {
                    web_platforms: {
                        title: 'Plataformas web',
                        visible: false
                    },
                }

            }
        },
        async created() {
        },
        methods: {


        }
    }
</script>
