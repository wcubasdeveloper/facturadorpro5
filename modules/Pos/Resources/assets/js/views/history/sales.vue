<template>
    <el-dialog :title="titleDialog"   :visible="showDialog"  @open="create"  :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false" append-to-body top="7vh">
        <div class="form-body">
            <div class="row" >
                <div class="col-lg-12">
                    <button v-show="type" class="btn btn-danger"
                            type="button"
                            @click.prevent="clickUser()"> Ventas por usuario
                        </button>
                        <button v-show="type" class="btn btn-danger"
                                type="button"
                                @click.prevent="clickAllUser()"> Ventas por usuarios
                        </button>
                
                    <data-table :resource="resource" :form="form">
                        <tr slot="heading">
                            <th>#</th>
                            <th class="text-center">Documento</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Precio</th>
                            <th class="text-center">Cliente</th> 
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>
                            <td  class="text-center">{{ row.number_full }}</td>
                            <td class="text-center">{{ row.date_of_issue }}</td> 
                            <td class="text-center">{{ row.price }} </td>  
                            <td class="text-center">{{ row.name }} </td> 
                        </tr>
                    </data-table>

                </div>
                
            </div>
        </div>
        
        <div class="form-actions text-right pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
        </div>
    </el-dialog>
</template> 

<script>
    import DataTable from '../../components/SimpleDataTableParams.vue'

    export default {
        components: {DataTable},
        props: ['showDialog', 'item_id','customer_id','type'],
        data() {
            return {
                titleDialog: 'Historial de ventas',
                loading: false,
                resource: 'pos/history-sales',
                form:{}
            }
        },
        async created() {
             
        },
        methods: {
            initForm(){
                this.form = {
                    item_id : this.item_id,
                    customer_id : this.customer_id,
                    all_user : false
                }
            },
            async create(){
                await this.initForm()
                await this.$eventHub.$emit('reloadSimpleDataTableParams')
                
            },   
            close() {
                this.$emit('update:showDialog', false)
            }, 
            async clickAllUser(){
                this.form.all_user=true
                await this.$eventHub.$emit('reloadSimpleDataTableParams')
            },
            async clickUser(){
                this.form.all_user=false
                await this.$eventHub.$emit('reloadSimpleDataTableParams')
            }
        }
    }
</script>
