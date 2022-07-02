<template>
    <div class="pt-2">
        <el-dialog :title="titleDialog" :visible="showDialog" class="" @close="close" @open="create" >

            <el-tabs v-model="activeTab" @tab-click="handleClick" v-loading="loading">
                <el-tab-pane label="Yape" name="01">

                    <template  v-if="payment_configuration.enabled_yape">

                        <div class="row">
                            <div class="col-md-7 col-lg-5 bg-yape text-center pr-0">
                                <img src="/logo/yape-logo.png" class="my-1" style="max-width:80px;" alt="Yape">
                                <div class="card mx-4">
                                    <div class="card-body">
                                        <div class="p-2">
                                            <img :src="payment_configuration.qrcode_yape" class="img-fluid" alt="qr_yape">
                                        </div>
                                    </div>
                                </div>
                                <p class="text-white">{{payment_configuration.name_yape}}</p>
                                <div class="payment-links-yape">
                                    <template v-if="has_payment_link">
                                        <el-button type="primary" class="mt-2" icon="el-icon-share"
                                            v-clipboard:copy="form.user_payment_link"
                                            v-clipboard:success="onCopy"
                                            v-clipboard:error="onError"
                                        >
                                            Copiar Link
                                        </el-button>
                                        <el-button type="primary" class="mt-2" icon="fas fa-envelope fa-fw" @click="showInputEmail">Enviar Correo</el-button>
                                        <el-button type="primary" class="my-2" icon="fab fa-whatsapp fa-fw" @click="showInputWhatsapp">Enviar Whatsapp</el-button>

                                    </template>
                                    <template v-else>
                                        <el-button type="primary" class="mt-2 mb-2" icon="el-icon-link" @click="clickGenerateLink" :loading="loading_generate_link">Generar Link</el-button>
                                    </template>

                                </div>
                                <div class="m-3" v-if="show_input_whatsapp || show_input_email">
                                    <template v-if="show_input_whatsapp">
                                        <el-input v-model="form_utilities.customer_telephone">
                                            <template slot="prepend">+51</template>
                                            <el-button slot="append"
                                                    @click="clickSendWhatsapp">Enviar
                                                <el-tooltip class="item"
                                                            content="Se recomienta tener abierta la sesión de Whatsapp web"
                                                            effect="dark"
                                                            placement="top-start">
                                                    <i class="fab fa-whatsapp"></i>
                                                </el-tooltip>
                                            </el-button>
                                        </el-input>
                                    </template>
                                    <template v-if="show_input_email">
                                        <el-input v-model="form_utilities.customer_email" class="mt-2">
                                            <el-button slot="append"
                                                    :loading="loading_email"
                                                    icon="el-icon-message"
                                                    @click="clickSendEmail">Enviar
                                            </el-button>
                                        </el-input>
                                    </template>
                                </div>
                            </div>
                            <div class="col-md-5 col-lg-7 pt-2 d-flex justify-content-center">
                                <template v-if="has_payment_link">
                                    <!-- <el-button type="primary">Adjuntar pago</el-button> -->

                                    <el-upload
                                            :data="{'id': form.id}"
                                            :headers="headers"
                                            :multiple="false"
                                            :on-remove="handleRemove"
                                            :action="`/${resource}/uploaded-file`"
                                            :show-file-list="true"
                                            :file-list="fileList"
                                            :on-success="onSuccess"
                                            :limit="1"
                                            >
                                        <el-button slot="trigger" type="primary">Adjuntar pago</el-button>
                                    </el-upload>


                                    <img class="img-fluid pt-3" style="width: 100%; max-height: 300px" v-if="form.image_url_uploaded_filename" :src="form.image_url_uploaded_filename" alt="Yape">

                                </template>

                            </div>
                        </div>
                    </template>
                    <template v-else>
                         <el-alert
                            title="Configuración deshabilitada"
                            type="info"
                            description="Disponible en configuración/empresa"
                            show-icon>
                        </el-alert>
                    </template>

                </el-tab-pane>
                <el-tab-pane label="Mercado Pago" name="02" >

                    <template  v-if="payment_configuration.enabled_mp">
                        <div class="row">
                            <div class="col-md-7 bg-mercadopago text-center pr-0">
                                <img src="/logo/logo_mercadopago.jpg" class="img-fluid" alt="Yape">
                                <div class="payment-links-mp">

                                    <template v-if="has_payment_link">

                                        <el-button type="primary" class="mt-2" icon="el-icon-share"
                                            v-clipboard:copy="form.user_payment_link"
                                            v-clipboard:success="onCopy"
                                            v-clipboard:error="onError"
                                            >
                                            Copiar Link
                                        </el-button>

                                        <el-button type="primary" class="mt-2" icon="fas fa-envelope fa-fw" @click="showInputEmail">Enviar Correo</el-button>
                                        <el-button type="primary" class="my-2" icon="fab fa-whatsapp fa-fw" @click="showInputWhatsapp">Enviar Whatsapp</el-button>

                                    </template>
                                    <template v-else>
                                        <el-button type="primary" class="mt-2" icon="el-icon-link" @click="clickGenerateLink" :loading="loading_generate_link">Generar Link</el-button>
                                    </template>

                                    <div class="m-3" v-if="show_input_whatsapp || show_input_email">

                                        <template v-if="show_input_whatsapp">

                                            <el-input v-model="form_utilities.customer_telephone">
                                                <template slot="prepend">+51</template>
                                                <el-button slot="append"
                                                        @click="clickSendWhatsapp">Enviar
                                                    <el-tooltip class="item"
                                                                content="Se recomienta tener abierta la sesión de Whatsapp web"
                                                                effect="dark"
                                                                placement="top-start">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </el-tooltip>
                                                </el-button>
                                            </el-input>

                                        </template>


                                        <template v-if="show_input_email">
                                            <el-input v-model="form_utilities.customer_email" class="mt-2">
                                                <el-button slot="append"
                                                        :loading="loading_email"
                                                        icon="el-icon-message"
                                                        @click="clickSendEmail">Enviar
                                                </el-button>
                                            </el-input>
                                        </template>

                                    </div>

                                </div>
                            </div>
                            <div class="col-md-5 pt-2">

                                <template v-if="has_payment_link">
                                    <template v-if="form.query_transaction">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4><b>Tiene un pago aceptado.</b></h4>
                                            </div>
                                            <div class="col-12 text-center" v-if="form.transaction">
                                                <h1 class="display-3 color--success pt-5"><i class="fas fa-check"></i></h1>
                                                <p><b>{{form.transaction.transaction_state_message}}</b></p>
                                                <p><b>Total pagado: S/ {{form.transaction.transaction_total}}</b></p>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <el-button type="primary" @click="clickQueryStatusMP">Consultar estado</el-button>
                                    </template>
                                </template>

                            </div>
                        </div>
                    </template>
                    <template v-else>
                         <el-alert
                            title="Configuración deshabilitada"
                            type="info"
                            description="Disponible en configuración/empresa"
                            show-icon>
                        </el-alert>
                    </template>
                </el-tab-pane>
            </el-tabs>

            <div class="row mt-3">
                <div class="col-md-12">
                    <h4 class="text-left"><b>Total a pagar: S/ {{getPayment()}}</b></h4>
                </div>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        props: [
            'documentPaymentId',
            'currencyTypeId',
            'exchangeRateSale',
            'payment',
            'showDialog',
        ],
        data() {
            return {
                loading: false,
                titleDialog: 'Link de Pago',
                activeTab: '01',
                payment_configuration: {},
                titleDialog: null,
                loading_generate_link: false,
                loading_email: false,
                resource: 'payment-links',
                errors: {},
                form: {},
                has_payment_link: false,
                copyTextValue: null,
                show_input_whatsapp: false,
                show_input_email: false,
                form_utilities: {},
                headers: headers_token,
                fileList: [],
            }
        },
        async created() {
            await this.initForm()
            await this.getConfiguration()
        },
        methods: {
            clickQueryStatusMP(){

                this.loading = true

                this.$http.post(`/${this.resource}/query-transaction-state`, {
                        id : this.form.id
                    })
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.getData()
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
                    .then(()=>{
                        this.loading = false
                    })

            },
            getPayment(){

                if(this.currencyTypeId === 'PEN') return this.payment

                return _.round(this.payment * this.exchangeRateSale, 2)

            },
            onSuccess(response, file, fileList) {

                this.fileList = fileList

                if (response.success) {

                    this.getData()

                } else {

                    this.cleanFileList()
                    this.$message.error(response.message)
                }

                // console.log(this.records)

            },
            cleanFileList(){
                this.fileList = []
            },
            handleRemove(file, fileList) {

                this.form.filename = null
                this.form.temp_path = null
                this.fileList = []

            },
            clickSendEmail() {

                if (!this.form_utilities.customer_email) return this.$message.error('El correo electrónico es obligatorio')

                this.loading_email = true
                this.$http.post(`/${this.resource}/email`, {
                        customer_email: this.form_utilities.customer_email,
                        user_payment_link: this.form.user_payment_link
                    })
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
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
                        this.loading_email = false
                    })
            },
            clickSendWhatsapp() {

                if (!this.form_utilities.customer_telephone)
                {
                    return this.$message.error('El número es obligatorio')
                }

                const text = `Su link de pago ha sido generado correctamente, puede revisarlo en: ${this.form.user_payment_link}`

                window.open(`https://wa.me/51${this.form_utilities.customer_telephone}?text=${text}`, '_blank');

            },
            showInputEmail(){
                this.show_input_email = !this.show_input_email
            },
            showInputWhatsapp(){
                this.show_input_whatsapp = !this.show_input_whatsapp
            },
            onCopy: function(e) {
                this.$message.success('Texto copiado al portapapeles')
            },
            onError: function(e) {
                this.$message.error('No se pudo copiar el texto al portapapeles')
                console.log(e);
            },
            handleClick(){
                this.form.payment_link_type_id = this.activeTab
                this.show_input_whatsapp = false
                this.show_input_email = false
                this.getData()
            },
            async create(){

                this.form.payment_id = this.documentPaymentId
                this.form.total = this.payment

                await this.getData()

            },
            clickGenerateLink(){

                this.loading_generate_link = true

                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.getData()
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
                    .then(()=>{
                        this.loading_generate_link = false
                    })
            },
            initForm(){

                this.form = {
                    id: null,
                    payment_link_type_id: this.activeTab,
                    payment_id: null,
                    total: 0,
                    instance_type: 'document',
                    user_payment_link: null,
                    image_url_uploaded_filename: null,
                    query_transaction: false,
                    transaction: null,
                }

                this.form_utilities = {
                    customer_telephone: null,
                    customer_email: null,
                }

                this.titleDialog = 'Link de pago'

            },
            async getData() {

                this.loading = true

                await this.$http.get(`/${this.resource}/record/${this.documentPaymentId}/${this.form.instance_type}/${this.form.payment_link_type_id}`)
                    .then(response => {

                        this.has_payment_link = response.data.has_payment_link
                        if(this.has_payment_link) this.form = response.data.data

                    })
                    .then(()=>{
                        this.loading = false
                    })

            },
            async getConfiguration() {
                await this.$http.get(`/payment-configurations/record-permissions`)
                    .then(response => {
                        this.payment_configuration = response.data.data
                    })
            },
            close() {
                this.$emit('update:showDialog', false);
            },
        }
    }
</script>


<style>
    .el-dialog__body {
        padding-top: 0px;
    }

    .payment-links-yape .el-button--primary {
        background-color: #10cbb4 !important;
        border-color: #10cbb4 !important;
    }

    .bg-yape {
        background: #5e186c;
    }
</style>
