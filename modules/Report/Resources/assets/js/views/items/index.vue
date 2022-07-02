<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Consulta de documentos por producto</h3>
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
                            <th class="" v-if="defaultType!== 'purchase'">Plataforma</th>
                            <th class="">Cantidad</th>
                            <th class="">Monto</th>
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>
                            <td>{{row.date_of_issue}}</td>
                            <td>{{row.document_type_description}}</td>
                            <td>{{row.series}}</td>
                            <td>{{row.alone_number}}</td>
                            <td>{{row.customer_number}}</td>
                            <td>{{row.customer_name}}</td>
                            <td v-if="defaultType!== 'purchase'">{{row.web_platform_name}}</td>
                            <td>{{row.quantity}}</td>
                            <td>{{ (row.document_type_id == '07') ? ( (row.total == 0) ? '0.00': '-'+row.total) : ((row.document_type_id!='07' && (row.state_type_id =='11'||row.state_type_id =='09')) ? '0.00':row.total) }}</td>
                        </tr>

                    </data-table>


                </div>
        </div>

    </div>
</template>

<script>

    import DataTable from '../../components/DataTableItems.vue'

    export default {
        components: {DataTable},
        props:[
            'defaultType',
            'configuration',
        ],
        data() {
            return {
                resource: 'reports/items',
                form: {},
                config:{},
                title: 'Consulta de documentos por producto',
            }
        },
        async created() {

            if(this.defaultType === 'purchase'){
                this.title = 'Compra - ' + this.title;
            }
            if(this.configuration !== undefined && this.configuration !== null && this.configuration.length > 0){
                this.$setStorage('configuration',this.configuration)
            }
            this.config = this.$getStorage('configuration');
        },
        methods: {


        }
    }
</script>
