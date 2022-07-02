<template>
    <el-dialog :title="titleDialog"   :visible="showDialog"  @open="create" append-to-body :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
        <div class="form-body">
            <div class="row" >
                <div class="col-lg-12">
                
                    <div class="table-responsive" v-loading="loading">
                        <table class="table">
                            <thead> 
                                <tr slot="heading">
                                    <th>#</th>
                                    <th class="text-center">Serie</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Estado</th>  
                                    <th class="text-center">Vendido</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in records" :key="index">
                                    <td>{{ customIndex(index) }}</td>
                                    <td  class="text-center">{{ row.series }}</td>
                                    <td class="text-center">{{ row.date }}</td> 
                                    <td class="text-center">{{ row.state }} </td>  
                                    <td class="text-center">{{ row.status }} </td>  
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <el-pagination
                                    @current-change="getRecords"
                                    layout="total, prev, pager, next"
                                    :total="pagination.total"
                                    :current-page.sync="pagination.current_page"
                                    :page-size="pagination.per_page">
                            </el-pagination>
                        </div>
                    </div>  

                </div>
                
            </div>
        </div>
        
        <div class="form-actions text-right pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
        </div>
    </el-dialog>
</template> 

<script>
    import queryString from 'query-string'

    export default {
        props: ['showDialog', 'selecteWarehouse'],
        data() {
            return {
                titleDialog: 'Series disponibles',
                loading: false,
                resource: 'items/available-series',
                form:{},
                loading:false,
                columns: [],
                records: [],
                pagination: {}
            }
        },
        async created() {
             
        },
        async mounted () { 

        },
        methods: {
            initForm(){
                this.form = {
                    item_id : this.selecteWarehouse.item_id,
                    warehouse_id : this.selecteWarehouse.warehouse_id
                }
            },
            async create(){
                await this.initForm()
                await this.getRecords()                
                await this.$eventHub.$emit('reloadSimpleDataTableParams')
                
            },   
            close() {
                this.$emit('update:showDialog', false)
            }, 
            customIndex(index) {
                return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
            },
            getRecords() {
                this.loading = true
                return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`).then((response) => {
                    this.records = response.data.data
                    this.pagination = response.data.meta
                    this.pagination.per_page = parseInt(response.data.meta.per_page)
                }).then(()=>{
                    this.loading = false
                });
            },
            getQueryParameters() {
                return queryString.stringify({
                    page: this.pagination.current_page,
                    form: JSON.stringify(this.form),
                    limit: this.limit
                })
            } 
        }
    }
</script>
