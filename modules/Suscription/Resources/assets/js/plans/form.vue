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
                        Datos del plan
                    </span>
                    <div class="form-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div
                                    :class="{'has-danger': errors.name}"
                                    class="form-group">
                                    <label class="control-label">
                                        Nombre
                                    </label>
                                    <el-input
                                        v-model="form_data.name"
                                    ></el-input>
                                    <small
                                        v-if="errors.name"
                                        class="form-control-feedback"
                                        v-text="errors.name[0]">
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div
                                    :class="{'has-danger': errors.periods}"
                                    class="form-group">
                                    <label class="control-label">
                                        Tipo de Periodos
                                    </label>
                                    <el-select
                                        v-model="form_data.periods"
                                        filterable>
                                        <el-option
                                            v-for="option in periods"
                                            :key="option.period"
                                            :label="option.name"
                                            :value="option.period">
                                        </el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.periods"
                                        class="form-control-feedback"
                                        v-text="errors.periods[0]">
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div
                                    :class="{'has-danger': errors.quantity_period}"
                                    class="form-group">
                                    <label class="control-label">
                                        Cantidad de Periodos
                                    </label>
                                    <el-input
                                        v-model="form_data.quantity_period"
                                    ></el-input>
                                    <small
                                        v-if="errors.quantity_period"
                                        class="form-control-feedback"
                                        v-text="errors.quantity_period[0]">
                                    </small>
                                </div>
                            </div>
                            <!--

                            <div class="col-md-3">
                                <div
                                    :class="{'has-danger': errors.payment_method_type_id}"
                                    class="form-group">
                                    <label class="control-label">
                                        Método de pago
                                    </label>

                                    <el-select
                                        v-model="form_data.payment_method_type_id"
                                        filterable>
                                        <el-option
                                            v-for="option in payment_method_types"
                                            :key="option.id" :value="option.id" :label="option.description"
                                        >
                                        </el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.payment_method_type_id"
                                        class="form-control-feedback"
                                        v-text="errors.payment_method_type_id[0]">
                                    </small>
                                </div>
                            </div>
                            -->

                            <div class="col-md-8">
                                <div
                                    :class="{'has-danger': errors.description}"
                                    class="form-group">
                                    <label class="control-label">
                                        Descripcion
                                    </label>
                                    <el-input
                                        v-model="form_data.description"
                                    ></el-input>
                                    <small
                                        v-if="errors.description"
                                        class="form-control-feedback"
                                        v-text="errors.description[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                </el-tab-pane>
                <el-tab-pane class
                             name="second">
                    <span slot="label">
                        Servicios en el plan
                    </span>
                    <div class="form-body">

                        <div class="row">

                            <div class="col-12">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="font-weight-bold">Descripción</th>
                                                <th class="text-center font-weight-bold">Unidad</th>
                                                <th class="text-right font-weight-bold">Cantidad</th>
                                                <th class="text-right font-weight-bold">Valor Unitario</th>
                                                <th class="text-right font-weight-bold">Precio Unitario</th>
                                                <th class="text-right font-weight-bold">Subtotal</th>
                                                <!--<th class="text-right font-weight-bold">Cargo</th>-->
                                                <th class="text-right font-weight-bold">Total</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody v-if="fakeForm.items !== undefined && fakeForm.items.length > 0">
                                            <tr v-for="(row, index) in fakeForm.items"
                                                :key="index">
                                                <td>{{ index + 1 }}</td>
                                                <td>
                                                    {{ setDescriptionOfItem(row.item) }}
                                                    {{
                                                        row.item.presentation.hasOwnProperty('description') ? row.item.presentation.description : ''
                                                    }}<br/><small>{{ row.affectation_igv_type.description }}</small>
                                                </td>
                                                <td class="text-center">{{ row.item.unit_type_id }}</td>
                                                <td class="text-right">{{ row.quantity }}</td>
                                                <!-- <td class="text-right">{{currency_type.symbol}} {{row.unit_price}}</td> -->
                                                <td class="text-right">{{ currency_type.symbol }}
                                                                       {{ getFormatUnitPriceRow(row.unit_value) }}
                                                </td>
                                                <td class="text-right">{{ currency_type.symbol }}
                                                                       {{ getFormatUnitPriceRow(row.unit_price) }}
                                                </td>

                                                <td class="text-right">{{ currency_type.symbol }}
                                                                       {{ row.total_value }}
                                                </td>
                                                <!--<td class="text-right">{{ currency_type.symbol }} {{ row.total_charge }}</td>-->
                                                <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>
                                                <td class="text-right">
                                                    <button class="btn waves-effect waves-light btn-xs btn-info"
                                                            type="button"
                                                            @click="ediItem(row, index)"><span style='font-size:10px;'>&#9998;</span>
                                                    </button>
                                                    <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                            type="button"
                                                            @click.prevent="clickRemoveItem(index)">x
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="9"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12  text-center">
                                    <div class="form-group">
                                        <button class="btn waves-effect waves-light btn-primary"
                                                type="button"
                                                @click="clickAddItem">+ Agregar Producto
                                        </button>
                                    </div>
                                </div>
                                <div class="col-xlg-push-6 col-6 text-right">
                                    <p v-if="fakeForm.total_exportation > 0"
                                       class="text-right">
                                        OP.EXPORTACIÓN: {{ currency_type.symbol }} {{ fakeForm.total_exportation }}
                                    </p>
                                    <p v-if="fakeForm.total_free > 0"
                                       class="text-right">
                                        OP.GRATUITAS: {{ currency_type.symbol }} {{ fakeForm.total_free }}
                                    </p>
                                    <p v-if="fakeForm.total_unaffected > 0"
                                       class="text-right">
                                        OP.INAFECTAS: {{ currency_type.symbol }} {{ fakeForm.total_unaffected }}
                                    </p>
                                    <p v-if="fakeForm.total_exonerated > 0"
                                       class="text-right">
                                        OP.EXONERADAS: {{ currency_type.symbol }} {{ fakeForm.total_exonerated }}
                                    </p>
                                    <p v-if="fakeForm.total_taxed > 0"
                                       class="text-right">
                                        OP.GRAVADA: {{ currency_type.symbol }} {{ fakeForm.total_taxed }}
                                    </p>
                                    <p v-if="fakeForm.total_igv > 0"
                                       class="text-right">
                                        IGV: {{ currency_type.symbol }} {{ fakeForm.total_igv }}
                                    </p>
                                    <h3 v-if="fakeForm.total > 0"
                                        class="text-right">
                                        <b>
                                            TOTAL DEL PLAN:
                                        </b>
                                        {{ currency_type.symbol }} {{ fakeForm.total }}
                                    </h3>
                                </div>
                                <div class="col-12"  v-if="errors.items">
                                    <small
                                        v-if="errors.items"
                                        class="form-control-feedback"
                                        v-text="errors.items[0]">
                                    </small>
                                </div>
                            </div>

                        </div>

                    </div>

                </el-tab-pane>

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
            :exchange-rate-sale="exchange_rate"
            :recordItem="recordItem"
            :showDialog.sync="showDialogAddItem"
            :typeUser="config.typeUser"
            :displayDiscount="false"
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

export default {
    mixins: [
        serviceNumber
    ],
    props: [
        'showDialog',
    ],
    data() {
        return {
            loading_submit: false,
            showDialogAddItem: false,
            titleDialog: null,
            errors: {},
            tabActive: 'first',
            currency_type: {},
            //countries: [],
            //all_departments: [],
            //all_provinces: [],
            //all_districts: [],
            provinces: [],
            districts: [],
            recordItem: null,
            fakeForm: {},
            //identity_document_types: []
        }
    },
    created() {
        this.initForm()
        this.$http
            .post(`/suscription/${this.resource}/tables`, {})
            .then(response => {
                this.$store.commit('setPeriods', response.data.periods)
            })
        this.getCommonData()
    },
    computed: {
        ...mapState([
            'config',
            'resource',
            'periods',
            'form_data',
            'exchange_rate',
            'currency_types',
            'affectation_igv_types',
            'unit_types',
            'payment_method_types',
        ]),
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
            'clearFormData',
            'EmitEvent',
        ]),

        getCommonData(){
            this.$http.post('/suscription/CommonData',{})
                .then((response)=>{
                    this.$store.commit('setCurrencyTypes',response.data.currency_types)
                    this.$store.commit('setAffectationIgvTypes',response.data.affectation_igv_types)
                    this.$store.commit('setUnitTypes',response.data.unit_types)
                    this.$store.commit('setPaymentMethodTypes', response.data.payments_credit)
                })
            .then(()=>{
                this.changeCurrencyType()
            })
        },
        clearForm() {
            let form = {
                id: null,
                cat_period_id: null,
                name: null,
                description: null,
                period: null,
                items: [],
                periods: 12,
                quantity_period : 'M'
            }
            this.$store.commit('setFormData', form)
        },
        initForm() {
            this.tabActive = 'first'
            this.errors = {}


            if (this.form_data === undefined) {
                this.clearForm();
            }
            this.titleDialog = (this.form_data.id) ? 'Editar Plan' : 'Nuevo Plan'
            if (this.form_data.items === undefined) this.form_data.items = [];
            if (this.fakeForm.currency_type_id === undefined) this.fakeForm.currency_type_id = this.config.currency_type_id;
            this.fakeForm = this.form_data

            this.changeCurrencyType()


        },
        create() {
            this.initForm()
            if (this.form_data.id) {
                this.$http
                    .post(`/suscription/${this.resource}/record`, {
                        person: this.form_data.id,
                    })
                    .then(response => {
                        this.$store.commit('setFormData', response.data.data)

                        // this.form = response.data.data
                        // this.filterProvinces()
                        // this.filterDistricts()
                    })
            }
        },

        submit() {
            this.loading_submit = true
            let data = this.form_data;
            // console.dir(data)
            this.$http.post(`/suscription/${this.resource}`, data)
                .then(response => {
                    this.$eventHub.$emit('reloadData')
                    // this.EmitEvent('reloadData')
                    this.close()
                })
                .catch(error => {
                    console.error(error)
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
            this.clearForm();
            this.clearFormData();
        },
        searchCustomer() {
            this.searchServiceNumberByType()
        },
        addRow(row) {
            /* Extraido de resources/js/views/tenant/quotations/form.vue */
            if (this.recordItem) {
                this.fakeForm.items[this.recordItem.indexi] = row
                this.recordItem = null

            } else {
                this.fakeForm.items.push(JSON.parse(JSON.stringify(row)));
            }
            this.$store.commit('setFormData', this.fakeForm)

            this.calculateTotal();
            this.$store.commit('setFormData', this.fakeForm)
        },
        clickAddItem() {
            this.recordItem = null;
            this.showDialogAddItem = true;
        },

        clickRemoveItem(index) {
            this.fakeForm.items.splice(index, 1)
            this.calculateTotal()
        },
        ediItem(row, index) {
            row.indexi = index
            this.recordItem = row
            this.showDialogAddItem = true
        },

        changeCurrencyType() {
            let currencyT =  this.config.currency_type_id;
            if( this.fakeForm.currency_type_id !== undefined){
                currencyT =  this.fakeForm.currency_type_id;
            }
            if(currencyT === null) {
                currencyT = this.config.currency_type_id;
                this.fakeForm.currency_type_id = this.config.currency_type_id;
            }
            this.currency_type = _.find(this.currency_types, {'id': currencyT})

            let items = []
            this.form_data.items.forEach((row) => {
                items.push(calculateRowItem(row, this.form_data.currency_type_id, this.form_data.exchange_rate_sale))
            });
            this.form_data.items = items
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

            this.fakeForm.items.forEach((row) => {
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

            this.fakeForm.total_igv_free = _.round(total_igv_free, 2)
            this.fakeForm.total_exportation = _.round(total_exportation, 2)
            this.fakeForm.total_taxed = _.round(total_taxed, 2)
            this.fakeForm.total_exonerated = _.round(total_exonerated, 2)
            this.fakeForm.total_unaffected = _.round(total_unaffected, 2)
            this.fakeForm.total_free = _.round(total_free, 2)
            this.fakeForm.total_igv = _.round(total_igv, 2)
            this.fakeForm.total_value = _.round(total_value, 2)
            this.fakeForm.total_taxes = _.round(total_igv, 2)
            this.fakeForm.total = _.round(total, 2)


            this.setTotalDefaultPayment()

        },

        setTotalDefaultPayment() {

            if (this.fakeForm.payments !== undefined && this.fakeForm.payments.length > 0) {

                this.fakeForm.payments[0].payment = this.fakeForm.total
            }
        },

        getFormatUnitPriceRow(number) {
            return FormatUnitPriceRow(number)
        },
        setDescriptionOfItem(item) {
            return showNamePdfOfDescription(item, this.config.show_pdf_name)
        }
    }
}
</script>
