<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.payment_link_type_id}">
                            <label class="control-label">Tipo</label>
                            <el-select  v-model="form.payment_link_type_id" :disabled="recordId">
                                <el-option v-for="(option, index) in payment_link_types" :key="index" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.payment_link_type_id" v-text="errors.payment_link_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.total}">
                            <label class="control-label">Total</label>
                            <el-input v-model="form.total"></el-input>
                            <small class="form-control-feedback" v-if="errors.total" v-text="errors.total[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-3">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'payment-links',
                errors: {}, 
                form: {}, 
                payment_link_types: []
            }
        },
        created() {
            this.initForm() 
            this.getTables() 
        },
        methods: {
            getTables(){

                this.$http.get(`/${this.resource}/tables`).then(response => {
                        this.payment_link_types = response.data.payment_link_types
                    })

            },
            initForm() {

                this.errors = {}

                this.form = {
                    id: null,
                    payment_link_type_id: '01',
                    total: 0,
                    without_payment: true,
                }

            },
            create() {

                this.titleDialog = (this.recordId)? 'Editar link de pago':'Nuevo link de pago'

                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record-without-payment/${this.recordId}`).then(response => {
                            this.form = response.data.data
                        })
                }
            },
            submit() {   

                this.loading_submit = true  
                this.$http.post(`${this.resource}/store-without-payment`, this.form)

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
                        if (error.response.status === 422) {
                            this.errors = error.response.data 
                        } else {
                            console.log(error.response)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
                    
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            }
        }
    }
</script>