<template>
    <el-dialog
        :title="titleDialog"
        :visible="showDialog"
        @close="close"
        @open="create">
        <form
            autocomplete="off"
            @submit.prevent="submit">
            <el-tabs v-model="tabActive">

                <el-tab-pane class
                             name="first">
                    <span slot="label">
                        Datos de la suscripcion
                    </span>
                    <div class="form-body">

                        <div class="row">
                            <!-- Cliente -->

                            <div :class="{'has-danger': errors.parent_customer_id}"
                                 class="form-group col-6 ">
                                <label class="control-label">
                                    Cliente
                                    <!--
                                    <a href="#"
                                       @click.prevent="showDialogNewPerson = true">
                                       [+ Nuevo]
                                       </a>
                                    -->
                                </label>
                                <el-select v-model="form.parent_customer_id"
                                           :disabled='!is_editable'
                                           :loading="loading_search"
                                           :remote-method="searchRemoteParent"
                                           class="border-left rounded-left border-info"
                                           dusk="parent_customer_id"
                                           filterable
                                           placeholder="Escriba el nombre o número de documento del padre"
                                           popper-class="el-select-parent"
                                           remote
                                           @change="changeCustomer"
                                           @keyup.enter.native="keyupCustomer">

                                    <el-option v-for="option in customers"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>

                                </el-select>
                                <small v-if="errors.parent_customer_id"
                                       class="form-control-feedback"
                                       v-text="errors.parent_customer_id[0]"></small>

                            </div>

                            <!-- Plan -->
                            <div :class="{'has-danger': errors.suscription_plan_id}"
                                 class="form-group col-12 ">
                                <label class="control-label">
                                    Seleccione el plan
                                </label>
                                <el-select v-model="form.suscription_plan_id"
                                           :clearable="false"
                                           :disabled='!is_editable'
                                           class="border-left rounded-left border-info"
                                           dusk="suscription_plan_id"
                                           filterable
                                           placeholder="Escriba el nombre del plan"
                                           popper-class="el-select-customers"
                                           remote
                                           @change="changePlan"
                                >

                                    <el-option v-for="option in plans"
                                               :key="option.id"
                                               :label="option.name + ' - ' +option.description"
                                               :value="option.id"></el-option>

                                </el-select>
                                <small v-if="errors.suscription_plan_id"
                                       class="form-control-feedback"
                                       v-text="errors.suscription_plan_id[0]"></small>

                            </div>
                            <!--                            Fecha de inicio-->
                            <div class="col-md-3">
                                <label class="control-label">
                                    Fecha de primer pago
                                </label>
                                <el-date-picker v-model="form.start_date"
                                                :clearable="false"
                                                :disabled='!is_editable'
                                                format="dd/MM/yyyy"
                                                type="date"
                                                value-format="yyyy-MM-dd"
                                                @change="changeStartDate"></el-date-picker>
                            </div>

                            <!--                            Fecha de fin-->
                            <div class="col-md-3">
                                <label class="control-label">
                                    Fecha de último pago
                                </label>
                                <el-date-picker v-model="end_date"
                                                :disabled="true"
                                                format="dd/MM/yyyy"
                                                type="date"
                                                value-format="yyyy-MM-dd"
                                ></el-date-picker>
                            </div>

                            <!--
                            Lista de items
                            -->

                            <div v-if="form.items !== undefined && form.items.length > 0"
                                 class="col-12">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th class="font-weight-bold">Descripción</th>
                                                <th class="text-right font-weight-bold">Valor Unitario</th>
                                                <th class="text-right font-weight-bold">Precio Unitario</th>
                                                <th class="text-right font-weight-bold">Subtotal</th>
                                                <!--<th class="text-right font-weight-bold">Cargo</th>-->
                                                <th class="text-right font-weight-bold">Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(row, index) in form.items"
                                                :key="index">
                                                <td>
                                                    {{ setDescriptionOfItem(row.item) }}
                                                    {{
                                                        row.item.presentation.hasOwnProperty('description') ? row.item.presentation.description : ''
                                                    }}<br/><small>{{ row.affectation_igv_type.description }}</small>
                                                </td>
                                                <td class="text-right">{{ getSymbol(currency_type) }}
                                                                       {{ getFormatUnitPriceRow(row.unit_value) }}
                                                </td>
                                                <td class="text-right">{{ getSymbol(currency_type) }}
                                                                       {{ getFormatUnitPriceRow(row.unit_price) }}
                                                </td>

                                                <td class="text-right">{{ getSymbol(currency_type) }}
                                                                       {{ row.total_value }}
                                                </td>
                                                <!--<td class="text-right">{{  getSymbol(currency_type) }} {{ row.total_charge }}</td>-->
                                                <td class="text-right">{{ getSymbol(currency_type) }} {{
                                                        row.total
                                                                       }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="9"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </el-tab-pane>


                <el-tab-pane v-if="hasNv"
                             class
                             name="payments">
                    <span slot="label">
                        Ver Recibos de pago
                    </span>
                    <div class="form-body">

                        <div class="table-responsive">
                            <table class="table table-responsive-xl ">
                                <thead class="">
                                <th>#</th>
                                <th class="text-center">Fecha Emisión</th>
                                <!--                                <th>Cliente</th>-->
                                <!--                                <th>Hijo</th>-->
                                <!--                                <th>Grado</th>-->
                                <!--                                <th>Sección</th>-->
                                <th>Recibo de pago</th>
                                <th>Estado</th>
                                <th class="text-center">Moneda</th>
                                <th
                                    class="text-right">F. Vencimiento
                                </th>
                                <th class="text-right">Total</th>


                                <th class="text-center">Comprobantes</th>
                                <th class="text-center">Estado pago</th>
                                <!--                                <th class="text-center">Pagos</th>-->
                                <th class="text-center">Descarga</th>
                                </thead>

                                <tbody>
                                <tr

                                    v-for="(row, index) in form.sales_note"
                                    :key="index"

                                >
                                    <!-- # -->
                                    <td>{{ index + 1}}</td>
                                    <!-- Fecha Emisión -->
                                    <td class="text-center">{{ row.date_of_issue }}</td>
                                    <!-- Cliente -->
                                    <!--                                <td>{{ row.customer_name }}<br/>
                                                                        <small v-text="row.customer_number">
                                                                        </small>
                                                                    </td>-->
                                    <!-- Recibo de pago -->
                                    <td>{{ row.full_number }}</td>
                                    <!-- Estado  -->
                                    <td>{{ row.state_type_description }}</td>
                                    <!--Moneda -->
                                    <td class="text-center">{{ row.currency_type_id }}</td>

                                    <!-- F. Vencimiento -->
                                    <td
                                        class="text-right">{{ row.due_date }}
                                    </td>
                                    <!-- Total -->
                                    <td class="text-right">{{ row.total }}</td>

                                    <!--Comprobantes -->
                                    <td>
                                        <template v-for="(document,i) in row.documents">
                                            <label :key="i"
                                                   class="d-block"
                                                   v-text="document.number_full">
                                            </label>
                                        </template>
                                    </td>
                                    <!-- Estado pago -->
                                    <td class="text-center">
                            <span
                                :class="{'bg-success': (row.total_canceled), 'bg-warning': (!row.total_canceled)}"
                                class="badge text-white">{{ row.total_canceled ? 'Pagado' : 'Pendiente' }}
                            </span>
                                    </td>

                                    <!-- Pagos -->
                                    <!--                                <td class="text-center">
                                                                        <button class="btn waves-effect waves-light btn-xs btn-primary"
                                                                                style="min-width: 41px"
                                                                                type="button"
                                                                                @click.prevent="clickPayment(row.id)">
                                                                            <i class="fas fa-money-bill-alt">
                                                                            </i>
                                                                        </button>
                                                                    </td>-->

                                    <!-- Descarga -->
                                    <td class="text-right">
                                        <button class="btn waves-effect waves-light btn-xs btn-info"
                                                type="button"
                                                @click.prevent="clickDownload(row.external_id)">
                                            <i class="fas fa-file-pdf">
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </el-tab-pane>
                <el-tab-pane v-if="hasFac"
                             class
                             name="invoices">
                    <span slot="label">
                        Ver Facturas
                    </span>
                    <div class="form-body">
                        <table class="table table-responsive-xl ">
                            <thead class="">

                            <th>#</th>
                            <!-- date_of_issue -->
                            <th class="text-center"
                                style="min-width: 95px;">Emisión
                            </th>
                            <th>Cliente</th>
                            <th>Número</th>
                            <th>Estado</th>
                            <th class="text-center">Moneda</th>
                            <th class="text-right">T.Igv</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Saldo</th>
                            <th class="text-center"></th>
                            </thead>

                            <tbody>
                            <tr

                                v-for="(row, index) in form.invoices"
                                :key="index"

                                :class="{'text-danger': (row.state_type_id === '11'),
                            'text-warning': (row.state_type_id === '13'),
                            'border-light': (row.state_type_id === '01'),
                            'border-left border-info': (row.state_type_id === '03'),
                            'border-left border-success': (row.state_type_id === '05'),
                            'border-left border-secondary': (row.state_type_id === '07'),
                            'border-left border-dark': (row.state_type_id === '09'),
                            'border-left border-danger': (row.state_type_id === '11'),
                            'border-left border-warning': (row.state_type_id === '13')}"

                            >
                                <td>{{ index }}</td>
                                <!-- date_of_issue -->
                                <td class="text-center">{{ row.date_of_issue }}</td>
                                <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                                <td>{{ row.number }}<br/>
                                    <small v-text="row.document_type_description"></small><br/>
                                    <small v-if="row.affected_document"
                                           v-text="row.affected_document"></small>
                                </td>
                                <td>
                                    <el-tooltip v-if="tooltip(row, false)"
                                                class="item"
                                                effect="dark"
                                                placement="bottom">
                                        <div slot="content">{{ tooltip(row) }}</div>
                                        <span :class="{'bg-danger': (row.state_type_id === '11'), 'bg-warning': (row.state_type_id === '13'), 'bg-secondary': (row.state_type_id === '01'), 'bg-info': (row.state_type_id === '03'), 'bg-success': (row.state_type_id === '05'), 'bg-secondary': (row.state_type_id === '07'), 'bg-dark': (row.state_type_id === '09')}"
                                              class="badge bg-secondary text-white">
                                    {{ row.state_type_description }}
                                </span>
                                    </el-tooltip>
                                    <span v-else
                                          :class="{'bg-danger': (row.state_type_id === '11'), 'bg-warning': (row.state_type_id === '13'), 'bg-secondary': (row.state_type_id === '01'), 'bg-info': (row.state_type_id === '03'), 'bg-success': (row.state_type_id === '05'), 'bg-secondary': (row.state_type_id === '07'), 'bg-dark': (row.state_type_id === '09')}"
                                          class="badge bg-secondary text-white">
                                {{ row.state_type_description }}
                            </span>
                                    <template v-if="row.regularize_shipping && row.state_type_id === '01'">
                                        <el-tooltip :content="row.message_regularize_shipping"
                                                    class="item"
                                                    effect="dark"
                                                    placement="top-start">
                                            <i class="fas fa-exclamation-triangle fa-lg"
                                               style="color: #D2322D !important"></i>
                                        </el-tooltip>
                                    </template>
                                </td>
                                <td class="text-center">{{ row.currency_type_id }}</td>
                                <td class="text-right">{{ row.total_igv }}</td>
                                <td class="text-right">{{ row.total }}</td>
                                <td class="text-right">{{ row.balance }}</td>
                                <td class="text-center">
                                    <button type="button"
                                            style="min-width: 41px"
                                            class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                            @click.prevent="clickDownloadExtra(row.download_pdf)"
                                            v-if="row.has_pdf">PDF
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </el-tab-pane>
                <!--
                <el-tab-pane class
                             name="second">
                    <span slot="label">
                        Servicios en el plan
                    </span>
                    <div class="form-body">

                        <div class="row">



                        </div>

                    </div>

                </el-tab-pane>
                -->

            </el-tabs>
            <div class="form-actions text-right mt-4">
                <el-button
                    @click.prevent="close()">
                    Cancelar
                </el-button>
                <el-button
                    :loading="loading_submit"
                    native-type="submit"
                    type="primary">
                    Guardar
                </el-button>
            </div>
        </form>


        <tenant-quotations-item-form
            :configuration="config"
            :currency-type-id-active="config.currency_type_id"
            :displayDiscount="false"
            :exchange-rate-sale="exchange_rate"
            :recordItem="recordItem"
            :showDialog.sync="showDialogAddItem"
            :typeUser="config.typeUser"
            @add="addRow"
        >

        </tenant-quotations-item-form>

        <!--

        <tenant-documents-items-list
            :currency_types=currency_types
            :document_type_id='"01"'
            :exchange-rate-sale="exchange_rate"
            :external="true"
            :showDialog.sync="showDialogNewPerson"
            :operationTypeId="'0101'"

            type="customers"
        ></tenant-documents-items-list>
        -->
    </el-dialog>

</template>

<script>

import {mapActions, mapState} from "vuex/dist/vuex.mjs";

import {serviceNumber} from '../../../../../../resources/js/mixins/functions'
import {
    calculateRowItem,
    FormatUnitPriceRow,
    showNamePdfOfDescription
} from "../../../../../../resources/js/helpers/functions";
import moment from 'moment'

export default {
    mixins: [
        serviceNumber
    ],
    props: [
        'showDialog',
        'suscriptionId',
    ],
    data() {
        return {
            is_editable: true,
            loading_search: false,
            loading_submit: false,
            showDialogAddItem: false,
            end_date: null,
            defaultStartDate: null,
            titleDialog: null,
            errors: {},
            hasNv: false,
            hasFac: false,
            tabActive: 'first',
            currency_type: {},
            //countries: [],
            //all_departments: [],
            //all_provinces: [],
            //all_districts: [],
            provinces: [],
            districts: [],
            customer: {},
            customers: [],
            recordItem: null,
            form: {
                id: null,
                cat_period_id: null,
                name: null,
                description: null,
                period: null,
                currency_type_id: null,
                items: [],
                start_date: moment().format('YYYY-MM-DD'),
                total_igv_free: 0,
                total_exportation: 0,
                total_taxed: 0,
                total_exonerated: 0,
                total_unaffected: 0,
                total_free: 0,
                total_igv: 0,
                total_value: 0,
                total_taxes: 0,
                total: 0,
                payments: [],
                document_type_id: "01",
                parent_customer_id: null,
            },
            input_person: {},

            //identity_document_types: []
        }
    },
    created() {
        this.loadConfiguration();
        this.$store.commit('setPlans', [])
        this.initForm()

        this.$http
            .post(`/full_suscription/${this.resource}/tables`, {})
            .then(response => {
                this.$store.commit('setPeriods', response.data.periods)
                // this.$store.commit('setCustomers', response.data.customers)
                this.customers = response.data.customers
                this.$store.commit('setAllCustomers', response.data.customers)
                this.$store.commit('setPlans', response.data.plans)
            })
        this.getCommonData()
    },
    computed: {
        ...mapState([
            'config',
            'resource',
            'periods',
            // 'form_data',
            'exchange_rate',
            'currency_types',
            // 'customers',
            'customer_addresses',
            'all_customers',
            'affectation_igv_types',
            'unit_types',
            // 'customer',
            'parent_customer',
            'plans',
        ]),
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
            'clearFormData',
        ]),

        getCommonData() {
            this.$http.post('/full_suscription/CommonData', {})
                .then((response) => {
                    this.$store.commit('setCurrencyTypes', response.data.currency_types)
                    this.$store.commit('setAffectationIgvTypes', response.data.affectation_igv_types)
                    this.$store.commit('setUnitTypes', response.data.unit_types)
                    this.defaultStartDate = response.data.startDate;

                })
                .then(() => {
                    // console.error(this.currency_type);
                    // this.changeCurrencyType()
                    this.form.start_date = this.defaultStartDate
                    this.changeStartDate()
                })
        },

        clearForm() {
            this.form = {
                id: null,
                cat_period_id: null,
                name: null,
                description: null,
                period: null,
                currency_type_id: this.config.currency_type_id,
                items: [],
                start_date: moment().format('YYYY-MM-DD'),
                total_igv_free: 0,
                total_exportation: 0,
                total_taxed: 0,
                total_exonerated: 0,
                total_unaffected: 0,
                total_free: 0,
                total_igv: 0,
                total_value: 0,
                total_taxes: 0,
                total: 0,
                payments: [],
                document_type_id: "01",
                parent_customer_id: null,
            }
            this.form.start_date = this.defaultStartDate
            this.changeStartDate()
            this.customer = {};
            this.$store.commit('setParentCustomer', {})

        },
        initForm() {
            this.clearForm();
            this.$emit('clearSuscriptionId', null)
            if (this.form.items === undefined) this.form.items = [];
            if (this.form.currency_type_id === undefined) this.form.currency_type_id = this.config.currency_type_id;


        },
        create() {
            this.tabActive = 'first';
            /*
                    tabActive
                    first
                    payments
                    invoices


                    hasNv
        hasFac
                    */
            this.hasNv = false;
            this.hasFac = false;
            this.getCommonData()
            this.is_editable = false;
            this.tabActive = 'first'
            this.errors = {}
            if (this.suscriptionId) {
                this.$http
                    .post(`/full_suscription/${this.resource}/record`, {
                        person: this.suscriptionId,
                    })
                    .then(response => {
                        this.$emit('clearSuscriptionId', null)
                        this.form = response.data.data
                        this.$store.commit('setFormData', {})
                        let cs = this.customers;
                        if (cs === null) {
                            cs = []
                        }

                        let parent = this.form.parent_customer;
                        let customers = undefined;
                        if (parent !== undefined) {
                            customers = _.find(cs, {'id': parent.id});
                            if (customers === undefined) {
                                cs.push(parent)
                            }
                        }


                        // esponse.data.dat
                        // this.$store.commit('setCustomers', cs)
                        this.customers = cs

                        this.$store.commit('setParentCustomer', parent)


                        if (this.form.sales_note !== undefined && this.form.sales_note.length > 0) {
                            this.hasNv = true;
                        }

                        if (this.form.invoices !== undefined && this.form.invoices.length > 0) {
                            this.hasFac = true;
                        }

                        this.changeStartDate()
                        /*
                        hasNv
                        hasFac
                        */
                        // this.form = response.data.data
                        // this.filterProvinces()
                        // this.filterDistricts()
                    })
                    .then(() => {
                        this.changeCurrencyType()
                    })
                    .finally(() => {
                        this.titleDialog = (this.form.id) ? 'Editar matrícula' : 'Nueva matrícula'
                        this.$emit('clearSuscriptionId', null)
                    })
            } else {
                this.clearForm()
                this.form.id = this.suscriptionId
                this.changeCurrencyType()
                this.is_editable = true;
            }
            this.changeStartDate()
            this.titleDialog = (this.form.id) ? 'Editar matrícula' : 'Nueva matrícula'

        },

        submit() {
            this.loading_submit = true
            let plan = _.find(this.plans, {'id': this.form.suscription_plan_id});


            let form = this.form;
            form.customer = this.customer;
            form.parent_customer = this.parent_customer;


            form.suscription_suscription_plan_id = plan.id
            form.cat_period_id = plan.cat_period_id
            form.quantity_period = plan.quantity_period
            form.customer_id = this.customer.id

            this.$http.post(`/full_suscription/${this.resource}`, this.form)
                .then(response => {
                    this.$eventHub.$emit('reloadData')
                    this.close()
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        console.log(error)
                    }
                })
                .finally(() => {
                    this.loading_submit = false
                })
        },
        close() {
            this.$emit('update:showDialog', false)
            this.$emit('clearSuscriptionId', null)
            this.clearForm();
            this.clearFormData();
            this.$store.commit('setFormData', {})

        },
        searchCustomer() {
            this.searchServiceNumberByType()
        },
        addRow(row) {
            /* Extraido de resources/js/views/tenant/quotations/form.vue */
            if (this.recordItem) {
                this.form.items[this.recordItem.indexi] = row
                this.recordItem = null

            } else {
                this.form.items.push(JSON.parse(JSON.stringify(row)));
            }

            if (
                this.form.start_date === undefined ||
                this.form.start_date === null
            ) {
                this.form.start_date = this.defaultStartDate
                this.changeStartDate()
            }
            this.calculateTotal();

        },
        clickAddItem() {
            this.recordItem = null;
            this.showDialogAddItem = true;
        },

        clickRemoveItem(index) {
            this.form.items.splice(index, 1)
            this.calculateTotal()
        },
        ediItem(row, index) {
            row.indexi = index
            this.recordItem = row
            this.showDialogAddItem = true
        },

        changeCurrencyType() {
            let currencyT = this.config.currency_type_id;
            if (this.form !== undefined && this.form.currency_type_id !== undefined) {
                currencyT = this.form.currency_type_id;
            }

            if (this.form !== undefined && currencyT === null) {
                currencyT = this.config.currency_type_id;
                this.form.currency_type_id = this.config.currency_type_id;
            }
            this.currency_type = _.find(this.currency_types, {'id': currencyT})
            let items = []

            if (this.form !== undefined &&
                this.form.items !== undefined &&
                this.form.items !== null) {
                let citems = this.form.items;
                Object.keys(citems).forEach(key => {
                    let row = citems[key] // value of the current key
                    items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
                })
            }

            this.form.items = items
            this.calculateTotal()
        },
        calculateTotal() {

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
            let total_igv_free = 0
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


                if (['11', '12', '13', '14', '15', '16'].includes(row.affectation_igv_type_id)) {

                    let unit_value = row.total_value / row.quantity
                    let total_value_partial = unit_value * row.quantity
                    row.total_taxes = row.total_value - total_value_partial
                    row.total_igv = total_value_partial * (row.percentage_igv / 100)
                    row.total_base_igv = total_value_partial
                    total_value -= row.total_value
                    total_igv_free += row.total_igv

                }

            });

            this.form.total_igv_free = _.round(total_igv_free, 2)
            this.form.total_exportation = _.round(total_exportation, 2)
            this.form.total_taxed = _.round(total_taxed, 2)
            this.form.total_exonerated = _.round(total_exonerated, 2)
            this.form.total_unaffected = _.round(total_unaffected, 2)
            this.form.total_free = _.round(total_free, 2)
            this.form.total_igv = _.round(total_igv, 2)
            this.form.total_value = _.round(total_value, 2)
            this.form.total_taxes = _.round(total_igv, 2)
            this.form.total = _.round(total, 2)


            this.setTotalDefaultPayment()

        },

        setTotalDefaultPayment() {
            if (this.form.payments !== undefined && this.form.payments.length > 0) {

                this.form.payments[0].payment = this.form.total
            }
        },

        getFormatUnitPriceRow(number) {
            return FormatUnitPriceRow(number)
        },
        setDescriptionOfItem(item) {
            return showNamePdfOfDescription(item, this.config.show_pdf_name)
        },

        searchRemtePerson(input, type) {

            if (input.length > 0) {
                this.loading_search = true
                let parameters = {
                    input: input,
                    document_type_id: this.form.document_type_id,
                    operation_type_id: this.form.operation_type_id,
                    type: type,
                }
                this.$http
                    .post(`/full_suscription/${this.resource}/search/customers`, parameters)
                    .then(response => {
                        // this.$store.commit('setCustomers', response.data.customers)
                            this.customers = response.data.customers

                        this.loading_search = false
                        this.input_person.number = null
                        if (this.customers.length == 0) {
                            this.filterCustomers()
                            this.input_person.number = input
                        }
                    })
            } else {
                this.filterCustomers()
                this.input_person.number = null
            }
        },
        // Busqueda de clientes basada en resources/js/views/tenant/documents/invoice.vue
        searchRemoteParent(input) {
            this.searchRemtePerson(input, 'parent')

        },

        keyupCustomer() {

            if (this.input_person.number) {

                if (!isNaN(parseInt(this.input_person.number))) {

                    switch (this.input_person.number.length) {
                        case 8:
                            this.input_person.identity_document_type_id = '1'
                            this.showDialogNewPerson = true
                            break;

                        case 11:
                            this.input_person.identity_document_type_id = '6'
                            this.showDialogNewPerson = true
                            break;
                        default:
                            this.input_person.identity_document_type_id = '6'
                            this.showDialogNewPerson = true
                            break;
                    }
                }
            }
        },
        filterCustomers() {
            let customers = this.all_customers;
            if (this.form.operation_type_id !== undefined && ['0101', '1001', '1004'].includes(this.form.operation_type_id)) {

                if (this.form.document_type_id === '01') {
                    customers = _.filter(this.all_customers, {'identity_document_type_id': '6'})
                } else {
                    if (this.document_type_03_filter) {
                        customers = _.filter(this.all_customers, (c) => {
                            return c.identity_document_type_id !== '6'
                        })
                    } else {
                        customers = this.all_customers
                    }
                }

            } else {
                customers = this.all_customers
            }

            // this.$store.commit('setCustomers', customers)
            this.customers = customers


        },
        changeCustomer() {
            //  this.$store.commit('setCustomersAddresses', [])
            this.$store.commit('setParentCustomer', {})
            // this.$store.commit('setCustomer', {})
            this.customer = {};

            let customers = this.customers;
            let customer = _.find(customers, {'id': this.form.parent_customer_id});
            if (customer !== undefined) {
                customer.parent_id = parseInt(customer.parent_id);
                if (isNaN(customer.parent_id)) customer.parent_id = 0;
                if (parseInt(customer.parent_id) == 0) {
                    this.$store.commit('setParentCustomer', customer)
                }
                // this.$store.commit('setCustomer', this.parent_customer)
            }
        },
        changePlan() {

            // this.form.start_date = moment().format('YYYY-MM-DD');
            this.form.start_date = this.defaultStartDate
            let plan = _.find(this.plans, {'id': this.form.suscription_plan_id});
            this.form.items = [];
            this.form.total_charge = 0;
            this.form.total_discount = 0;
            this.form.total_exportation = 0;
            this.form.total_free = 0;
            this.form.total_taxed = 0;
            this.form.total_unaffected = 0;
            this.form.total_exonerated = 0;
            this.form.total_igv = 0;
            this.form.total_igv_free = 0;
            this.form.total_base_isc = 0;
            this.form.total_isc = 0;
            this.form.total_base_other_taxes = 0;
            this.form.total_other_taxes = 0;
            this.form.total_taxes = 0;
            this.form.total_value = 0;
            this.form.total = 0;
            if (plan !== undefined && plan.items !== undefined && plan.items.length > 0) {
                this.form.items = plan.items;
                this.form.total_prepayment = plan.total_prepayment
                this.form.total_charge = plan.total_charge
                this.form.total_discount = plan.total_discount
                this.form.total_exportation = plan.total_exportation
                this.form.total_free = plan.total_free
                this.form.total_taxed = plan.total_taxed
                this.form.total_unaffected = plan.total_unaffected
                this.form.total_exonerated = plan.total_exonerated
                this.form.total_igv = plan.total_igv
                this.form.total_igv_free = plan.total_igv_free
                this.form.total_base_isc = plan.total_base_isc
                this.form.total_isc = plan.total_isc
                this.form.total_base_other_taxes = plan.total_base_other_taxes
                this.form.total_other_taxes = plan.total_other_taxes
                this.form.total_taxes = plan.total_taxes
                this.form.total_value = plan.total_value
                this.form.total = plan.total
            }


            this.changeStartDate();
        },
        getSymbol(currency_type) {
            if (currency_type !== undefined &&
                currency_type.symbol !== undefined) {
                return currency_type.symbol;
            }

            return '*'
        },
        changeStartDate() {
            let plan = _.find(this.plans, {'id': this.form.suscription_plan_id});
            let date = this.form.start_date, period = 'M', qty = 1;
            if (plan === undefined) {
                plan = this.plans[0]
            }

            if (plan !== undefined) {
                if (plan.quantity_period !== undefined)
                    qty = parseInt(plan.quantity_period)
                if(qty > 0){
                    qty = qty - 1;
                }
                if (plan.periods !== undefined)
                    period = plan.periods
            }
            this.end_date = moment(date, 'YYYY-MM-DD').add(qty, period).format('YYYY-MM-DD');

        },
        clickDownload(external_id) {
            window.open(`/sale-notes/downloadExternal/${external_id}`, '_blank');
        },
        // periods
        tooltip(row, message = true) {
            if (message) {
                if (row.shipping_status) return row.shipping_status.message;

                if (row.sunat_shipping_status) return row.sunat_shipping_status.message;

                if (row.query_status) return row.query_status.message;
            }

            if ((row.shipping_status) || (row.sunat_shipping_status) || (row.query_status)) return true;

            return false;
        },
        clickDownloadExtra(download) {
            window.open(download, '_blank');
        },
    }
}
</script>
