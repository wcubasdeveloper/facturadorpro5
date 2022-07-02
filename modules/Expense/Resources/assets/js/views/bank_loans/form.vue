<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Nuevo Prestamo Bancario</h3>
        </div>
        <div class="tab-content">
            <form autocomplete="off"
                  @submit.prevent="submit">
                <div class="form-body">


                    <div class="row">
                        <!--                                     Institución Financiera -->
                        <!--
                        <div class="col-lg-3 col-md-4">
                            <div :class="{'has-danger': errors.bank_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Institución Financiera
                                </label>
                                <el-select v-model="form.bank_id"
                                           @change="changeBank"
                                           filterable>
                                    <el-option v-for="option in banks"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id">
                                    </el-option>
                                </el-select>
                                <small v-if="errors.bank_id"
                                       class="form-control-feedback"
                                       v-text="errors.bank_id[0]">
                                </small>
                            </div>
                        </div>
                        -->
                        <!--                                     Cuenta bancaria-->
                        <div class="col-lg-3 col-md-4">
                            <div :class="{'has-danger': errors.bank_account_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Cuenta
                                </label>
                                <el-select v-model="form.bank_account_id"
                                           filterable>
                                    <el-option v-for="option in bank_loan_method_types"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id">
                                    </el-option>
                                </el-select>
                                <small v-if="errors.bank_account_id"
                                       class="form-control-feedback"
                                       v-text="errors.bank_account_id[0]">
                                </small>
                            </div>
                        </div>
                        <!-- Tipo comprobante -->
                        <div class="col-lg-3 col-md-4">
                            <div :class="{'has-danger': errors.bank_loan_type_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Tipo comprobante
                                </label>
                                <el-select v-model="form.bank_loan_type_id">
                                    <el-option v-for="option in bank_loan_types"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id">
                                    </el-option>
                                </el-select>
                                <small v-if="errors.bank_loan_type_id"
                                       class="form-control-feedback"
                                       v-text="errors.bank_loan_type_id[0]">
                                </small>
                            </div>
                        </div>
                        <!-- Número *-->
                        <div class="col-lg-2 col-md-4">
                            <div :class="{'has-danger': errors.number}"
                                 class="form-group">
                                <label class="control-label">
                                    Número
                                    <span v-if="form.bank_loan_type_id != 4"
                                          class="text-danger">*</span>
                                </label>
                                <el-input v-model="form.number">
                                </el-input>

                                <small v-if="errors.number"
                                       class="form-control-feedback"
                                       v-text="errors.number[0]">
                                </small>
                            </div>
                        </div>
                        <!--                                    Moneda -->
                        <div class="col-lg-2 col-md-3">
                            <div :class="{'has-danger': errors.currency_type_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Moneda
                                </label>
                                <el-select v-model="form.currency_type_id"
                                           @change="changeCurrencyType">
                                    <el-option v-for="option in currency_types"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id">
                                    </el-option>
                                </el-select>
                                <small v-if="errors.currency_type_id"
                                       class="form-control-feedback"
                                       v-text="errors.currency_type_id[0]">
                                </small>
                            </div>
                        </div>

                        <!-- Tipo de cambio -->
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.exchange_rate_sale}"
                                 class="form-group">
                                <label class="control-label">
                                    Tipo de cambio
                                    <el-tooltip class="item"
                                                content="Tipo de cambio del día, extraído de SUNAT"
                                                effect="dark"
                                                placement="top-end">
                                        <i class="fa fa-info-circle">
                                        </i>
                                    </el-tooltip>
                                </label>
                                <el-input v-model="form.exchange_rate_sale">
                                </el-input>
                                <small v-if="errors.exchange_rate_sale"
                                       class="form-control-feedback"
                                       v-text="errors.exchange_rate_sale[0]">
                                </small>
                            </div>
                        </div>
                        <!--Fec Emisión -->
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.date_of_issue}"
                                 class="form-group">
                                <label class="control-label">
                                    Fec Emisión
                                </label>
                                <el-date-picker v-model="form.date_of_issue"
                                                :clearable="false"
                                                type="date"
                                                value-format="yyyy-MM-dd"
                                                @change="changeDateOfIssue">
                                </el-date-picker>
                                <small v-if="errors.date_of_issue"
                                       class="form-control-feedback"
                                       v-text="errors.date_of_issue[0]">
                                </small>
                            </div>
                        </div>
                        <!--                                    Motivo -->
                        <!--
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors.loan_reason_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Motivo
                                </label>
                                <el-select v-model="form.loan_reason_id"
                                           filterable>
                                    <el-option v-for="option in bank_loan_reasons"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id">
                                    </el-option>
                                </el-select>
                                <small v-if="errors.loan_reason_id"
                                       class="form-control-feedback"
                                       v-text="errors.loan_reason_id[0]">
                                </small>
                            </div>
                        </div>
                        -->
                        <div class="col-md-12">&nbsp;</div>
                        <!--                                     + Agregar detalle -->
                        <div class="col-lg-2 col-md-6 mt-4">
                            <div class="form-group">
                                <button class="btn waves-effect waves-light btn-primary"
                                        type="button"
                                        @click.prevent="showDialogAddItem = true">
                                    + Agregar detalle
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="form.items.length > 0"
                         class="row mt-2">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="font-weight-bold">Descripción</th>
                                        <th class="text-right font-weight-bold">Total</th>
                                        <th class="text-right font-weight-bold">Total Interes</th>
                                        <th class="text-right font-weight-bold">Total a Pagar</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in form.items">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ row.description }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total_ingress }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total_interest }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>
                                        <td class="text-right">
                                            <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                    type="button"
                                                    @click.prevent="clickRemoveItem(index)">x
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h3 v-if="form.total > 0"
                                class="text-right">
                                <b>TOTAL: </b>{{ currency_type.symbol }} {{ form.total }}</h3>
                        </div>
                    </div>

                    <!-- Cuoptas -->
                    <div v-if="form.total > 0"
                         class="row col-lg-12 mt-3">
                        <!-- Pagos -->

                        <!--
                        <div class="col-md-6 table-responsive" v-if="form.payments !== undefined && form.payments.length>0">
                            <table v-if="form.payments.length>0"
                                   class="text-left table"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th class="text-left" >#
                                    </th>
                                    <th class="text-left" >Metodo de pago
                                    </th>
                                    <th class="text-left" >Descripcion
                                    </th>
                                    <th class="text-left"> Referencia </th>
                                    <th class="text-left"> Pago </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in form.payments"
                                    :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ row.payment_method_type_description}}</td>

                                                                            <td>{{ row.destination_description}}</td>

                                                                           <td>{{ row.reference}}</td>

                                                                            <td>{{ row.payment}}</td>


                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6" v-else >&nbsp;</div>
                        -->
                        <div class="col-md-6" >&nbsp;</div>

                        <div class="col-md-6 table-responsive">
                            <table v-if="form.fee.length>0"
                                   class="text-left table"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th class="text-left"
                                        style="width: 15px">#
                                    </th>
                                    <th class="text-left"
                                        style="width: 100px">Fecha
                                    </th>
                                    <th class="text-left"
                                        style="width: 100px">Monto
                                    </th>
                                    <th style="width: 30px">
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in form.fee"
                                    :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>
                                        <el-date-picker v-model="row.date"
                                                        :clearable="false"
                                                        format="dd/MM/yyyy"
                                                        type="date"
                                                        value-format="yyyy-MM-dd"></el-date-picker>
                                    </td>
                                    <td>
                                        <el-input v-model="row.amount"></el-input>
                                    </td>
                                    <td class="text-center">
                                        <button v-if="index > 0"
                                                class="btn waves-effect waves-light btn-xs btn-danger"
                                                type="button"
                                                @click.prevent="clickRemoveFee(index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <label class="control-label">
                                            <a class=""
                                               href="#"
                                               @click.prevent="clickAddFee">
                                                <i class="fa fa-plus font-weight-bold text-info"></i>
                                                <span style="color: #777777">
                                                Agregar cuota
                                            </span></a>

                                        </label>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <small v-if="errors.fee"
                               class="form-control-feedback"
                               v-text="errors.fee[0]">
                        </small>

                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button v-if="form.items.length > 0 && !id"
                               :loading="loading_submit"
                               native-type="submit"
                               type="primary">{{ (id) ? 'Actualizar' : 'Generar' }}
                    </el-button>
                </div>
            </form>
        </div>

        <loan-form-item :currency-type="currency_type"
                        :exchange-rate-sale="form.exchange_rate_sale"
                        :showDialog.sync="showDialogAddItem"
                        @add="addRow">
        </loan-form-item>

        <loan-options                           :isUpdate="id ? true:false"
                                                :recordId="expenseNewId"
                      :showClose="false"
                      :showDialog.sync="showDialogOptions">
        </loan-options>
    </div>
</template>

<script>

import LoanFormItem from './partials/item.vue'
// import PersonForm from '../../../../../../../resources/js/views/tenant/persons/form.vue'
import LoanOptions from './partials/options.vue'
import {exchangeRate, functions} from '../../../../../../../resources/js/mixins/functions'
import {mapActions, mapState} from "vuex";
import moment from "moment";


export default {
    props: [
        'configuration',
        'id'
    ],
    components: {
        LoanFormItem,
        // PersonForm,
        LoanOptions
    },
    mixins: [
        functions,
        exchangeRate
    ],
    computed: {
        ...mapState([
            'config',
            'payment_destinations',
            'establishment',
            'currency_types'
        ])
    },
    data() {
        return {
            loanId :null,
            resource: 'bank_loan',
            showDialogAddItem: false,
            showDialogNewPerson: false,
            showDialogOptions: false,
            loading_submit: false,
            errors: {},
            form: {
                fee: [],
                total: 0,
                bank_account_id: null,
            },
            aux_bank_id: null,
            bank_loan_types: [],
            accounts: [],
            accounts_by_bank: [],
            banks: [],
            // establishment: {},
            currency_type: {},
            bank_loan_method_types: [],
            // payment_destinations: [],
            bank_loan_reasons: [],
            expenseNewId: null
        }
    },
    mounted() {


        this.$http.get(`/${this.resource}/tables`)
            .then(response => {

                let data = response.data

                this.bank_loan_reasons = data.bank_loan_reasons
                this.bank_loan_method_types = data.bank_loan_method_types
                this.bank_loan_types = data.bank_loan_types
                 this.accounts = data.accounts
                // this.currency_types = data.currency_types
                this.$store.commit('setCurrencyTypes', data.currency_types);
                // this.establishment = data.establishment
                this.$store.commit('setEstablishment', data.establishment);
                this.banks = data.banks
                this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
                this.form.establishment_id = (this.establishment.id) ? this.establishment.id : null
                this.form.bank_loan_type_id = (this.bank_loan_types.length > 0) ? this.bank_loan_types[0].id : null
                this.form.loan_reason_id = (this.bank_loan_reasons.length > 0) ? this.bank_loan_reasons[0].id : null
                this.$store.commit('setPaymentMethodTypes',data.payment_destinations)
                // this.payment_destinations = data.payment_destinations

                this.changeDateOfIssue()
                this.changeCurrencyType()
            })
            .then(() => {

                this.getDataFromLoan()
            }).finally(()=>{
            if (this.form.fee.length < 1) {
                this.addFee()
            }

            })
    },
    created() {

        this.$store.commit('setConfiguration', this.configuration);
        this.loadConfiguration()
        // await
        this.initForm()
        if(this.config.currency_type_id !== undefined) {
            this.form.currency_type_id = this.config.currency_type_id
        }
        if(this.config.establishment !== undefined) {
            this.form.establishment_id = this.config.establishment.id
        }

        // await

        // await
        /*
        this.$eventHub.$on('reloadDataPersons', (bank_id) => {
            this.reloadDataSuppliers(bank_id)
        })
        */

        // await
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        getDataFromLoan(){
            if (this.id !== undefined && this.id) {
                let data = undefined
                this.expenseNewId = this.id
                this.$http.get(`/${this.resource}/record/${this.id}`)
                    .then(response => {
                        this.form.fee = []
                        data = response.data.data.bank_loan;
                        this.form = data
                    })
                .finally(()=>{
                        if (this.form.fee.length < 1) {
                            this.addFee()
                        }
                    })
            }else{
                this.addFee()
            }
        },
        async isUpdate() {
            if (this.id !== undefined  && this.id) {
                await this.$http.get(`/${this.resource}/record/${this.id}`)
                    .then(response => {
                        this.form = response.data.data.bank_loan
                    })
            }

        },
        changeLoanMethodType(index = 0) {

            // this.form.payments[index].payment_destination_id = (this.payment_destinations.length > 0 && this.form.payments[index].bank_loan_method_type_id != 1) ? this.payment_destinations[0].id : null
            // this.form.payments[index].payment_destination_disabled = (this.form.payments[index].bank_loan_method_type_id == 1) ? true : false

        },
        selectBank() {

            let bank = _.find(this.banks, {'id': this.aux_bank_id})
            this.form.bank_id = (bank) ? bank.id : null
            this.aux_bank_id = null

        },
        changeBank(){

            this.form.bank_account_id = null;
            this.accounts_by_bank = [];
            this.accounts_by_bank = _.filter(this.accounts,{bank_id:this.form.bank_id})
        },
        initForm() {
            this.errors = {}
            this.form = {
                id: (this.id)?this.id:null,
                bank_account_id :null,
                payment_condition_id: '02',
                establishment_id: null,
                bank_loan_type_id: null,
                loan_reason_id: null,
                number: null,
                date_of_issue: moment().format('YYYY-MM-DD'),
                time_of_issue: moment().format('HH:mm:ss'),
                bank_id: null,
                currency_type_id: null,
                exchange_rate_sale: 0,
                total: 0,
                items: [],
                fee: [],
                payments: [],
            }

            this.clickAddFee();
            // this.clickAddPayment()
            // this.changeLoanMethodType()
        },
        resetForm() {
            this.initForm()
            this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
            this.form.establishment_id = (this.config.establishment !== undefined)?this.config.establishment.id:null;
            this.form.bank_loan_type_id = (this.bank_loan_types.length > 0) ? this.bank_loan_types[0].id : null
            this.form.loan_reason_id = (this.bank_loan_reasons.length > 0) ? this.bank_loan_reasons[0].id : null

            this.changeDateOfIssue()
            this.changeCurrencyType()
        },
        changeDateOfIssue() {
            this.form.date_of_due = this.form.date_of_issue
            this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                this.form.exchange_rate_sale = response
            })
        },
        clickRemoveFee(index) {
            this.form.fee.splice(index, 1);
            this.calculateFee();
        },
        clickCancel(index) {
            // this.form.payments.splice(index, 1);
            this.form.fee.splice(index, 1);
        },
        clickAddPayment() {
            /*
            this.form.payments.push({
                id: null,
                bank_loan_id: null,
                date_of_payment: moment().format('YYYY-MM-DD'),
                bank_loan_method_type_id: 1,
                payment_destination_id: null,
                reference: null,
                payment_destination_disabled: true,
                payment: 0,
            });
            */
        },
        addRow(row) {
            this.form.items.push(row)
            this.calculateTotal()
        },
        clickRemoveItem(index) {
            this.form.items.splice(index, 1)
            this.calculateTotal()
        },
        changeCurrencyType() {
            this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
            let items = []
            this.form.items.forEach((row) => {
                items.push(this.calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))


            });
            this.form.items = items
            this.calculateTotal()
        },
        calculateRowItem(row, currency_type_id, exchange_rate_sale) {

            let currency_type_id_old = row.currency_type_id

            row.total = row.total_original

            if (currency_type_id_old === 'PEN' && currency_type_id_old !== currency_type_id) {
                row.total = row.total_original / exchange_rate_sale;
            }

            if (currency_type_id === 'PEN' && currency_type_id_old !== currency_type_id) {
                row.total = row.total_original * exchange_rate_sale;
            }

            row.total = _.round(row.total, 2)

            return row
        },
        calculateTotal() {

            this.calculateFee()
            /*
            let total = 0
            this.form.items.forEach((row) => {
                total += parseFloat(row.total)
            });
            this.form.total = _.round(total, 2)
            this.form.payments[0].payment = this.form.total
            */
        },
        submit() {

            let validate = this.validate_payments()
            if (validate.acum_total > parseFloat(this.form.total) || validate.error_by_item > 0) {
                return this.$message.error('Los montos ingresados no coinciden con el monto total o son incorrectos');
            }

            if (validate.empty_payment_destination > 0) {
                return this.$message.error('El destino del pago es requerido');
            }

            if (this.form.bank_loan_type_id != 4) {
                if (!this.form.number) {
                    return this.$message.error('El número es obligatorio')
                }
            }

            this.loading_submit = true
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.resetForm()
                        this.expenseNewId = response.data.data.id
                        this.showDialogOptions = true
                        this.getDataFromLoan()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        this.$message.error(error.response.data.message)
                    }
                })
                .finally(() => {
                    this.loading_submit = false
                })
        },
        validate_payments() {


            let error_by_item = 0
            let acum_total = 0
            let empty_payment_destination = 0

            this.form.payments.forEach((item) => {
                acum_total += parseFloat(item.payment)
                if (item.payment <= 0 || item.payment == null) error_by_item++;
                if (item.bank_loan_method_type_id != 1 && item.payment_destination_id == null) empty_payment_destination++;
            })

            return {
                error_by_item: error_by_item,
                empty_payment_destination: empty_payment_destination,
                acum_total: acum_total
            }

        },
        close() {
            location.href = `/${this.resource}`
        },
        reloadDataSuppliers(bank_id) {

            this.$http.get(`/${this.resource}/table/suppliers`).then((response) => {

                this.form.bank_id = bank_id
                this.suppliers = response.data

            })
        },

        clickAddFee() {
            // @todo: mejorar las cuotas para que añadan un mes automaticamente
                this.addFee()
                this.calculateFee();

        },
        addFee(){
            let date = moment();
            this.form.date_of_due = date;
            // @todo: mejorar las cuotas para que añadan un mes automaticamente
                if (this.form.fee) {
                    let fee_count = parseInt(this.form.fee.length);
                    if (isNaN(fee_count)) fee_count = 0;
                    date = date.add(fee_count, 'months')
                    this.form.fee.push({
                        id: null,
                        date: date.format('YYYY-MM-DD'),
                        currency_type_id: this.form.currency_type_id,
                        amount: 0,
                    });
                }
        },
        calculateFee() {

            let total = 0
            this.form.items.forEach((row) => {
                total += parseFloat(row.total)
            });
            this.form.total = _.round(total, 2)
            let fee_count = this.form.fee.length;


            if (isNaN(total)) total = 0;

            let accumulated = 0;
            let amount = _.round(total / fee_count, 2);

            _.forEach(this.form.fee, row => {
                accumulated += amount;
                if (total - accumulated < 0) {
                    amount = _.round(total - accumulated + amount, 2);
                }
                row.amount = amount;

            })
        },
    }
}
</script>
