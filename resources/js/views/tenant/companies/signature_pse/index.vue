<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Firma digital PSE
                <el-tooltip
                    class="item"
                    content="Solicitar datos al PSE - Disponible para facturas, boletas y anulaciones de facturas"
                    effect="dark"
                    placement="top-start">
                    <i class="fa fa-info-circle"></i>
                </el-tooltip>
            </h3>
        </div>
        <div class="card-body"> 
            <form autocomplete="off" @submit.prevent="submit">
                <div class="row pt-1">
                    <div class="col-md-9">
                        <h4 class="control-label">Enviar documento a servicio externo
                            <el-tooltip
                                class="item"
                                content="Envía xml del documento al servicio web externo para agregar la firma digital y enviar el CDR al PSE"
                                effect="dark"
                                placement="top-start">
                                <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                        </h4>
                        <div :class="{'has-danger': errors.send_document_to_pse}"
                                class="form-group">
                            <el-switch v-model="form.send_document_to_pse"
                                        active-text="Si"
                                        inactive-text="No"></el-switch>
                            <small v-if="errors.send_document_to_pse"
                                    class="form-control-feedback"
                                    v-text="errors.send_document_to_pse[0]"></small>
                        </div>
                    </div>
                    
                    <template v-if="form.send_document_to_pse">

                        <div class="col-md-3 mt-3">
                            <div class="form-group" :class="{'has-danger': errors.client_id_pse}">
                                <label class="control-label">ID Cliente <span class="text-danger">*</span>
                                </label>
                                <el-input v-model="form.client_id_pse"></el-input>
                                <small class="form-control-feedback" v-if="errors.client_id_pse" v-text="errors.client_id_pse[0]"></small>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="form-group" :class="{'has-danger': errors.url_signature_pse}">
                                <label class="control-label">Url firma digital del documento <span class="text-danger">*</span></label>
                                <el-input v-model="form.url_signature_pse"></el-input>
                                <div class="sub-title text-muted"><small>Ejemplo: https://pse.com/firma-digital</small></div>
                                <small class="form-control-feedback" v-if="errors.url_signature_pse" v-text="errors.url_signature_pse[0]"></small>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group" :class="{'has-danger': errors.url_send_cdr_pse}">
                                <label class="control-label">Url envio CDR <span class="text-danger">*</span></label>
                                <el-input v-model="form.url_send_cdr_pse"></el-input>
                                <div class="sub-title text-muted"><small>Ejemplo: https://pse.com/envio-cdr</small></div>
                                <small class="form-control-feedback" v-if="errors.url_send_cdr_pse" v-text="errors.url_send_cdr_pse[0]"></small>
                            </div>
                        </div>
                    </template>
    
                </div>
    
                <div class="form-actions text-right pt-2">
                    <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
                </div>
            </form>
        </div> 
    </div>
</template>

<script>

    export default {
        data() {
            return {
                resource: 'companies',
                recordId: null,
                form: {},
                errors: {},
                loading_submit: false,
            }
        },
        created() {
            this.initForm()
            this.getData()
        },
        methods: {
            submit(){

                this.loading_submit = true
                this.$http.post(`/${this.resource}/store-send-pse`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })

            },
            initForm(){

                this.form = {
                    send_document_to_pse : false,
                    url_signature_pse : null,
                    url_send_cdr_pse : null,
                    client_id_pse: null,
                }

                this.errors = {}

            },
            getData() {
                this.$http.get(`/${this.resource}/record-send-pse`)
                    .then(response => {
                        this.form = response.data
                    })
            }, 
        }
    }
</script>
