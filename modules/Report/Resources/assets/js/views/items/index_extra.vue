<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Consulta de documentos por producto
                <el-tooltip
                    class="item"
                    content="Reporte con los campos opcionales del item"
                    effect="dark"
                    placement="top-start">
                    <i class="fa fa-info-circle"></i>
                </el-tooltip>
            </h3>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table
                    :defaultType="defaultType"
                    :resource="resource">
                    <tr slot="heading">
                        <th class="">#</th>
                        <th class="">Fecha</th>
                        <th class="">Tipo Documento</th>
                        <th class="">Serie</th>
                        <th class="">Número</th>
                        <th class="">N° Documento</th>
                        <th class="">Cliente</th>
                        <th v-if="defaultType!== 'purchase'"
                            class="">Plataforma
                        </th>
                        <th class="">Cantidad</th>
                        <th class="">Monto</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.date_of_issue }}</td>
                        <td>{{ row.document_type_description }}</td>
                        <td>{{ row.series }}</td>
                        <td>{{ row.alone_number }}</td>
                        <td>{{ row.customer_number }}</td>
                        <td>{{ row.customer_name }}</td>
                        <td v-if="defaultType!== 'purchase'">{{ row.web_platform_name }}</td>
                        <td>{{ row.quantity }}</td>
                        <td>{{
                                (row.document_type_id == '07') ? ((row.total == 0) ? '0.00' : '-' + row.total) : ((row.document_type_id != '07' && (row.state_type_id == '11' || row.state_type_id == '09')) ? '0.00' : row.total)
                            }}
                        </td>
                    </tr>

                </data-table>


            </div>
        </div>

    </div>
</template>

<script>

import DataTable from './DataTableItemsExtra.vue'
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
// import DataTable from '../../components/DataTableItems.vue'


export default {
    components: {DataTable},
    props: [
        'defaultType',
        'configuration',
    ],
    data() {
        return {
            resource: 'reports/extra-general-items/items',
            form: {},
            title: 'Consulta de documentos por producto',
        }
    },
    computed: {
        ...mapState([
            'config',
        ]),
    },
    async created() {
        this.loadConfiguration();
        this.$store.commit('setConfiguration', this.configuration);
            if (this.defaultType === 'purchase') {
            this.title = 'Compra - ' + this.title;
        }
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),


    }
}
</script>
