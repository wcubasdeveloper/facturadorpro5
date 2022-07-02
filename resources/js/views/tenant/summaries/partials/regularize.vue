<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%"  append-to-body @close="close">
             
        <div class="row" v-if="summary">
            <div class="col-md-2">
                <div class="widget-summary widget-summary-xs pl-3">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-warning">
                            <i class="fas fa-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <p class="title"><strong>¿Los comprobantes del resúmen {{summary.identifier}} han sido validados por SUNAT?</strong></p>
            </div>
            <div class="col-md-12">
                <p>Si acepta la operación, todos los comprobantes informados en el resúmen se modificarán a estado <strong>ACEPTADO</strong></p>
            </div>
        </div>

        <span slot="footer" class="dialog-footer">
            <el-button @click="clickCancel" :loading="loading_cancel">Cancelar</el-button>
            <el-button @click="clickSubmit" type="danger" :loading="loading_submit">Aceptar</el-button>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'summary'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'summaries',
                loading_cancel: false,
                loading_submit: false,
            }
        },
        async created() {
        },
        methods: {
            async clickSubmit(){

                this.loading_submit = true

                await this.$http.get(`/${this.resource}/regularize/${this.summary.id}`)
                    .then(response => {

                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData') 
                            this.close()

                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        this.$message.error(error.response.data.message)
                    })
                    .then(() => {
                        this.loading_submit = false
                    })

            }, 
            async create() {

                // console.log(this.summary) 
            },  
            close() {
                this.$emit('update:showDialog', false)
            },
            async clickCancel() {
                
                this.loading_cancel = true

                await this.$http.get(`/${this.resource}/cancel-regularize/${this.summary.id}`)
                    .then(response => {

                        this.$eventHub.$emit('reloadData') 

                        if (response.data.success) {
                            this.$message.success(response.data.message)
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        this.$message.error(error.response.data.message)
                    })
                    .then(() => {
                        this.loading_cancel = false
                    })

                this.close()
            },
        }
    }
</script>
