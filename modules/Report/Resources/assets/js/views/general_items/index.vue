<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Reporte general de productos</h3>

            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columns" :key="index">
                            <el-checkbox
                                v-model="column.visible"
                                :disabled="column.disable"
                            >{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table
                        :defaultType = "defaultType"
                        :applyConversionToPen="applyConversionToPen"
                        :resource="resource">
                        <tr slot="heading">
                            <th class="">#</th>
                            <th class="">F. Emisión</th>
                            <th class="">Tipo Documento</th>
                            <th class="">Serie</th>
                            <th class="">Número</th>
                            <th class="" v-if="columns.purchase_order.visible"> Orden de compra</th>

                            <th class="">N° Documento</th>
                            <th class="">Cliente</th>

                            <th class="">Cod. Interno</th>
                            <th>Marca</th>
                            <th class="">Descripción</th>
                            <th  v-if="columns.model.visible">Modelo</th>
                            <th  v-if="columns.platform.visible">Plataforma</th>
                            <th class="">U. Medida</th>
                            <th class="">Cantidad</th>
                            <th>Series</th>
                            <th>Plataforma</th>
                            <th class="">Moneda</th>
                            <th class="">Valor unitario</th>
                            <th class="">Total</th>
                            <template v-if="type == 'sale'">
                                <th class="">Total compra</th>
                                <th class="">Ganancia</th>
                            </template>
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>
                            <td>{{row.date_of_issue}}</td>
                            <td>{{row.document_type_description}}</td>
                            <td>{{row.series}}</td>

                        <td>{{row.alone_number}}</td>
                        <td  v-if="columns.purchase_order.visible">{{row.purchase_order}}</td>
                        <td>{{row.customer_number}}</td>
                            <td>{{row.customer_name}}</td>
                            
                            <td>{{row.internal_id}}</td>
                            <td>{{ row.brand }}</td>
                            <td>{{row.description}}</td>
                        <td  v-if="columns.model.visible">{{row.model}}</td>
                        <td  v-if="columns.platform.visible">{{row.platform}}</td>
                        <td>{{row.unit_type_id}}</td>
                            <td>
                                {{row.quantity}}<span v-if="row.factor > 0"> X {{row.factor}}</span>
                            </td>
                            <td>
                                {{ row.lot_has_sale | filterLots }}
                            </td>
                            <td>{{row.web_platform_name}}</td>
                            <td>
                                {{row.currency_type_id}}

                                <template v-if="row.description_apply_conversion_to_pen">
                                    <el-tooltip class="item"
                                                :content="row.description_apply_conversion_to_pen"
                                                effect="dark"
                                                placement="top">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </template>
                            </td>
                            <td>{{row.unit_value}}</td>
                            <td>{{row.total}}</td>
                            <template v-if="type == 'sale'">
                                <td>{{row.total_item_purchase}}</td>
                                <td>{{row.utility_item}}</td>
                            </template>
                        </tr>

                    </data-table>
                </div>
        </div>

    </div>
</template>

<script>
    import DataTable from '../../components/DataTableGeneralItems.vue'

    export default {
        components: {
            DataTable
        },
        props:[
            'defaultType',
            'typeresource',
            'typereport',
            'configuration',
            'applyConversionToPen',
        ],
        computed:{
            PurchaseOrderDisable:function(){
                if(this.type === 'sale'){
                    return  false;
                }
                return true;
            },
        },
        data() {
            return {
                resource: 'reports/general-items',
                form: {},
                type: "sale",
                config:{},
                columns: {
                    model: {
                        title: 'Modelo',
                        visible: false,
                        disable: false,
                    },
                    purchase_order: {
                        title: 'Orden de compra',
                        visible: false,
                        disable: this.PurchaseOrderDisable,
                    },
                    platform: {
                        title: 'Plataforma',
                        visible: false,
                        disable: false,
                    },
                }

            }
        },
        filters:{
            filterLots(data){

                if(data && data.length > 0)
                {
                    const lots_sale = data.filter(x=> (x.has_sale == true||(x.warehouse_id == undefined && x.id == null)) )
                    if(lots_sale)
                    {
                        return lots_sale.map(p=> p.series).join(' - ')
                    }
                    else{
                        return ''
                    }
                }
                else{
                    return ''
                }

            }
        },
        created(){

        },
         mounted() {

            if(this.configuration !== undefined && this.configuration !== null && this.configuration.length > 0){
                this.$setStorage('configuration',this.configuration)
            }
            this.config = this.$getStorage('configuration');

            if(this.typeresource !== undefined && this.typeresource !== null){
                this.resource = this.typeresource;
            }
            if(this.typereport !== undefined && this.typereport !== null){
                this.type = this.typereport;
                this.PurchaseOrderDisable;
            }
            this.$eventHub.$on('typeTransaction', (type) => {
                this.type = type
            })

        },
        methods: {


        }
    }
</script>
