<template>
    <el-dialog :title="titleDialog" :visible="showDialog"   @close="close" @open="create"   append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12"> 

                        <el-tabs v-model="activeName">
                            <el-tab-pane label="Ver stock" name="first">
                                <table class="table table-hover mt-2">
                                    <thead>
                                        <tr>
                                            <th>Ubicación</th>
                                            <th class="text-right">Stock</th>
                                            <th class="text-right">Series</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in data.warehouses" :key="index">
                                            <td>{{ row.warehouse_description }}</td>
                                            <td class="text-right">{{ row.stock }}</td>
                                            <td class="text-right">
                                                
                                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" 
                                                    @click.prevent="clickSearchItemLots(row)" v-if="row.series_enabled"
                                                >
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </el-tab-pane>

                            <el-tab-pane label="Últimas ventas" name="second">
                                 
                                <div class="table-responsive" v-loading="sales_loading">
                                    <table class="table">
                                        <thead> 
                                            <tr slot="heading">
                                                <th>#</th>
                                                    <th class="text-center">Fecha</th>
                                                    <th class="text-center">Documento</th>
                                                    <th class="text-left">Cliente</th>
                                                    <th class="text-center">Cantidad</th>   
                                                    <th class="text-center">Precio</th>   
                                                    <th class="text-center">Total</th>   
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, index) in sales_records" :key="index">
                                                <td>{{ salesCustomIndex(index) }}</td>
                                                    <td class="text-center">{{ row.date_of_issue }}</td> 
                                                    <td  class="text-center">{{ row.number_full }}</td>
                                                    <td class="text-left">{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                                                    <td class="text-center">{{ row.quantity }} </td>    
                                                    <td class="text-center">{{ row.price }} </td>    
                                                    <td class="text-center">{{ row.total }} </td>    
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div>
                                        <el-pagination
                                                @current-change="getSalesRecords"
                                                layout="total, prev, pager, next"
                                                :total="sales_pagination.total"
                                                :current-page.sync="sales_pagination.current_page"
                                                :page-size="sales_pagination.per_page">
                                        </el-pagination>
                                    </div>
                                </div>  

                            </el-tab-pane>

 

                            <el-tab-pane label="Últimas compras" name="third">
                                
                                
                                <div class="table-responsive" v-loading="purchases_loading">
                                    <table class="table">
                                        <thead> 
                                            <tr slot="heading">
                                                <th>#</th>
                                                    <th class="text-center">Fecha</th>
                                                    <th class="text-center">Documento</th>
                                                    <th class="text-left">Proveedor</th>
                                                    <th class="text-center">Cantidad</th>   
                                                    <th class="text-center">Precio</th>   
                                                    <th class="text-center">Total</th>   
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, index) in purchases_records" :key="index">
                                                <td>{{ purchasesCustomIndex(index) }}</td>
                                                    <td class="text-center">{{ row.date_of_issue }}</td> 
                                                    <td  class="text-center">{{ row.number_full }}</td>
                                                    <td class="text-left">{{ row.supplier_name }}<br/><small v-text="row.supplier_number"></small></td>
                                                    <td class="text-center">{{ row.quantity }} </td>    
                                                    <td class="text-center">{{ row.price }} </td>    
                                                    <td class="text-center">{{ row.total }} </td>    
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div>
                                        <el-pagination
                                                @current-change="getPurchasesRecords"
                                                layout="total, prev, pager, next"
                                                :total="purchases_pagination.total"
                                                :current-page.sync="purchases_pagination.current_page"
                                                :page-size="purchases_pagination.per_page">
                                        </el-pagination>
                                    </div>
                                </div>  

                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </div>
                

            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
            </div>
        </form>
        
        <item-lots-index
            :showDialog.sync="showDialogItemLots"
            :selecteWarehouse="selecteWarehouse"
        >
        </item-lots-index>
    </el-dialog>
</template>

<script>

    import ItemLotsIndex from "./item_lots/index.vue";
    import queryString from 'query-string'

    export default {
        props:['showDialog', 'recordId'],
        components: {
            ItemLotsIndex,
        },
        data() {
            return {
                showImportDialog: false,
                showDialogItemLots: false,
                resource: 'items',
                activeName: "first",
                titleDialog: 'Historial',
                data: {
                    warehouses: []
                },
                selecteWarehouse: {},
                form_history: null,
                sales_records:[],
                sales_loading:false,
                sales_pagination: {},
                purchases_records:[],
                purchases_loading:false,
                purchases_pagination: {}
            }
        },
        created() {
        }, 
        async mounted () { 
            // console.log(this.form)
            // await this.getSalesRecords()

        },
        methods: {
            clickSearchItemLots(row){
                this.selecteWarehouse = row
                this.showDialogItemLots = true
            },
            async create() { 
 
                await this.$http.get(`/${this.resource}/data-history/${this.recordId}`)
                    .then(response => {
                        // console.log(response)
                        this.data = response.data
                        // this.form = response.data.data 
                    })

                await this.initForm()
                await this.getSalesRecords()
                await this.getPurchasesRecords()

            },
            initForm(){
                this.form_history = {
                    item_id : this.recordId
                }
            },
            close() {
                this.$emit('update:showDialog', false)
            },
            salesCustomIndex(index) {
                return (this.sales_pagination.per_page * (this.sales_pagination.current_page - 1)) + index + 1
            },
            getSalesRecords() {
                this.sales_loading = true
                return this.$http.get(`/${this.resource}/history-sales/records?${this.getSalesQueryParameters()}`).then((response) => {
                    this.sales_records = response.data.data
                    this.sales_pagination = response.data.meta
                    this.sales_pagination.per_page = parseInt(response.data.meta.per_page)
                }).then(()=>{
                    this.sales_loading = false
                });
            },
            purchasesCustomIndex(index) {
                return (this.sales_pagination.per_page * (this.sales_pagination.current_page - 1)) + index + 1
            },
            getPurchasesRecords() {
                this.purchases_loading = true
                return this.$http.get(`/${this.resource}/history-purchases/records?${this.getPurchasesQueryParameters()}`).then((response) => {
                    this.purchases_records = response.data.data
                    this.purchases_pagination = response.data.meta
                    this.purchases_pagination.per_page = parseInt(response.data.meta.per_page)
                }).then(()=>{
                    this.purchases_loading = false
                });
            },
            getSalesQueryParameters() {
                return queryString.stringify({
                    page: this.sales_pagination.current_page,
                    form: JSON.stringify(this.form_history),
                    limit: this.limit
                })
            } ,
            getPurchasesQueryParameters() {
                return queryString.stringify({
                    page: this.purchases_pagination.current_page,
                    form: JSON.stringify(this.form_history),
                    limit: this.limit
                })
            } 
        }
    }
</script>
