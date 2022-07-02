<template>
    <div class="row col-lg-12 m-0 p-0">
        <Keypress :key-code="113"
                  key-event="keyup"
                  @success="handleFn113"/>

        <div class="col-12">
            <div class="row">
                <div class="col-6">
                    <el-radio-group v-model="form.document_type_id" size="small" @change="filterSeries">
                        <el-radio-button label="01">FACTURA</el-radio-button>
                        <el-radio-button label="03">BOLETA</el-radio-button>
                        <el-radio-button label="80">N. VENTA</el-radio-button>
                    </el-radio-group>
                </div>
                <div class="col-2 px-0">
                    <el-select v-model="form.series_id" class="c-width" style="height: 30px;">
                        <el-option v-for="option in series"
                                   :key="option.id"
                                   :label="option.number"
                                   :value="option.id">
                        </el-option>
                    </el-select>
                </div>
                <div class="col-3">
                    <el-switch v-model="enabled_discount"
                                        active-text="Descuento"
                                        class="control-label font-weight-semibold m-0 text-center m-b-0"
                                        @change="changeEnabledDiscount"></el-switch>
                </div>
            </div>
            <div class="row d-flex align-items-end">
                <div class="col-4">
                    <div class="form-group">
                        <label class="control-label">Ingrese monto</label>
                        <el-input ref="enter_amount"
                                    v-model="enter_amount"
                                    @input="enterAmount()"
                                    @keyup.enter.native="keyupEnterAmount()">
                            <template slot="prepend" style="px-1">{{ currencyTypeActive.symbol }}</template>
                        </el-input>
                    </div>
                </div>
                <div class="col-3">
                    <div :class="{'has-danger': difference < 0}"
                            class="form-group">
                        <label class="control-label"
                                v-text="(difference <0) ? 'Faltante' :'Vuelto'"></label>
                        <!-- <el-input v-model="difference" :disabled="true">
                            <template slot="prepend">{{currencyTypeActive.symbol}}</template>
                        </el-input> -->
                        <h4 class="control-label font-weight-semibold m-0 text-center m-b-0">
                            {{ currencyTypeActive.symbol }} {{ difference }}</h4>
                    </div>
                </div>
                <div class="col-5">
                    <button class="btn btn-sm btn-block btn-primary" @click="clickAddPayment()">
                        Agregar Pagos
                    </button>
                </div>
            </div>
            <div class="row">
                <template v-for="(pay,index) in form.payments">
                    <div :key="pay.id"
                            class="col-lg-1">
                        <label>{{ index + 1 }}.-</label>
                    </div>
                    <div :key="pay.id"
                            class="col-lg-6">
                        <label>{{ getDescriptionPaymentMethodType(pay.payment_method_type_id) }}</label>
                    </div>
                    <div :key="pay.id"
                            class="col-lg-5">
                        <label><strong>{{ currencyTypeActive.symbol }}
                                        {{ pay.payment }}</strong> </label>
                    </div>
                </template>
            </div>
            <div class="row" v-if="enabled_discount">
                <div class="col-12">
                    <div class="form-group">
                        <label class="control-label">Monto descuento</label>
                        <el-input v-model="discount_amount"
                                    :disabled="!enabled_discount"
                                    @input="inputDiscountAmount()">
                            <template slot="prepend">{{ currencyTypeActive.symbol }}</template>
                        </el-input>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div v-if="businessTurns.active" class="row col-md-12 col-lg-12">
                        <div class="col-md-6 col-lg-6"></div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="control-label">N° Placa</label>
                                <el-input v-model="form.plate_number" type="textarea"></el-input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div>
                    <template v-if="form.total_plastic_bag_taxes > 0">
                        <div class="row m-0 p-0 bg-white h-17 d-flex align-items-center">
                            <div class="col-sm-6 py-1">
                                <p class="font-weight-semibold mb-0">SUBTOTAL</p>
                            </div>
                            <div class="col-sm-6 py-1 text-right">
                                <p class="font-weight-semibold mb-0">
                                    {{ currencyTypeActive.symbol }}
                                    {{ form.total_taxed }}
                                </p>
                            </div>
                        </div>
                        <div class="row m-0 p-0 bg-white h-17 d-flex align-items-center">
                            <div class="col-sm-6 py-1">
                                <p class="font-weight-semibold mb-0">IGV</p>
                            </div>
                            <div class="col-sm-6 py-1 text-right">
                                <p class="font-weight-semibold mb-0">
                                    {{ currencyTypeActive.symbol }}
                                    {{ form.total_igv }}
                                </p>
                            </div>
                        </div>
                        <div class="row m-0 p-0 bg-white h-17 d-flex align-items-center">
                            <div class="col-sm-6 py-1">
                                <p class="font-weight-semibold mb-0">ICBPER</p>
                            </div>
                            <div class="col-sm-6 py-1 text-right">
                                <p class="font-weight-semibold mb-0">
                                    {{ currencyTypeActive.symbol }}
                                    {{ form.total_plastic_bag_taxes }}
                                </p>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="row m-0 p-0 bg-white h-25 d-flex align-items-center">
                            <div class="col-sm-6 py-1">
                                <p class="font-weight-semibold mb-0">SUBTOTAL</p>
                            </div>
                            <div class="col-sm-6 py-1 text-right">
                                <p class="font-weight-semibold mb-0">
                                    {{ currencyTypeActive.symbol }} {{form.total_taxed}}
                                </p>
                            </div>
                        </div>
                        <div class="row m-0 p-0 bg-white h-25 d-flex align-items-center">
                            <div class="col-sm-6 py-1">
                                <p class="font-weight-semibold mb-0">IGV</p>
                            </div>
                            <div class="col-sm-6 py-1 text-right">
                                <p class="font-weight-semibold mb-0">
                                    {{ currencyTypeActive.symbol }}{{ form.total_igv }}
                                </p>
                            </div>
                        </div>
                    </template>
                    <div class="row m-0 p-0 h-25 d-flex align-items-center">
                        <div class="col-sm-6 py-2">
                            <p class="font-weight-semibold mb-0">TOTAL</p>
                        </div>
                        <div class="col-sm-6 py-2 text-right">
                            <h4 class="font-weight-semibold mb-0">{{ currencyTypeActive.symbol }} {{form.total}}</h4>
                        </div>
                    </div>
                    <div class="row m-0 p-0 h-25 d-flex align-items-center bg-white">
                        <div class="col-lg-6">
                            <button :disabled="button_payment"
                                    class="btn btn-block btn-primary"
                                    @click="clickPayment">PAGAR
                            </button>
                        </div>
                        <div class="col-lg-6">
                            <button class="btn btn-block btn-danger"
                                    @click="clickCancel">CANCELAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <options-form
            :recordId="documentNewId"
            :resource="resource_options"
            :showDialog.sync="showDialogOptions"
            :statusDocument="statusDocument"
        ></options-form>

        <multiple-payment-form
            :payments="payments"
            :showDialog.sync="showDialogMultiplePayment"
            :total="form.total"
            @add="addRow"
        ></multiple-payment-form>

        <!-- <sale-notes-options :showDialog.sync="showDialogSaleNote"
                          :recordId="saleNotesNewId"
                          :showClose="true"></sale-notes-options>  -->

        <card-brands-form :external="true"
                          :recordId="null"
                          :showDialog.sync="showDialogNewCardBrand"></card-brands-form>
    </div>
</template>
<style>
.c-width {
    margin-right: 0 !important;
    padding: 0 !important;
    width: 80px !important;
}

.card {
    margin-bottom: 2px;
}

.card-body {
    padding: 10px;
}

.h-40 {
    height: 40% !important;
}

.h-60 {
    height: 60% !important;
}

.el-input-group__prepend {
    padding-right: 10px;
    padding-left: 10px;
}

.el-radio-button--small .el-radio-button__inner {
    padding: 9px 6px;
    font-size: 10px;
}
</style>

<script>
import Keypress from 'vue-keypress'

import CardBrandsForm from '../../card_brands/form.vue'
import SaleNotesOptions from '../../sale_notes/partials/options.vue'
import OptionsForm from './options.vue'
import MultiplePaymentForm from './multiple_payment.vue'

export default {
    components: {OptionsForm, CardBrandsForm, SaleNotesOptions, MultiplePaymentForm, Keypress},

    props: ['form', 'customer', 'currencyTypeActive', 'exchangeRateSale', 'is_payment', 'soapCompany', 'businessTurns', 'isPrint', 'rowsItems'],
    data() {
        return {
            enabled_discount: false,
            discount_amount: 0,
            loading_submit: false,
            showDialogOptions: false,
            showDialogMultiplePayment: false,
            showDialogSaleNote: false,
            showDialogNewCardBrand: false,
            documentNewId: null,
            saleNotesNewId: null,
            resource_options: null,
            has_card: false,
            resource: 'pos',
            resource_documents: 'documents',
            resource_payments: 'document_payments',
            amount: 0,
            enter_amount: 0,
            difference: 0,
            button_payment: false,
            input_item: '',
            form_payment: {},
            series: [],
            all_series: [],
            cards_brand: [],
            cancel: false,
            form_cash_document: {},
            statusDocument: {},
            payment_method_types: [],
            payments: [],
            locked_submit: false
        }
    },
    async created() {

        await this.initLStoPayment()
        await this.getTables()
        this.initFormPayment()
        this.inputAmount()
        this.form.payments = []
        this.$eventHub.$on('reloadDataCardBrands', (card_brand_id) => {
            this.reloadDataCardBrands(card_brand_id)
        })

        this.$eventHub.$on('localSPayments', (payments) => {
            this.payments = payments

        })

        this.events();

        await this.setInitialAmount()

        await this.getFormPosLocalStorage()
        // console.log(this.form.payments, this.payments)
        if (!qz.websocket.isActive() && this.isPrint) {
            startConnection();
        }
    },
    mounted() {
        // console.log(this.currencyTypeActive)
    },
    methods: {
        handleFn113() {
            const code = this.form.document_type_id
            if (code == '01') {
                this.form.document_type_id = '03'
            } else if (code == '03') {
                this.form.document_type_id = '80'
            } else if (code == '80') {
                this.form.document_type_id = '01'
            }

            this.filterSeries()
        },
        keyupEnterAmount() {

            if (this.button_payment) {
                return this.$message.warning("El monto a pagar es menor al total")
            }

            if (this.locked_submit) return;

            this.clickPayment()

        },
        async setInitialAmount() {
            this.enter_amount = this.form.total
            // this.form.payments = this.payments
            // this.$eventHub.$emit('eventSetFormPosLocalStorage', this.form)
            await this.$refs.enter_amount.$el.getElementsByTagName('input')[0].focus()
            await this.$refs.enter_amount.$el.getElementsByTagName('input')[0].select()
            // console.log(this.$refs.enter_amount.$el.getElementsByTagName('input')[0])
        },
        changeEnabledDiscount() {

            if (!this.enabled_discount) {

                this.discount_amount = 0
                this.deleteDiscountGlobal()
                this.reCalculateTotal()

            }

        },
        inputDiscountAmount() {

            if (this.enabled_discount) {

                if (this.discount_amount && !isNaN(this.discount_amount) && parseFloat(this.discount_amount) > 0) {

                    if (this.discount_amount >= this.form.total)
                        return this.$message.error("El monto de descuento debe ser menor al total de venta")

                    this.deleteDiscountGlobal()
                    this.reCalculateTotal()

                } else {

                    // this.discount_amount = 0
                    this.deleteDiscountGlobal()
                    this.reCalculateTotal()

                }

                // console.log(this.discount_amount)
            }
        },
        isExonerated() {

            let not_exonerated = this.form.items.find((item) => {
                return item.affectation_igv_type_id != '20'
            })

            return (not_exonerated) ? false : true
        },
        async discountGlobal() {

            // let is_exonerated = this.isExonerated()
            // let is_exonerated = false

            let global_discount = parseFloat(this.discount_amount)

            let base = parseFloat(this.form.total)
            let amount = parseFloat(global_discount)
            let factor = _.round(amount / base, 5)

            let discount = _.find(this.form.discounts, {'discount_type_id': '03'})

            if (global_discount > 0 && !discount) {

                this.form.total_discount = _.round(amount, 2)
                this.form.total = _.round(this.form.total - amount, 2)

                this.form.discounts.push({
                    discount_type_id: '03',
                    description: 'Descuentos globales que no afectan la base imponible del IGV/IVAP',
                    factor: factor,
                    amount: amount,
                    base: base
                })

            }

            this.difference = this.enter_amount - this.form.total
            // this.difference = this.enter_amount - this.form.total_payable_amount
            // console.log(this.form.discounts)
        },
        reCalculateTotal() {

            let total_discount = 0
            let total_charge = 0
            let total_exportation = 0
            let total_taxed = 0
            let total_exonerated = 0
            let total_unaffected = 0
            let total_free = 0
            let total_igv = 0
            let total_value = 0
            let total = 0
            let total_plastic_bag_taxes = 0
            let total_base_isc = 0
            let total_isc = 0

            this.form.items.forEach((row) => {
                total_discount += parseFloat(row.total_discount)
                total_charge += parseFloat(row.total_charge)

                if (row.affectation_igv_type_id === '10') {
                    total_taxed += parseFloat(row.total_value)
                }
                if (row.affectation_igv_type_id === '20') {
                    total_exonerated += parseFloat(row.total_value)
                }
                if (row.affectation_igv_type_id === '30') {
                    total_unaffected += parseFloat(row.total_value)
                }
                if (row.affectation_igv_type_id === '40') {
                    total_exportation += parseFloat(row.total_value)
                }
                if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) < 0) {
                    total_free += parseFloat(row.total_value)
                }
                if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) > -1) {
                    total_igv += parseFloat(row.total_igv)
                    total += parseFloat(row.total)
                }
                total_value += parseFloat(row.total_value)
                total_plastic_bag_taxes += parseFloat(row.total_plastic_bag_taxes)

                // isc
                total_isc += parseFloat(row.total_isc)
                total_base_isc += parseFloat(row.total_base_isc)

            });

            // isc
            this.form.total_base_isc = _.round(total_base_isc, 2)
            this.form.total_isc = _.round(total_isc, 2)

            this.form.total_exportation = _.round(total_exportation, 2)
            this.form.total_taxed = _.round(total_taxed, 2)
            this.form.total_exonerated = _.round(total_exonerated, 2)
            this.form.total_unaffected = _.round(total_unaffected, 2)
            this.form.total_free = _.round(total_free, 2)
            this.form.total_igv = _.round(total_igv, 2)
            this.form.total_value = _.round(total_value, 2)
            // this.form.total_taxes = _.round(total_igv, 2)

            //impuestos (isc + igv)
            this.form.total_taxes = _.round(total_igv + total_isc, 2);

            this.form.total_plastic_bag_taxes = _.round(total_plastic_bag_taxes, 2)
            // this.form.total = _.round(total, 2)
            this.form.subtotal = _.round(total + this.form.total_plastic_bag_taxes, 2)
            this.form.total = _.round(total + this.form.total_plastic_bag_taxes, 2)

            this.discountGlobal()


        },
        deleteDiscountGlobal() {

            let discount = _.find(this.form.discounts, {'discount_type_id': '03'})
            let index = this.form.discounts.indexOf(discount)
            // let is_exonerated = this.isExonerated()

            if (index > -1) {
                this.form.discounts.splice(index, 1)
                this.form.total_discount = 0
                // this.setDiscountByItem(0, is_exonerated)
            }

        },
        back() {
            this.$emit('update:is_payment', false)
        },
        async initLStoPayment() {

            this.amount = await this.getLocalStoragePayment('amount', 0)
            this.enter_amount = await this.getLocalStoragePayment('enter_amount', 0)
            this.difference = await this.getLocalStoragePayment('difference', 0)
        },
        getFormPosLocalStorage() {

            let form_pos = localStorage.getItem('form_pos');
            form_pos = JSON.parse(form_pos)
            if (form_pos) {
                this.form.payments = form_pos.payments
            }

        },
        clickAddPayment() {
            this.showDialogMultiplePayment = true
        },
        reloadDataCardBrands(card_brand_id) {
            this.$http.get(`/${this.resource}/table/card_brands`).then((response) => {
                this.cards_brand = response.data
                this.form_payment.card_brand_id = card_brand_id
                this.changePaymentMethodType()
            })
        },
        getDescriptionPaymentMethodType(id) {
            let payment_method_type = _.find(this.payment_method_types, {'id': id})
            return (payment_method_type) ? payment_method_type.description : ''

        },
        changePaymentMethodType() {
            let payment_method_type = _.find(this.payment_method_types, {'id': this.form_payment.payment_method_type_id})
            this.has_card = payment_method_type.has_card
            this.form_payment.card_brand_id = (payment_method_type.has_card) ? this.form_payment.card_brand_id : null
        },
        addRow(payments) {

            this.form.payments = payments
            let acum_payment = 0

            this.form.payments.forEach((item) => {
                acum_payment += parseFloat(item.payment)
            })

            // this.amount = acum_payment
            this.setAmount(acum_payment)

            // console.log(this.form.payments)
        },
        setAmount(amount) {
            // this.amount = parseFloat(this.amount) + parseFloat(amount)
            this.amount = parseFloat(amount) //+ parseFloat(amount)
            this.enter_amount = parseFloat(amount) //+ parseFloat(amount)
            this.inputAmount()
        },
        setAmountCash(amount) {
            let row = _.last(this.payments, {'payment_method_type_id': '01'})
            row.payment = parseFloat(row.payment) + parseFloat(amount)
            // console.log(row.payment)

            this.form.payments = this.payments
            let acum_payment = 0

            this.form.payments.forEach((item) => {
                acum_payment += parseFloat(item.payment)
            })

            this.setAmount(acum_payment)

        },
        async enterAmount() {

            let r_item = await _.last(this.payments, {'payment_method_type_id': '01'})
            r_item.payment = await parseFloat(this.enter_amount)
            // console.log(r_item.payment)

            let ind = this.form.payments.length - 1
            this.form.payments[ind].payment = parseFloat(this.enter_amount)
            // this.setAmount(item.payment)

            let acum_payment = 0

            await this.form.payments.forEach((item) => {
                acum_payment += parseFloat(item.payment)
            })
            // console.log(this.form.payments)

            // this.amount = item.payment
            this.amount = acum_payment
            // this.amount = this.enter_amount
            // console.log(this.amount)
            this.difference = this.amount - this.form.total

            if (isNaN(this.difference)) {
                this.button_payment = true
                this.difference = "-"
            } else if (this.difference >= 0) {
                this.button_payment = false
                this.difference = this.amount - this.form.total
            } else {
                this.button_payment = true
            }
            this.difference = _.round(this.difference, 2)

            this.$eventHub.$emit('eventSetFormPosLocalStorage', this.form)

            await this.lStoPayment()

        },
        getLocalStoragePayment(key, re_default = null) {

            let ls_obj = localStorage.getItem(key + '_garage');
            ls_obj = JSON.parse(ls_obj)

            if (ls_obj) {
                return ls_obj
            }

            return re_default
        },
        setLocalStoragePayment(key, obj) {
            localStorage.setItem(key, JSON.stringify(obj));
        },
        inputAmount() {

            this.difference = this.amount - this.form.total

            if (isNaN(this.difference)) {
                this.button_payment = true
                this.difference = "-"
            } else if (this.difference >= 0) {
                this.button_payment = false
                this.difference = this.amount - this.form.total
            } else {
                this.button_payment = true
            }
            this.difference = _.round(this.difference, 2)
            // this.form_payment.payment = this.amount

            this.$eventHub.$emit('eventSetFormPosLocalStorage', this.form)
            this.lStoPayment()

        },
        lStoPayment() {

            this.setLocalStoragePayment('enter_amount', this.enter_amount)
            this.setLocalStoragePayment('amount', this.amount)
            // console.log(this.amount)
            this.setLocalStoragePayment('difference', this.difference)

        },
        initFormPayment() {

            this.difference = -this.form.total

            this.form_payment = {
                id: null,
                date_of_payment: moment().format('YYYY-MM-DD'),
                payment_method_type_id: '01',
                reference: null,
                card_brand_id: null,
                document_id: null,
                sale_note_id: null,
                payment: this.form.total,
            }

            this.form_cash_document = {
                document_id: null,
                sale_note_id: null
            }

        },

        filterSeries() {
            this.form.series_id = null
            this.series = _.filter(this.all_series, {'document_type_id': this.form.document_type_id});
            this.form.series_id = (this.series.length > 0) ? this.series[0].id : null

            if (!this.form.series_id) {
                return this.$message.warning('El establecimiento no tiene series disponibles para el comprobante');
            }
        },
        async clickCancel() {

            this.loading_submit = true
            await this.sleep(800);
            this.loading_submit = false
            this.cleanLocalStoragePayment()
            this.$eventHub.$emit('cancelSale')
            //console.info('cli cancel fas_payment')

        },
        async events() {
            await this.$eventHub.$on("cancelSale", () => {
                this.initLStoPayment()
                this.getTables()
                this.initFormPayment()
                this.inputAmount()
                this.form.payments = []
                this.$eventHub.$on('reloadDataCardBrands', (card_brand_id) => {
                    this.reloadDataCardBrands(card_brand_id)
                })

                this.$eventHub.$on('localSPayments', (payments) => {
                    this.payments = payments
                });

                this.setInitialAmount()

                this.getFormPosLocalStorage()
            });
        },
        cleanLocalStoragePayment() {

            this.setLocalStoragePayment('amount', null)
            this.setLocalStoragePayment('enter_amount', null)
            this.setLocalStoragePayment('difference', null)
        },
        sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },
        async asignPlateNumberToItems() {
            if (this.form.plate_number) {

                await this.form.items.forEach(item => {

                    let at = _.find(item.attributes, {'attribute_type_id': '5010'})

                    if (!at) {
                        item.attributes.push({
                            attribute_type_id: '7000',
                            description: "Gastos Art. 37 Renta:  Número de Placa",
                            value: this.form.plate_number,
                            start_date: null,
                            end_date: null,
                            duration: null,
                        })
                    }
                });
            }
        },
        async clickPayment() {
            // if(this.has_card && !this.form_payment.card_brand_id) return this.$message.error('Seleccione una tarjeta');

            if(this.businessTurns.active) {
                if(this.form.document_type_id == '01' && !this.form.plate_number) {
                    return this.$message.warning('Debe ingresar placa');
                }
            }

            if(this.rowsItems < 1) {
                return this.$message.warning('Debe agregar productos');
            }

            if (!moment(moment().format("YYYY-MM-DD")).isSame(this.form.date_of_issue)) {
                return this.$message.error('La fecha de emisión no coincide con la del día actual');
            }

            if (!this.form.series_id) {
                return this.$message.warning('El establecimiento no tiene series disponibles para el comprobante');
            }

            if (this.form.document_type_id === "80") {
                this.form.prefix = "NV";
                this.form.paid = 1;
                this.resource_documents = "sale-notes";
                this.resource_payments = "sale_note_payments";
                this.resource_options = this.resource_documents;
            } else {
                this.form.prefix = null;
                this.resource_documents = "documents";
                this.resource_payments = "document_payments";
                this.resource_options = this.resource_documents;
                await this.asignPlateNumberToItems()
            }

            this.loading_submit = true
            this.locked_submit = true

            await this.$http.post(`/${this.resource_documents}`, this.form).then(response => {
                if (response.data.success) {

                    if (this.form.document_type_id === "80") {

                        // this.form_payment.sale_note_id = response.data.data.id;
                        this.form_cash_document.sale_note_id = response.data.data.id;

                    } else {

                        // this.form_payment.document_id = response.data.data.id;
                        this.form_cash_document.document_id = response.data.data.id;
                        this.statusDocument = response.data.data.response

                    }

                    this.documentNewId = response.data.data.id;
                    this.showDialogOptions = true;

                    // this.savePaymentMethod();
                    this.saveCashDocument();

                    // this.initFormPayment() ;
                    this.cleanLocalStoragePayment()
                    if(this.isPrint){
                        this.gethtml();
                    }
                    this.$eventHub.$emit('saleSuccess');
                } else {
                    this.$message.error(response.data.message);
                }
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data;
                } else {
                    this.$message.error(error.response.data.message);
                }
            }).then(() => {
                this.loading_submit = false;
                this.locked_submit = false
            });
        },
        gethtml(){
            this.form.datahtml="";
            var doc='salenote';
            var route = `/printticket/document/${this.documentNewId}/ticket`;
            if(this.resource_documents!=='documents'){
                route = `/sale-notes/ticket/${this.documentNewId}/ticket`;
            }

            // console.log(route);

            this.$http.get(route)
            .then(response => {
                if (response.data.length>0) {
                    this.form.datahtml=response.data;
                    this.printticket();
                }

            })
            .catch(error => {
                console.log(error);
            })
        },
        async printticket(){
            //getUpdatedConfig();
            await this.sleep(400);
            var configg = getUpdatedConfig();
            var opts = getUpdatedConfig();
            var printData = [
                {
                    type: 'html',
                    format: 'plain',
                    data: this.form.datahtml,
                    options: opts
                }
            ];
            qz.print(configg, printData).catch(displayError);
        },
        saveCashDocument() {
            this.$http.post(`/cash/cash_document`, this.form_cash_document)
                .then(response => {
                    if (response.data.success) {
                        // console.log(response)
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },
        savePaymentMethod() {
            this.$http.post(`/${this.resource_payments}`, this.form_payment)
                .then(response => {
                    if (response.data.success) {
                        // console.log(response)
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.records[index].errors = error.response.data;
                    } else {
                        console.log(error);
                    }
                })
        },
        getTables() {
            this.$http.get(`/${this.resource}/payment_tables`)
                .then(response => {
                    this.all_series = response.data.series
                    this.payment_method_types = response.data.payment_method_types
                    this.cards_brand = response.data.cards_brand
                    this.filterSeries()
                })

        },
    }
}
</script>
