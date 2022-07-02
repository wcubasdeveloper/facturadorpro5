<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" width="60%">
        <form autocomplete="off" @submit.prevent="submit" v-loading="loading">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12"> 
                        
                        <template v-if="records.length > 0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr width="100%">
                                            <th>#</th>
                                            <th width="12%">Fecha</th>
                                            <th width="12%">Hora</th>
                                            <th width="25%">Descripción</th>
                                            <th width="12%">T.Pagado</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in records" :key="index">
                                            <td>{{ index+1 }}</td>
                                            <td >{{ row.date }}</td>
                                            <td>{{ row.time }}</td>
                                            <td>{{ row.description }}</td>
                                            <td>{{ row.amount }}</td>
                                            <td>{{ row.transaction_state_description }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                        <template v-else>
                            <h4>Sin registros</h4>
                        </template>

                    </div> 
                </div>
            </div>
            <div class="form-actions text-right mt-3">
                <el-button @click.prevent="close()">Cerrar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                titleDialog: null,
                resource: 'payment-links',
                records: [],
                loading: false,
            }
        },
        created() {
        },
        methods: {
            create() {

                this.titleDialog = 'Transacciones Mercado Pago'
                this.loading = true
                this.$http.get(`/${this.resource}/transactions/${this.recordId}`).then(response => {
                        this.records = response.data
                    })
                    .then(()=>{
                        this.loading = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            }
        }
    }
</script>