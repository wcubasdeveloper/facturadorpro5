<template>
    <div>
        <el-dialog
            :close-on-click-modal="false"
            :close-on-press-escape="false"
            :show-close="false"
            :title="titleDialog"
            :visible="showDialog"
            width="50%"
            @open="create"
        >
            <div v-show="!showGenerate" class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 text-center font-weight-bold">
                    <p>Imprimir A4</p>
                    <button
                        class="btn btn-lg btn-info waves-effect waves-light"
                        type="button"
                        @click="clickToPrint('a4')"
                    >
                        <i class="fa fa-file-alt"></i>
                    </button>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 text-center font-weight-bold">
                    <p>Imprimir A5</p>
                    <button
                        class="btn btn-lg btn-info waves-effect waves-light"
                        type="button"
                        @click="clickToPrint('a5')"
                    >
                        <i class="fa fa-file-alt"></i>
                    </button>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 text-center font-weight-bold">
                    <p>Imprimir Ticket</p>
                    <button
                        class="btn btn-lg btn-info waves-effect waves-light"
                        type="button"
                        @click="clickToPrint('ticket')"
                    >
                        <i class="fa fa-receipt"></i>
                    </button>
                </div>
            </div>
            <br/>
            <div v-show="!showGenerate" class="row">
                <div class="col-md-12">
                    <el-input v-model="customer_email">
                        <el-button
                            slot="append"
                            :loading="loading"
                            icon="el-icon-message"
                            @click="clickSendEmail"
                        >Enviar
                        </el-button>
                    </el-input>
                    <!--<small class="form-control-feedback" v-if="errors.customer_email" v-text="errors.customer_email[0]"></small> -->
                </div>
            </div>
            <div v-show="!showGenerate" class="row mt-3">
                <div class="col-md-12">
                    <el-input v-model="form.customer_telephone">
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
                    <small v-if="errors.customer_telephone"
                           class="form-control-feedback"
                           v-text="errors.customer_telephone[0]"></small>
                </div>
            </div>

            <br/>

            <div v-if="typeUser == 'admin'" class="row">
                <div v-show="!showGenerate" class="col-md-9">
                    <div class="form-group">
                        <el-checkbox v-model="generate">Generar comprobante electrónico</el-checkbox>
                    </div>
                </div>
            </div>
            <div v-if="generate" class="row">
                <div class="col-lg-12 pb-2">
                    <div class="form-group">
                        <label class="control-label font-weight-bold text-info">Cliente</label>
                        <el-select
                            v-model="document.customer_id"
                            :loading="loading_search"
                            :remote-method="searchRemoteCustomers"
                            class="border-left rounded-left border-info"
                            dusk="customer_id"
                            filterable
                            placeholder="Escriba el nombre o número de documento del cliente"
                            popper-class="el-select-customers"
                            remote
                            @change="changeCustomer"
                        >
                            <el-option
                                v-for="option in customers"
                                :key="option.id"
                                :label="option.description"
                                :value="option.id"
                            ></el-option>
                        </el-select>
                        <small
                            v-if="errors.customer_id"
                            class="form-control-feedback"
                            v-text="errors.customer_id[0]"
                        ></small>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div :class="{'has-danger': errors.document_type_id}" class="form-group">
                        <label class="control-label">Tipo comprobante</label>
                        <el-select
                            v-model="document.document_type_id"
                            class="border-left rounded-left border-info"
                            dusk="document_type_id"
                            popper-class="el-select-document_type"
                            @change="changeDocumentType"
                        >
                            <el-option
                                v-for="option in document_types"
                                :key="option.id"
                                :label="option.description"
                                :value="option.id"
                            ></el-option>
                            <el-option key="nv" label="NOTA DE VENTA" value="nv"></el-option>
                        </el-select>
                        <small
                            v-if="errors.document_type_id"
                            class="form-control-feedback"
                            v-text="errors.document_type_id[0]"
                        ></small>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div :class="{'has-danger': errors.series_id}" class="form-group">
                        <label class="control-label">Serie</label>
                        <el-select v-model="document.series_id">
                            <el-option
                                v-for="option in series"
                                :key="option.id"
                                :label="option.number"
                                :value="option.id"
                            ></el-option>
                        </el-select>
                        <small
                            v-if="errors.series_id"
                            class="form-control-feedback"
                            v-text="errors.series_id[0]"
                        ></small>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div :class="{'has-danger': errors.payment_condition_id}" class="form-group">
                        <label class="control-label">Condición de pago</label>
                        <el-select v-model="document.payment_condition_id" dusk="document_type_id"
                                   popper-class="el-select-document_type" style="max-width: 200px;"
                                   @change="changePaymentCondition">
                            <el-option label="Crédito con cuotas" value="03"></el-option>
                            <el-option label="Crédito" value="02"></el-option>
                            <el-option label="Contado" value="01"></el-option>
                        </el-select>
                        <small
                            v-if="errors.date_of_due"
                            class="form-control-feedback"
                            v-text="errors.date_of_due[0]"
                        ></small>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div :class="{'has-danger': errors.date_of_issue}" class="form-group">
                        <label class="control-label">Fecha de emisión</label>
                        <el-date-picker
                            v-model="document.date_of_issue"
                            :clearable="false"

                            type="date"
                            value-format="yyyy-MM-dd"
                            @change="changeDateOfIssue"
                        ></el-date-picker>
                        <small
                            v-if="errors.date_of_issue"
                            class="form-control-feedback"
                            v-text="errors.date_of_issue[0]"
                        ></small>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div :class="{'has-danger': errors.date_of_issue}" class="form-group">
                        <label class="control-label">Fecha de vencimiento</label>
                        <el-date-picker
                            v-model="document.date_of_due"
                            :clearable="false"
                            type="date"
                            value-format="yyyy-MM-dd"
                        ></el-date-picker>
                        <small
                            v-if="errors.date_of_due"
                            class="form-control-feedback"
                            v-text="errors.date_of_due[0]"
                        ></small>
                    </div>
                </div>

                <br/>
                <div class="col-lg-4" v-if="document.document_type_id == '03'">
                    <div v-show="document.document_type_id == '03'" class="form-group">
                        <el-checkbox
                            v-model="document.is_receivable"
                            class="font-weight-bold"
                        >¿Es venta por cobrar?
                        </el-checkbox>
                    </div>
                </div>
                <br/>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group" :class="{'has-danger': errors.purchase_order}">
                        <label class="control-label">Orden de Compra</label>
                        <el-input v-model="document.purchase_order">
                        </el-input>
                        <small
                            class="form-control-feedback"
                            v-if="errors.purchase_order"
                            v-text="errors.purchase_order[0]"
                        ></small>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                <div class="form-group" :class="{'has-danger': errors.additional_information}">
                        <label class="control-label">Observaciones</label>
                        <el-input v-model="document.additional_information">
                        </el-input>
                        <small
                            class="form-control-feedback"
                            v-if="errors.additional_information"
                            v-text="errors.additional_information[0]"
                        ></small>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">

                    <div :class="{'has-danger': errors.seller_id}" class="form-group">
                        <label class="control-label">Vendedor</label>
                        <el-select v-model="document.seller_id" clearable>
                            <el-option v-for="option in sellers" :key="option.id" :label="option.name"
                                       :value="option.id">{{ option.name }}
                            </el-option>
                        </el-select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 mt-3 mb-3" v-if="show_has_retention">
                    <div class="form-group">
                        <label class="control-label">¿Tiene retención de igv?</label>
                        <el-switch v-model="document.has_retention" @change="changeRetention"></el-switch>
                    </div>
                </div>

                <template v-if="is_document_type_invoice">
                    <!-- Crédito con cuotas -->
                    <div v-show=" document.payment_condition_id === '03'" class="col-lg-12">
                        <table v-if="document.fee.length>0" width="100%">
                            <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th style="width: 30px">
                                    <a
                                        class="text-center font-weight-bold text-center text-info"
                                        href="#"
                                        style="font-size:18px"
                                        @click.prevent="clickAddFee"
                                    >[+]</a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in document.fee"
                                :key="index">
                                <td v-if="document.fee.length>0">
                                    <div class="form-group mb-2 mr-2">
                                        <el-date-picker v-model="row.date"
                                                        :clearable="false"
                                                        format="dd/MM/yyyy"
                                                        type="date"
                                                        value-format="yyyy-MM-dd"></el-date-picker>
                                    </div>
                                </td>
                                <td v-if="document.fee.length>0">
                                    <div class="form-group mb-2 mr-2">
                                        <el-input v-model="row.amount"></el-input>
                                    </div>
                                </td>
                                <td class="series-table-actions text-center">
                                    <button
                                        class="btn waves-effect waves-light btn-xs btn-danger"
                                        type="button"
                                        @click.prevent="clickRemoveFee(index)"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Credito -->
                    <div v-show=" document.payment_condition_id === '02'" class="col-lg-12">
                        <table v-if="document.fee.length>0" width="100%">
                            <thead>
                            <tr>
                                <th>
                                    Método de pago
                                </th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th style="width: 30px">
                                    <a
                                        class="text-center font-weight-bold text-center text-info"
                                        href="#"
                                        style="font-size:18px"
                                        @click.prevent="clickAddFeeNew"
                                    >[+]</a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in document.fee" :key="index">


                                <td>
                                    <el-select
                                        v-model="row.payment_method_type_id"
                                        @change="changePaymentMethodType(index)">
                                        <el-option
                                            v-for="option in credit_payment_metod"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                </td>

                                <td v-if="document.fee.length>0">
                                    <div class="form-group mb-2 mr-2">
                                        <el-date-picker v-model="row.date" :clearable="false"
                                                        format="dd/MM/yyyy"
                                                        type="date"
                                                        value-format="yyyy-MM-dd"></el-date-picker>
                                    </div>
                                </td>
                                <td v-if="document.fee.length>0">
                                    <div class="form-group mb-2 mr-2">
                                        <el-input v-model="row.amount"></el-input>
                                    </div>
                                </td>
                                <td class="series-table-actions text-center">
                                    <button
                                        class="btn waves-effect waves-light btn-xs btn-danger"
                                        type="button"
                                        @click.prevent="clickRemoveFee(index)"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Contado -->
                    <div v-show="document.payment_condition_id == '01'" class="col-lg-12">
                        <table>
                            <thead>
                            <tr width="100%">
                                <th v-if="document.payments.length>0">F.Pago</th>
                                <th v-if="document.payments.length>0">M.Pago</th>
                                <th v-if="document.payments.length>0">Destino</th>
                                <th v-if="document.payments.length>0">Referencia</th>
                                <th v-if="document.payments.length>0">Monto</th>
                                <th width="5%">
                                    <a
                                        class="text-center font-weight-bold text-center text-info"
                                        href="#"
                                        style="font-size:18px"
                                        @click.prevent="clickAddPayment"
                                    >[+]</a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in document.payments" :key="index">
                                <td>
                                    <el-date-picker
                                        v-model="row.date_of_payment"
                                        :clearable="false"
                                        type="date"
                                        value-format="yyyy-MM-dd"
                                    ></el-date-picker>
                                </td>
                                <td>
                                    <el-select
                                        v-model="row.payment_method_type_id"
                                        @change="changePaymentMethodType(index)">
                                        <el-option
                                            v-for="option in cash_payment_metod"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"></el-option>
                                    </el-select>
                                </td>
                                <td>
                                    <div class="form-group mb-2 mr-2">
                                        <el-select
                                            v-model="row.payment_destination_id"
                                            :disabled="row.payment_destination_disabled"
                                            filterable
                                        >
                                            <el-option
                                                v-for="option in payment_destinations"
                                                :key="option.id"
                                                :label="option.description"
                                                :value="option.id"
                                            ></el-option>
                                        </el-select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group mb-2 mr-2">
                                        <el-input v-model="row.reference"></el-input>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group mb-2 mr-2">
                                        <el-input v-model="row.payment"></el-input>
                                    </div>
                                </td>
                                <td class="series-table-actions text-center">
                                    <button
                                        class="btn waves-effect waves-light btn-xs btn-danger"
                                        type="button"
                                        @click.prevent="clickCancel(index)"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                                <br/>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>

            <series-form v-if="generate && form.quotation" :items="form.quotation.items" :config="config"></series-form>
            <div  v-show="document.total > 0" class="col-lg-12">
                <div class="form-group pull-right">
                    <label class="control-label"> Total </label> <br>
                    <label class="control-label">{{ document.currency_type_id }} {{ this.document.tota }}</label>
                </div>

                <br>
            </div>
            <span slot="footer" class="dialog-footer">
                <template v-if="showClose">
                    <el-button @click="clickClose">Cerrar</el-button>
                    <el-button
                        v-if="generate"
                        :loading="loading_submit"
                        class="submit"
                        type="primary"
                        @click="submit"
                    >Generar</el-button>
                </template>
                <template v-else>
                    <el-button
                        v-if="generate"
                        :loading="loading_submit"
                        class="submit"
                        plain
                        type="primary"
                        @click="submit"
                    >Generar comprobante</el-button>
                    <el-button v-else @click="clickFinalize">Ir al listado</el-button>
                    <el-button type="primary" @click="clickNewQuotation">Nueva cotización</el-button>
                </template>
            </span>
        </el-dialog>

        <document-options
            :isContingency="false"
            :recordId="documentNewId"
            :showClose="true"
            :showDialog.sync="showDialogDocumentOptions"
        ></document-options>

        <sale-note-options
            :recordId="documentNewId"
            :showClose="true"
            :showDialog.sync="showDialogSaleNoteOptions"
        ></sale-note-options>
    </div>
</template>

<script>
import DocumentOptions from "../../documents/partials/options.vue";
import SaleNoteOptions from "../../sale_notes/partials/options.vue";
import SeriesForm from "./series_form.vue";
import moment from "moment";
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    components: {DocumentOptions, SaleNoteOptions, SeriesForm},

    props: [
        "showDialog",
        "recordId",
        "showClose",
        "showGenerate",
        "type",
        "typeUser",
    ],
    computed:{
        ...mapState([
            'config',
            'payment_method_types'
        ]),
        credit_payment_metod: function () {
            return _.filter(this.payment_method_types, {'is_credit': true})
        },
        cash_payment_metod: function () {
            return _.filter(this.payment_method_types, {'is_credit': false})
        },
        isCreditPaymentCondition: function () {
            return ['02', '03'].includes(this.document.payment_condition_id)
        },
    },
    data() {
        return {
            customer_email: "",
            titleDialog: null,
            loading: false,
            resource: "quotations",
            resource_documents: "documents",
            errors: {},
            form: {},
            document: {},
            document_types: [],
            all_document_types: [],
            all_series: [],
            series: [],
            customers: [],
            generate: false,
            loading_submit: false,
            showDialogDocumentOptions: false,
            showDialogSaleNoteOptions: false,
            documentNewId: null,
            is_document_type_invoice: true,
            payment_destinations: [],
            loading_search: false,
            // payment_method_types: [],
            sellers: [],
            show_has_retention: true,
        };
    },
    created() {
        this.loadConfiguration()
        this.initForm();
        this.initDocument();
        this.clickAddPayment();
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        clickSendWhatsapp() {
            if (!this.form.customer_telephone) {
                return this.$message.error('El número es obligatorio')
            }
            window.open(`https://wa.me/51${this.form.customer_telephone}?text=${this.form.message_text}`, '_blank');
        },
        
        validateCustomerRetention(identity_document_type_id) {

            if (identity_document_type_id != '6') {

                this.disabledRetention()

            } else {
                this.show_has_retention = true
            }

        },
        disabledRetention(){
            
            if (this.document.has_retention) {
                this.document.has_retention = false
                this.changeRetention()
            }

            this.show_has_retention = false

        },
        changeRetention() {

            if (this.document.has_retention) {

                let base = this.document.total
                let percentage = _.round(parseFloat(this.config.igv_retention_percentage) / 100, 5)
                let amount = _.round(base * percentage, 2)

                this.document.retention = {
                    base: base,
                    code: '62', //Código de Retención del IGV
                    amount: amount,
                    percentage: percentage
                }

                this.setTotalPendingAmountRetention(amount)

            } else {

                this.document.retention = {}
                this.document.total_pending_payment = 0
                this.calculateAmountToPayments()
            }

        },
        setTotalPendingAmountRetention(amount) {

            //monto neto pendiente aplica si la condicion de pago es credito
            this.document.total_pending_payment = ['02', '03'].includes(this.document.payment_condition_id) ? this.document.total - amount : 0
            this.calculateAmountToPayments()

        },
        calculateAmountToPayments() {
            this.calculateFee()
        },
        changePaymentCondition() {
            this.document.fee = [];
            this.document.payments = [];
            if (this.document.payment_condition_id === '01') {
                this.document.payments = this.form.quotation.payments;
                if (this.document.payments === undefined || this.document.payments.length < 1) {
                    this.clickAddPayment();
                }
            }
            if (this.document.payment_condition_id === '02') {
                this.document.fee = this.form.quotation.fee;
                if (this.document.fee === undefined || this.document.fee.length < 1) {
                    this.clickAddFeeNew();
                }
            }
            if (this.document.payment_condition_id === '03') {
                this.document.fee = this.form.quotation.fee;
                if (this.document.fee === undefined || this.document.fee.length < 1) {
                    this.clickAddFee();
                }
            }

            if (!_.isEmpty(this.document.retention)) {
                this.setTotalPendingAmountRetention(this.document.retention.amount)
            }

        },
        clickRemoveFee(index) {
            this.document.fee.splice(index, 1);
            this.calculateFee();
        },
        clickAddFeeNew() {
            let first = {
                id: '05',
                number_days: 0,
            };
            if (this.credit_payment_metod[0] !== undefined) {
                first = this.credit_payment_metod[0];
            }

            let date = moment(this.form.date_of_issue).add(first.number_days, 'days').format('YYYY-MM-DD')
            if (this.document.fee === undefined) this.document.fee = [];
            this.document.fee.push({
                id: null,
                payment_method_type_id: first.id,
                date: date,
                currency_type_id: this.document.currency_type_id,
                amount: 0,
            });
            this.calculateFee();
        },
        clickAddFee() {
            if (this.document.fee === undefined) this.document.fee = [];
            this.document.fee.push({
                id: null,
                date: moment().format('YYYY-MM-DD'),
                currency_type_id: this.document.currency_type_id,
                amount: 0,
            });
            this.calculateFee();
        },
        calculateFee() {
            let fee_count = this.document.fee.length;
            // let total = this.document.total;
            let total = this.getTotal()

            let accumulated = 0;
            let amount = _.round(total / fee_count, 2);
            _.forEach(this.document.fee, row => {
                accumulated += amount;
                if (total - accumulated < 0) {
                    amount = _.round(total - accumulated + amount, 2);
                }
                row.amount = amount;
            })
        },
        getTotal() {

            if (!_.isEmpty(this.document.retention) && this.document.total_pending_payment > 0) {
                return this.document.total_pending_payment
            }

            return this.document.total
        },
        clickCancel(index) {
            this.document.payments.splice(index, 1);
        },
        clickAddPayment() {
            let id = '01';
                if (this.cash_payment_metod !== undefined &&
                this.cash_payment_metod[0] !== undefined) {
                id = this.cash_payment_metod[0].id
            }

            if (this.document.payments === undefined) this.document.payments = [];
            let total = 0
            if(
                this.form !== null &&
                this.form.quotation !== null &&
                this.form.quotation.total !== null
            ){
                total =this.form.quotation.total
            }
            this.document.payments.push({
                id: null,
                document_id: null,
                date_of_payment: moment().format("YYYY-MM-DD"),
                payment_method_type_id: id,
                payment_destination_id: null,
                reference: null,
                payment: parseFloat(total),
            });

        },
        initForm() {
            this.generate = this.showGenerate ? true : false;
            this.errors = {};
            this.form = {
                id: null,
                external_id: null,
                identifier: null,
                date_of_issue: null,
                quotation: null,
            };
        },
        getCustomer() {
            this.$http
                .get(
                    `/${this.resource_documents}/search/customer/${this.form.quotation.customer_id}`
                )
                .then((response) => {
                    this.customers = response.data.customers;
                    this.document.customer_id = this.form.quotation.customer_id;
                    this.changeCustomer();
                });
        },
        changeCustomer() {
            this.validateIdentityDocumentType();
        },
        searchRemoteCustomers(input) {
            if (input.length > 0) {
                this.loading_search = true;
                let parameters = `input=${input}&document_type_id=${this.form.document_type_id}&operation_type_id=${this.form.operation_type_id}`;

                this.$http
                    .get(`/${this.resource}/search/customers?${parameters}`)
                    .then((response) => {
                        this.customers = response.data.customers;
                        this.loading_search = false;
                    });
            }
        },
        initDocument() {
            this.document = {
                document_type_id: null,
                series_id: null,
                establishment_id: null,
                number: "#",
                date_of_issue: moment().format("YYYY-MM-DD"),
                time_of_issue: null,
                customer_id: null,
                currency_type_id: null,
                purchase_order: null,
                exchange_rate_sale: 0,
                total_prepayment: 0,
                total_charge: 0,
                total_discount: 0,
                total_exportation: 0,
                total_free: 0,
                total_taxed: 0,
                payment_condition_id: '01',
                fee: [],
                total_unaffected: 0,
                total_exonerated: 0,
                total_igv: 0,
                total_igv_free: 0,
                total_base_isc: 0,
                total_isc: 0,
                total_base_other_taxes: 0,
                total_other_taxes: 0,
                total_taxes: 0,
                total_value: 0,
                subtotal: 0,
                total: 0,
                operation_type_id: null,
                date_of_due: moment().format("YYYY-MM-DD"),
                items: [],
                charges: [],
                discounts: [],
                attributes: [],
                guides: [],
                additional_information: null,
                actions: {
                    format_pdf: "a4",
                },
                quotation_id: null,
                is_receivable: false,
                payments: [],
                hotel: {},
                
                total_pending_payment: 0,
                has_retention: false,
                retention: {},

            };
        },
        changeDateOfIssue() {
            this.document.date_of_due = this.document.date_of_issue;
        },
        resetDocument() {
            this.generate = this.showGenerate ? true : false;
            this.initDocument();
            this.document.document_type_id =
                this.document_types.length > 0
                    ? this.document_types[0].id
                    : null;
            this.changeDocumentType();
        },
        validatePaymentDestination() {

            let error_by_item = 0

            this.document.payments.forEach((item) => {
                if (item.payment_destination_id == null) error_by_item++;
            })

            return {
                error_by_item: error_by_item,
            }

        },
        async submit() {

            let validate_items = await this.validateQuantityandSeries();
            if (!validate_items.success)
                return this.$message.error(validate_items.message);

            await this.assignDocument();

            let validate_payment_destination = await this.validatePaymentDestination()

            if (validate_payment_destination.error_by_item > 0) {
                return this.$message.error('El destino del pago es obligatorio');
            }

            this.loading_submit = true;
            if (this.document.document_type_id === "nv") {
                this.document.prefix = "NV";
                this.resource_documents = "sale-notes";
            } else {
                this.document.prefix = null;
                this.resource_documents = "documents";
            }
            // Condicion de pago Credito con cuota pasa a credito
            if (this.document.payment_condition_id === '03') this.document.payment_condition_id = '02';
            this.$http
                .post(`/${this.resource_documents}`, this.document)
                .then((response) => {
                    if (response.data.success) {
                        this.documentNewId = response.data.data.id;

                        this.$http
                            .get(`/${this.resource}/changed/${this.form.id}`)
                            .then(() => {
                                this.$eventHub.$emit("reloadData");
                            });

                        const payloadCash = {
                            document_id: null,
                            sale_note_id: null,
                        }

                        if (this.document.document_type_id === "nv") {
                            this.showDialogSaleNoteOptions = true;
                            payloadCash.sale_note_id = this.documentNewId;
                        } else {
                            this.showDialogDocumentOptions = true;
                            payloadCash.document_id = this.documentNewId;
                        }

                        this.getRecord()
                        this.$eventHub.$emit("reloadData");
                        this.resetDocument();
                        this.saveCashDocument(payloadCash);
                        this.document.customer_id = this.form.quotation.customer_id;
                        this.changeCustomer();
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        this.$message.error(error.response.data.message);
                    }
                })
                .finally(() => {
                    this.loading_submit = false;
                });
        },
        assignDocument() {
            let q = this.form.quotation;

            this.document.establishment_id = q.establishment_id;
            // this.document.date_of_issue = q.date_of_issue
            this.document.time_of_issue = moment().format("HH:mm:ss");
            // this.document.customer_id = q.customer_id
            this.document.currency_type_id = q.currency_type_id;
            if(q.purchase_order !== undefined && q.purchase_order != null) {
                this.document.purchase_order = q.purchase_order;
            }
            this.document.exchange_rate_sale = q.exchange_rate_sale;
            this.document.total_prepayment = q.total_prepayment;
            this.document.total_charge = q.total_charge;
            this.document.total_discount = q.total_discount;
            this.document.total_exportation = q.total_exportation;
            this.document.total_free = q.total_free;
            this.document.total_taxed = q.total_taxed;
            this.document.total_unaffected = q.total_unaffected;
            this.document.total_exonerated = q.total_exonerated;
            this.document.total_igv = q.total_igv;
            this.document.total_igv_free = q.total_igv_free;
            this.document.total_base_isc = q.total_base_isc;
            this.document.total_isc = q.total_isc;
            this.document.total_base_other_taxes = q.total_base_other_taxes;
            this.document.total_other_taxes = q.total_other_taxes;
            this.document.total_taxes = q.total_taxes;
            this.document.total_value = q.total_value;
            this.document.subtotal = q.subtotal;
            this.document.total = q.total;
            this.document.operation_type_id = "0101";
            // this.document.date_of_due = q.date_of_issue
            this.document.items = q.items;
            this.document.charges = q.charges;
            this.document.discounts = q.discounts;
            this.document.attributes = [];
            // this.document.payments = q.payments;
            this.document.guides = q.guides;
            if(q.additional_information !== undefined && q.additional_information  != null) {
                this.document.additional_information = q.additional_information;
            }
            if(q.seller_id !== undefined && q.seller_id  != null) {
                this.document.seller_id = q.seller_id;
            }
            this.document.actions = {
                format_pdf: "a4",
            };
            this.document.quotation_id = this.form.id;
            _.forEach(this.document.items, row => {
                row.name_product_pdf = row.item.name_product_pdf;
            })
        },
        async create() {
            await this.$http
                .get(`/${this.resource}/option/tables`)
                .then((response) => {
                    this.all_document_types =
                        response.data.document_types_invoice;
                    this.all_series = response.data.series;
                    this.payment_destinations =
                        response.data.payment_destinations;
                    this.$store.commit('setPaymentMethodTypes', response.data.payment_method_types)
                    // this.payment_method_types = response.data.payment_method_types ;
                    this.sellers = response.data.sellers
                    // this.document.document_type_id = (this.all_document_types.length > 0)?this.all_document_types[0].id:null
                    // this.changeDocumentType()
                });

            await this.getRecord()
        },
        async getRecord(){

            await this.$http
                        .get(`/${this.resource}/record2/${this.recordId}`)
                        .then((response) => {
                            this.form = response.data.data;
                            this.document.payments =
                                response.data.data.quotation.payments;
                            this.document.total = this.form.quotation.total;
                            this.document.currency_type_id = this.form.quotation.currency_type_id;
                            this.document.payment_condition_id = this.form.quotation.payment_condition_id;
                            if (this.document.payment_condition_id === undefined || this.document.payments.length > 0) {
                                this.document.payment_condition_id = "01";
                            }

                            // console.log(this.form)
                            // this.validateIdentityDocumentType()
                            this.getCustomer();
                            let type = this.type == "edit" ? "editada" : "registrada";
                            this.titleDialog =
                                `Cotización ${type}: ` + this.form.identifier;
                        })
        },
        changeDocumentType() {
            // this.filterSeries()
            this.document.is_receivable = false;
            this.series = [];

            if (this.document.document_type_id !== "nv") {
                this.filterSeries();
                this.is_document_type_invoice = true;

                this.show_has_retention = true

            } else {
                this.series = _.filter(this.all_series, {
                    document_type_id: "80",
                });
                this.document.series_id =
                    this.series.length > 0 ? this.series[0].id : null;

                this.is_document_type_invoice = false;

                this.disabledRetention()
            }
        },
        async validateIdentityDocumentType() {
            let identity_document_types = ["0", "1"];
            // console.log(this.document)
            let customer = _.find(this.customers, {
                id: this.document.customer_id,
            });

            if (
                identity_document_types.includes(
                    customer.identity_document_type_id
                )
            ) {
                this.document_types = _.filter(this.all_document_types, {
                    id: "03",
                });
            } else {
                this.document_types = this.all_document_types;
            }

            this.document.document_type_id =
                this.document_types.length > 0
                    ? this.document_types[0].id
                    : null;
            await this.changeDocumentType();

            
            // retencion para clientes con ruc
            this.validateCustomerRetention(customer.identity_document_type_id)

        },
        filterSeries() {
            this.document.series_id = null;
            this.series = _.filter(this.all_series, {
                document_type_id: this.document.document_type_id,
            });
            this.document.series_id =
                this.series.length > 0 ? this.series[0].id : null;
        },
        clickFinalize() {
            location.href = `/${this.resource}`;
        },
        clickNewQuotation() {
            this.clickClose();
        },
        clickClose() {
            this.$emit("update:showDialog", false);
            this.initForm();
            this.resetDocument();
        },
        clickToPrint(format) {
            // Si no hay external id, no hará nada.
            if(this.form.external_id == null) return null;
            window.open(
                `/${this.resource}/print/${this.form.external_id}/${format}`,
                "_blank"
            );
        },
        clickSendEmail() {
            this.loading = true;
            this.$http
                .post(`/${this.resource}/email`, {
                    customer_email: this.customer_email,
                    id: this.form.id,
                    customer_id: this.form.quotation.customer_id,
                })
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(
                            "El correo fue enviado satisfactoriamente"
                        );
                    } else {
                        this.$message.error("Error al enviar el correo");
                    }
                })
                .catch((error) => {
                    this.$message.error("Error al enviar el correo");
                })
                .then(() => {
                    this.loading = false;
                });
        },
        async validateQuantityandSeries() {
            let error = 0;
            await this.form.quotation.items.forEach((element) => {
                if (element.item.series_enabled) {
                    const select_lots = _.filter(element.item.lots, {
                        has_sale: true,
                    }).length;
                    if (select_lots != element.quantity) error++;
                }
            });
            if (error > 0)
                return {
                    success: false,
                    message:
                        "Las cantidades y series seleccionadas deben ser iguales.",
                };

            return {success: true};
        },
        changePaymentMethodType(index) {
            let id = '01';
            if (this.document.payments[index] !== undefined &&
                this.document.payments[index].payment_method_type_id !== undefined) {
                id = this.document.payments[index].payment_method_type_id;
            } else if (this.document.fee[index] !== undefined &&
                this.document.fee[index].payment_method_type_id !== undefined) {
                id = this.document.fee[index].payment_method_type_id;
            }
            let payment_method_type = _.find(this.payment_method_types, {'id': id});

            if (payment_method_type.number_days) {

                this.document.date_of_due = moment(this.document.date_of_issue).add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')
                // this.document.payments = []
                this.enabled_payments = false
                this.readonly_date_of_due = true
                this.document.payment_method_type_id = payment_method_type.id

                let date = moment(this.document.date_of_issue).add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')

                // let date = moment()
                //     .add(payment_method_type.number_days, 'days')
                //     .format('YYYY-MM-DD')

                if (this.document.fee !== undefined) {
                    for (let index = 0; index < this.document.fee.length; index++) {
                        this.document.fee[index].date = date;
                    }
                }

            } else if (payment_method_type.id == '09') {

                this.document.payment_method_type_id = payment_method_type.id
                this.document.date_of_due = this.document.date_of_issue
                // this.document.payments = []
                this.enabled_payments = false

            } else {

                this.document.date_of_due = this.document.date_of_issue
                this.readonly_date_of_due = false
                this.document.payment_method_type_id = null
                this.enabled_payments = true

            }

        },
        async saveCashDocument(payload){
            if(!this.id){
                await this.$http.post(`/cash/cash_document`, payload)
                    .then(response => {
                        if (response.data.success) {
                        } else {
                            this.$message.error(response.data.message);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }
        },

    },
};
</script>
