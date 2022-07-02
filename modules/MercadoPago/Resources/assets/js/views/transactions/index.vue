<template>
    <div class="" v-loading="loading_submit">
        <div class="pl-1 pr-1" id="pay" v-if="!success_response">
            <div class="row">

                <div class="col-md-12 col-lg-12">
                    <fieldset>
                        <legend>Datos de tarjeta</legend>
                        <div class="row">
                            
                            <div class="col-md-12 col-lg-12">
                                <div :class="{'has-danger': errors.user}" class="form-group">
                                    <label class="control-label" for="user">
                                        Nombre del titular como aparece en la tarjeta
                                    </label>
                                    <input class="form-control" type="text" v-model="form.user" id="user" placeholder="Nombres Apellidos" autocomplete>
                                    <small class="form-control-feedback" v-if="errors.user" v-text="errors.user[0]"></small>
                                </div>
                            </div>


                            <div class="col-md-6 col-lg-6">
                                <div :class="{'has-danger': errors.transaction_amount}" class="form-group">
                                    <label class="control-label" for="transaction_amount">
                                        Monto
                                    </label>
                                    <input class="form-control" type="text" v-model="visual_transaction_amount" id="transaction_amount" readonly/>
                                    <!-- <input class="form-control" type="text" v-model="form.transaction_amount" id="transaction_amount" readonly/> -->
                                    <small class="form-control-feedback" v-if="errors.transaction_amount" v-text="errors.transaction_amount[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div :class="{'has-danger': errors.email}" class="form-group">
                                    <label class="control-label" for="email">Correo</label>
                                    <input class="form-control" type="text" id="email" v-model="form.email" placeholder="demo@tarjeta2efectivo.com" data-checkout="email" />
                                    <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div :class="{'has-danger': errors.card_number}" class="form-group">
                                    <label class="control-label" for="cardNumber">Número de la tarjeta</label>
                                    <input class="form-control" v-model="form.card_number" @change="guessPaymentMethod" @input="guessPaymentMethod" placeholder="5031755734530604" type="number" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onDrag="return false" onDrop="return false" value="" autocomplete=off />
                                    <small class="form-control-feedback" v-if="errors.card_number" v-text="errors.card_number[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-2 col-lg-2">
                                <div :class="{'has-danger': errors.card_month_due}" class="form-group">
                                    <label class="control-label" for="card_month_due">Mes</label>
                                    <input class="form-control" type="text" v-model="form.card_month_due" id="cardExpirationMonth" placeholder="11" autocomplete="off" @change="guessPaymentMethod" @input="guessPaymentMethod">
                                    <small class="form-control-feedback" v-if="errors.card_month_due" v-text="errors.card_month_due[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-2 col-lg-2">
                                <div :class="{'has-danger': errors.card_year_due}" class="form-group">
                                    <label class="control-label" for="card_year_due">Año</label>
                                    <input class="form-control" type="text" v-model="form.card_year_due" id="cardExpirationYear" placeholder="25" data-checkout="cardExpirationYear" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off @change="guessPaymentMethod" @input="guessPaymentMethod">
                                    <small class="form-control-feedback" v-if="errors.card_year_due" v-text="errors.card_year_due[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-2 col-lg-2">
                                <div :class="{'has-danger': errors.security_code}" class="form-group">
                                    <label class="control-label" for="security_code">CVV</label>
                                    <input class="form-control" type="text" v-model="form.security_code" id="securityCode" placeholder="123" data-checkout="securityCode" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off @change="guessPaymentMethod" @input="guessPaymentMethod">
                                    <small class="form-control-feedback" v-if="errors.security_code" v-text="errors.security_code[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div :class="{'has-danger': errors.payment_method_id}" class="form-group">
                                    <label class="control-label" for="payment_method_id">Tipo de Tarjeta</label>
                                    <input class="form-control" type="text" v-model="form.payment_method_id" readonly name="payment_method_id" id="payment_method_id">
                                    <small class="form-control-feedback" v-if="errors.payment_method_id" v-text="errors.payment_method_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-8 col-lg-8 mt-3">
                                <label for="installments">Cuotas</label>
                                <select id="installments" v-model="form.installments" class="form-control mb-3" name="installments"></select>
                            </div>

                        </div>
                    </fieldset>
                </div>

                <div class="col-md-12 col-lg-12 mt-3">
                    <fieldset>
                        <legend>Inf.Adicional</legend>
                        <div class="row">

                            <div  class="col-md-12 col-lg-12">
                                <div :class="{'has-danger': errors.description}" class="form-group">
                                    <label class="control-label" for="description">Descripción del pago</label>
                                    <input class="form-control" type="text" id="description" v-model="form.description">
                                    <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div :class="{'has-danger': errors.identity_document_type}" class="form-group">
                                    <label class="control-label" for="identity_document_type">Tipo de documento</label>
                                    <select class="form-control" id="docType" v-model="form.identity_document_type" data-checkout="docType"></select>
                                    <small class="form-control-feedback" v-if="errors.identity_document_type" v-text="errors.identity_document_type[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div :class="{'has-danger': errors.number}" class="form-group">
                                    <label class="control-label" for="number">Número de documento</label>
                                    <input class="form-control" type="number" v-model="form.number" placeholder="12345678" />
                                    <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <input type="hidden" v-model="form.payment_type_id" id="payment_type_id">
                <input type="hidden" v-model="form.aux_installments" id="aux_installments">
                <input type="hidden" v-model="form.errors_sdk" id="errors_sdk">

            </div>
            <div class="form-actions text-right mt-2 mb-3">
            
                <el-button type="primary" class="mt-2" icon="el-icon-check" size="default" @click="clickProccess()" :loading="loading_submit">Pagar</el-button>
            </div>
        </div>
        <!-- respuesta mercado pago -->
        <div class="row" v-else>
            <div class="col-12 text-center">
                <template v-if="response_payment.success_operation && response_payment.transaction_state_id == '01'">
                    <h1 class="display-3 color--success pt-5"><i class="fas fa-check"></i></h1>
                    <p class="pt-5">Su operación ha finalizado</p>
                    <p><b>{{response_payment.message}}</b></p>
                    <p><b>{{response_payment.transaction_state_message}}</b></p>
                </template>

                <template v-else-if="response_payment.success_operation && (response_payment.transaction_state_id == '02' || response_payment.transaction_state_id == '03')">
                    <h1 class="display-3 color--warning pt-5"><i class="fas fa-exclamation-triangle icon-warning"></i></h1>
                    <p class="pt-5">Su operación ha finalizado</p>
                    <p><b>{{response_payment.message}}</b></p>
                    <p><b>{{response_payment.transaction_state_message}}</b></p>
                </template>

                <template v-else>
                    <h1 class="display-3 color--error pt-5 "><i class="fas fa-times icon-error"></i></h1>
                    <p class="pt-5">Su operación ha finalizado con errores</p>
                    <p><b>{{response_payment.message}}</b></p>
                    <p><b>{{response_payment.transaction_state_message}}</b></p>
                </template>

                <el-button type="primary" class="mt-2" icon="el-icon-check" size="default" @click="clickFinish()">Finalizar</el-button>
            </div>
        </div>

        <div v-show="alert_sdk.success">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="mr-4"><i class="fas fa-exclamation-triangle icon-warning mr-2"></i><b>Errores:</b></h5>
                <b>- {{alert_sdk.message}}</b><br>
                <span v-for="(row, index) in alert_sdk.errors" :key="index">
                    <b>- {{row.description}}</b><br>
                </span>
            </div>
        </div>


    </div>
</template>


<script>
export default {
    props: [
        'payment_link',
        'payment_configuration',
        'total',
    ],
    data() {
        return {
            errors_sdk: {},
            loading_submit: false,
            resource: 'transactions',
            form: {},
            errors: {},
            doSubmit: false,
            alert_sdk: {
                success: false,
            },
            success_response: false,
            client_errors: [],
            bank_accounts: [],
            mercado_pago: null,
            response_payment: {},
            visual_transaction_amount: 0
        }
    },
    async created() {

        await this.initForm()
        await this.setDefaultData()
        await this.getClientErrors()
    },
    methods: {
        clickFinish(){
            location.reload()
        },
        async setDefaultData() {


        },
        getIentificationType(identity_document_type_id) {

            switch (identity_document_type_id) {
                case '1':
                    return 'DNI';
                    break;
                case '4':
                    return 'C.E';
                    break;
                default:
                    return 'Otro'
            }

        },
        getClientErrors() {
            this.$http.get(`/client-errors/records`)
                .then(response => {
                    this.client_errors = response.data.data
                })
        },
        initForm() {

            this.form = {

                customer_uuid: this.customerUuid,
                description: null,
                transaction_amount: this.total,
                email: null,
                user: null,
                card_number: null,
                card_month_due: null,
                card_year_due: null,
                security_code: null,
                installments: null,
                payment_type_id: null,
                payment_method_id: null,
                token: null,
                number: null,
                identity_document_type: 'DNI',
                payment_link_id: this.payment_link.id,

            }

            this.errors_sdk = {
                success: false,
                status: null,
                response: null
            }

            this.mercado_pago = window.Mercadopago

            this.visual_transaction_amount = this.total

        },
        async clickProccess() {

            if (!this.form.installments) {
                return this.showAlertErrors('No ha seleccionado una cuota', [])
            }

            await this.doPay()

        },
        guessPaymentMethod() {

            let cardnumber = document.getElementById("cardNumber").value;

            if (cardnumber.length >= 6) {
                let bin = cardnumber.substring(0, 6);
                this.mercado_pago.getPaymentMethod({
                    "bin": bin
                }, this.setPaymentMethod);
            }

        },
        setPaymentMethod(status, response) {

            if (status == 200) {

                let paymentMethodId = response[0].id;
                // let paymentTypeId = response[0].payment_type_id;
                this.form.payment_method_id = paymentMethodId
                this.form.payment_type_id = response[0].payment_type_id

                let element = document.getElementById('payment_method_id');
                element.value = paymentMethodId;

                this.getInstallments();

            } else {

                let description = ''

                if (typeof response.cause == "object") {

                    description = response.cause.description
                    this.showAlertErrors(response.error, [{
                        description: description
                    }])

                } else if (Array.isArray(response.cause)) {

                    let errors = []

                    response.cause.forEach((it, index) => {
                        errors.push({
                            description: description
                        })
                    })

                    this.showAlertErrors(response.error, errors)

                } else {
                    console.log(response)
                }

                // alert( `payment method info error: ${message}`);
            }

        },
        getInstallments() {

            // let element_aux_installments = document.getElementById('aux_installments');
            let ctx = this

            this.mercado_pago.getInstallments({
                "payment_method_id": document.getElementById('payment_method_id').value,
                "amount": parseFloat(document.getElementById('transaction_amount').value)

            }, function (status, response) {

                if (status == 200) {
                    document.getElementById('installments').options.length = 0;

                    if (response[0].payer_costs.length > 0) {
                        // element_aux_installments.value = response[0].payer_costs[0].installments;
                        ctx.form.installments = response[0].payer_costs[0].installments
                    }

                    response[0].payer_costs.forEach(installment => {
                        let opt = document.createElement('option');
                        opt.text = installment.recommended_message;
                        opt.value = installment.installments;
                        document.getElementById('installments').appendChild(opt);
                    });
                } else {

                    console.log(response)
                    alert(`installments method info error: ${response}`);
                }
            })

        },
        doPay() {

            if (!this.doSubmit) {

                this.mercado_pago.createToken({
                    "cardNumber": this.form.card_number,
                    "cardExpirationMonth": this.form.card_month_due,
                    "cardExpirationYear": this.form.card_year_due,
                    "securityCode": this.form.security_code,
                    "cardholderName": this.form.user,
                    "docType": this.form.identity_document_type,
                    "docNumber": this.form.number
                }, this.sdkResponseHandler);

                // return false;
            }

        },
        async sdkResponseHandler(status, response) {

            if (status != 200 && status != 201) {

                await this.hasErrors(status, response)

            } else {
                var form = document.querySelector('#pay');
                var card = document.createElement('input');
                card.setAttribute('id', 'token');
                card.setAttribute('name', 'token');
                card.setAttribute('type', 'hidden');
                card.setAttribute('value', response.id);
                form.appendChild(card);
                // console.log(response)
                this.form.token = await response.id
                await this.sendPayment()

            }

        },
        async hasErrors(status, response) {

            this.errors_sdk = {
                success: false,
                status: status,
                response: response
            }
            // console.log(response)

            let errors = []
            let message = null

            await this.errors_sdk.response.cause.forEach((it, index) => {

                let error = this.getInfoError(it.code, 'data_entry')

                if (index == 0) {
                    message = error.client_error_type_name
                }

                errors.push({
                    description: error.user_message
                })
            })

            this.showAlertErrors(message, errors)

        },
        getInfoError(code, type) {

            let error = _.find(this.client_errors, {
                code: code,
                client_error_type_id: type
            })
            return error ? error : _.find(this.client_errors, {
                code: 'default',
                client_error_type_id: type
            })

        },
        showAlertErrors(message, errors) {

            this.alert_sdk.success = true
            this.alert_sdk.message = message
            this.alert_sdk.errors = errors
            setTimeout(() => this.alert_sdk.success = false, 10000)

        },
        async sendPayment(){

            this.loading_submit = true

            await this.$http.post(`/${this.resource}`, this.form)
                .then(response => {

                    this.success_response = response.data.success
                    this.response_payment = response.data

                    if (response.data.success) 
                    {

                        if(this.response_payment.success_operation)
                        {
                            this.$message.success(response.data.message)
                        }
                        else
                        {
                            this.$message.error(response.data.message)
                        }

                        this.initForm()

                    } 
                    else 
                    {
                        this.$message.error(response.data.message)
                    }

                })
                .catch(error => {

                    if (error.response.status === 422) {
                        // console.log(error.response.data)
                        this.errors = error.response.data
                    } else {
                        console.log(error.response)
                    }
                })
                .then(() => {
                    this.loading_submit = false
                })
        }
    }
}
</script>

<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -webkit-appearance: textfield !important;
        margin: 0;
        -moz-appearance: textfield !important;
    }
</style>