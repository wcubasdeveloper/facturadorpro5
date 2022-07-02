<template>
    <el-dialog :close-on-click-modal="false"
               :close-on-press-escape="false"
               :show-close="false"
               :title="titleDialog"
               :visible="showDialog"
               append-to-body
               width="30%"
               @open="create">

        <div v-if="form.response_message"
             class="row mb-4">
            <div class="col-md-12">
                <el-alert
                    :title="form.response_message"
                    :type="form.response_type"
                    show-icon>
                </el-alert>
            </div>
        </div>

        <div v-if="form.send_to_pse"
             class="row">

            <div v-if="form.response_signature_pse"
                 class="col-lg-12 col-md-12 col-sm-12">
                <el-alert :title="`Firma Xml PSE: ${form.response_signature_pse}`"
                          show-icon
                          type="success"></el-alert>
            </div>

            <div v-if="form.response_send_cdr_pse"
                 class="col-lg-12 col-md-12 col-sm-12 mt-3">
                <el-alert :title="`Envio CDR PSE: ${form.response_send_cdr_pse}`"
                          show-icon
                          type="success"></el-alert>
            </div>

        </div>

        <div class="row">

            <template v-if="form.has_cdr">
                <div v-if="form && form.external_id && form.external_id != null"
                     class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold mt-3">
                    <button class="btn btn-lg btn-info waves-effect waves-light"
                            type="button"
                            @click="clickDownload('a4')">
                        <i class="fa fa-file-alt"></i>
                    </button>
                    <p>Descargar A4</p>
                </div>
                <!-- se agregaron templates con el issue #1435 -->
                <div v-if="form && form.external_id && form.external_id != null"
                     class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold mt-3">
                    <button class="btn btn-lg btn-info waves-effect waves-light"
                            type="button"
                            @click="clickDownload('ticket')">
                        <i class="fa fa-file-alt"></i>
                    </button>
                    <p>80MM</p>
                </div>
                <div v-if="form && form.external_id && form.external_id != null"
                     class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold mt-3">
                    <button class="btn btn-lg btn-info waves-effect waves-light"
                            type="button"
                            @click="clickDownload('ticket_58')">
                        <i class="fa fa-file-alt"></i>
                    </button>
                    <p>58MM</p>
                </div>
                <div v-if="form && form.external_id && form.external_id != null"
                     class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold mt-3">
                    <button class="btn btn-lg btn-info waves-effect waves-light"
                            type="button"
                            @click="clickDownloadCdr()">
                        <i class="fa fa-file-download"></i>
                    </button>
                    <p>Descargar CDR</p>
                </div>
            </template>
            <template v-else>
                <div class="col-lg-12 col-md-12 col-sm-12 text-center font-weight-bold mt-3">
                    <button class="btn btn-lg btn-info waves-effect waves-light"
                            type="button"
                            @click="clickDownload()">
                        <i class="fa fa-file-alt"></i>
                    </button>
                    <p>Descargar A4</p>
                </div>
            </template>

        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <el-input v-model="form.customer_email">
                    <el-button slot="append"
                               :loading="loading"
                               icon="el-icon-message"
                               @click="clickSendEmail">Enviar
                    </el-button>
                </el-input>
                <small v-if="errors.customer_email"
                       class="form-control-feedback"
                       v-text="errors.customer_email[0]"></small>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <el-input v-model="form.customer_telephone">
                    <template slot="prepend">+51</template>
                    <el-button slot="append"
                               @click="clickSendWhatsapp">Enviar
                        <el-tooltip class="item"
                                    content="Es necesario tener aperturado Whatsapp web"
                                    effect="dark"
                                    placement="top-start">
                            <i class="fab fa-whatsapp"></i>
                        </el-tooltip>
                    </el-button>
                </el-input>
                <small v-if="errors.customer_telephone"
                       class="form-control-feedback"
                       v-text="errors.customer_telephone[0]"></small>
            </div>
        </div>
        <span slot="footer"
              class="dialog-footer">
            <template v-if="showClose">
                <el-button @click="clickClose">Cerrar</el-button>
            </template>
            <template v-else>
                <el-button class="list"
                           @click="clickFinalize">Ir al listado</el-button>
                <el-button v-if="!isUpdate"
                           type="primary"
                           @click="clickNewDocument">{{ text_button }}</el-button>
            </template>
        </span>
    </el-dialog>
</template>

<script>
export default {
    props: ['showDialog', 'recordId', 'showClose', 'isUpdate'],
    data() {
        return {
            titleDialog: null,
            loading: false,
            resource: 'dispatches',
            errors: {},
            form: {},
            company: {},
            locked_emission: {},
            text_button: null,
        }
    },
    async created() {
        this.initForm()

        this.text_button = 'Nueva guía'
    },
    methods: {

        clickDownload(format = 'a4') {
            if( (this.form && this.form.external_id)) {
                window.open(`/print/dispatch/${this.form.external_id}/${format}`, '_blank');
            }
        },
        clickSendWhatsapp() {

            if (!this.form.customer_telephone) {
                return this.$message.error('El número es obligatorio')
            }

            window.open(`https://wa.me/51${this.form.customer_telephone}?text=${this.form.message_text}`, '_blank');

        },
        initForm() {
            this.errors = {};
            this.form = {
                customer_email: null,
                download_external_pdf: null,
                external_id: null,
                number: null,
                id: null,
                response_message: null,
                response_type: null,
                customer_telephone: null,
                message_text: null,
                download_cdr: null,
                state_type_id: '05',
                has_cdr: true,
                send_to_pse: false,
                response_signature_pse: null,
                response_send_cdr_pse: null,
            }

            this.locked_emission = {
                success: true,
                message: null
            }
            this.company = {
                soap_type_id: null,
            }
        },
        clickDownloadCdr() {
            window.open(this.form.download_cdr, '_blank');
        },
        async create() {
            await this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                this.form = response.data.data;
                this.titleDialog = 'Guía: ' + this.form.number;
            });

        },
        clickPrint(format) {
            window.open(`/${this.resource}/print/${this.form.external_id}/${format}`, '_blank');
        },
        clickSendEmail() {
            this.loading = true
            this.$http.post(`/${this.resource}/email`, {
                customer_email: this.form.customer_email,
                id: this.form.id
            })
                .then(response => {
                    if (response.data.success) {
                        this.$message.success('El correo fue enviado satisfactoriamente')
                    } else {
                        this.$message.error('Error al enviar el correo')
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors
                    } else {
                        this.$message.error(error.response.data.message)
                    }
                })
                .then(() => {
                    this.loading = false
                })
        },
        clickFinalize() {
            location.href = `/${this.resource}`
        },
        clickNewDocument() {
            this.clickClose()
        },
        clickClose() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
    }
}
</script>
