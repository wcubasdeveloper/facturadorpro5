<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">
                Nuevo Ingreso de insumo
            </h3>
        </div>
        <div class="tab-content">
            <form autocomplete="off"
                  @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">

                        <div class="col-sm-12 col-md-6 col-lg-3 ">
                            <div :class="{'has-danger': errors.date_start}"
                                 class="form-group">
                                <label class="control-label">
                                    Fecha de inicio
                                </label>
                                <el-date-picker v-model="form.date_start"
                                                :clearable="false"
                                                format="dd/MM/yyyy"
                                                type="date"
                                                value-format="yyyy-MM-dd"></el-date-picker>
                                <small v-if="errors.date_start"
                                       class="form-control-feedback"
                                       v-text="errors.date_start[0]"></small>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3 ">
                            <div :class="{'has-danger': errors.time_start}"
                                 class="form-group">
                                <label class="control-label">Hora de inicio</label>
                                <el-time-picker v-model="form.time_start"
                                                dusk="time_start"
                                                value-format="HH:mm:ss"
                                                placeholder="Seleccionar"></el-time-picker>
                                <small v-if="errors.time_start"
                                       class="form-control-feedback"
                                       v-text="errors.time_start[0]"></small>
                            </div>
                        </div>


                        <div class="col-sm-12 col-md-6 col-lg-3 ">
                            <div :class="{'has-danger': errors.date_end}"
                                 class="form-group">
                                <label class="control-label">
                                    Fecha de Finalizacion
                                </label>
                                <el-date-picker v-model="form.date_end"
                                                :clearable="false"
                                                format="dd/MM/yyyy"
                                                type="date"
                                                value-format="yyyy-MM-dd"></el-date-picker>
                                <small v-if="errors.date_end"
                                       class="form-control-feedback"
                                       v-text="errors.date_end[0]"></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 ">
                            <div :class="{'has-danger': errors.time_end}"
                                 class="form-group">
                                <label class="control-label">Hora de finalizacion</label>
                                <el-time-picker v-model="form.time_end"
                                                dusk="time_end"
                                                value-format="HH:mm:ss"
                                                placeholder="Seleccionar"></el-time-picker>
                                <small v-if="errors.time_end"
                                       class="form-control-feedback"
                                       v-text="errors.time_end[0]"></small>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3 ">
                            <div :class="{'has-danger': errors.name}"
                                 class="form-group">
                                <label class="control-label">Número de Ficha</label>
                                <el-input v-model="form.name"></el-input>
                                <small v-if="errors.name"
                                       class="form-control-feedback"
                                       v-text="errors.name[0]"></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 ">
                            <div :class="{'has-danger': errors.comment}"
                                 class="form-group">
                                <label class="control-label">Comentario</label>
                                <el-input v-model="form.comment"></el-input>
                                <small v-if="errors.comment"
                                       class="form-control-feedback"
                                       v-text="errors.comment[0]"></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 ">
                            <div :class="{'has-danger': errors.mill_name}"
                                 class="form-group">
                                <label class="control-label">Molino</label>
                                <el-input v-model="form.mill_name"></el-input>
                                <small v-if="errors.mill_name"
                                       class="form-control-feedback"
                                       v-text="errors.mill_name[0]"></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 ">
                            <div :class="{'has-danger': errors.lot_code}"
                                 class="form-group">
                                <label class="control-label">Lote</label>
                                <el-input v-model="form.lot_code"></el-input>
                                <small v-if="errors.lot_code"
                                       class="form-control-feedback"
                                       v-text="errors.lot_code[0]"></small>
                            </div>
                        </div>
                        <!--

                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.item_extra_data}"
                                 class="form-group">
                                <label class="control-label">
                                    Color <<< pasarlo a item
                                </label>
                                <el-select v-model="form.item_extra_data.color"
                                           :disable="item === undefined || item.colors === undefined ||item.colors.length < 1"
                                           filterable>
                                    <el-option
                                        v-for="option in item.colors"

                                        :key="option.id"
                                        :label="option.color_name"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small v-if="errors.item_extra_data"
                                       class="form-control-feedback"
                                       v-text="errors.item_extra_data[0]"></small>
                            </div>
                        </div>
                        -->



                        <!--
                        <div class="col-lg-6">
                            <div :class="{'has-danger': errors.supplier_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Proveedor
                                    <a href="#"
                                       @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                </label>
                                <el-select v-model="form.supplier_id"
                                           filterable>
                                    <el-option v-for="option in suppliers"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.supplier_id"
                                       class="form-control-feedback"
                                       v-text="errors.supplier_id[0]"></small>
                            </div>
                        </div>
                        -->


                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-6 mt-4">
                            <div class="form-group">
                                <button class="btn waves-effect waves-light btn-primary"
                                        type="button"
                                        @click.prevent="showAddItemModal">+ Seleccionar Insumo
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
                                        <th class="font-weight-bold">Unidad</th>
                                        <th class="text-right font-weight-bold">Entrada</th>
                                        <th class="text-right font-weight-bold">Salida</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in form.items">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ ItemOptionDescriptionView(row) }}</td>
                                        <td>{{ ItemUnit(row) }}</td>
                                        <td class="text-right">{{ row.height_to_mill }}</td>
                                        <td class="text-right">{{ row.total_height }}</td>
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
                                class="text-right"><b>TOTAL: </b>{{ currency_type.symbol }} {{ form.total }}</h3>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button v-if="form.items.length > 0"
                               :loading="loading_submit"
                               native-type="submit"
                               type="primary">{{ (id) ? 'Actualizar' : 'Generar' }}
                    </el-button>
                </div>
            </form>
        </div>
        <mill-form-item
            :currency-type-id-active="form.currency_type_id"
            :exchange-rate-sale="form.exchange_rate_sale"
            :showDialog.sync="showDialog"
            @add="addRow"></mill-form-item>
        <!--



        <person-form :showDialog.sync="showDialogNewPerson"
                     type="suppliers"
                     :external="true"></person-form>

        <mill-options :showDialog.sync="showDialogOptions"
                         :recordId="millNewId"
                         :isUpdate="id ? true:false"
                         :showClose="false"></mill-options>
        -->
    </div>
</template>

<script>
import MillFormItem from './partials/item.vue'

/*
import PersonForm from '../../../../../../../resources/js/views/tenant/persons/form.vue'
import ExpenseOptions from './partials/options.vue'
*/
import {exchangeRate, functions} from '../../../../../../../resources/js/mixins/functions'
import {ItemOptionDescription} from "../../../../../../../resources/js/helpers/modal_item";


export default {
    props: ['id'],
    components: {
        MillFormItem
        /*
        ,
        PersonForm,
        ExpenseOptions
        */
    },
    mixins: [
        functions,
        exchangeRate],
    data() {
        return {
            resource: 'mill-production',
            showDialog: false,
            showDialogNewPerson: false,
            showDialogOptions: false,
            loading_submit: false,
            errors: {},
            form: {
                items:[],
                item_extra_data: {
                    color:null
                },
            },
            aux_supplier_id: null,
            mill_types: [],
            unit_types: [],
            currency_types: [],
            suppliers: [],
            establishment: {},
            currency_type: {},
            mill_method_types: [],
            payment_destinations: [],
            mill_reasons: [],
            millNewId: null
        }
    },
    async created() {
        await this.initForm()
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {

                this.mill_reasons = response.data.mill_reasons
                this.mill_method_types = response.data.mill_method_types
                this.mill_types = response.data.mill_types
                this.currency_types = response.data.currency_types
                this.establishment = response.data.establishment
                this.suppliers = response.data.suppliers
                // this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
                this.form.establishment_id = (this.establishment.id) ? this.establishment.id : null
                // this.form.mill_type_id = (this.mill_types.length > 0) ? this.mill_types[0].id : null
                // this.form.mill_reason_id = (this.mill_reasons.length > 0) ? this.mill_reasons[0].id : null
                this.payment_destinations = response.data.payment_destinations
                this.unit_types = response.data.unit_types

                this.changeDateOfIssue()

                this.changeCurrencyType()
            })

        await this.$eventHub.$on('reloadDataPersons', (supplier_id) => {
            this.reloadDataSuppliers(supplier_id)
        })

        await this.isUpdate()
    },
    methods: {
        showAddItemModal(){
            this.showDialog = true;
        },
        setTime(timePicker) {
            this.form.time_start = `${moment(timePicker).format('HH:mm')}:00`;
        },
        setTimeEnd(timePicker) {
            this.form.time_end = `${moment(timePicker).format('HH:mm')}:00`;
        },
        async isUpdate() {

            if (this.id) {

                await this.$http.get(`/${this.resource}/record/${this.id}`)
                    .then(response => {
                        this.form = response.data.data.mill
                    })
            }

        },
        changeExpenseMethodType(index = 0) {

            //this.form.payments[index].payment_destination_id = (this.payment_destinations.length > 0 && this.form.payments[index].mill_method_type_id != 1) ? this.payment_destinations[0].id : null
            //this.form.payments[index].payment_destination_disabled = (this.form.payments[index].mill_method_type_id == 1) ? true : false

        },
        selectSupplier() {
            /*

            let supplier = _.find(this.suppliers, {'id': this.aux_supplier_id})
            this.form.supplier_id = (supplier) ? supplier.id : null
            this.aux_supplier_id = null
*/
        },
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                date_start: moment().format('YYYY-MM-DD'),
                time_start: moment().format('HH:mm'),
                date_end: moment().format('YYYY-MM-DD'),
                time_end: moment().format('HH:mm'),
                items:[],
                item_extra_data: {
                    color:null
                },

                /*
                establishment_id: null,
                mill_type_id: null,
                mill_reason_id: null,
                number: null,
                date_of_issue: moment().format('YYYY-MM-DD'),
                time_of_issue: moment().format('HH:mm:ss'),
                supplier_id: null,
                currency_type_id: null,
                exchange_rate_sale: 0,
                total: 0,
                payments: [],
                */
            }

            // this.changeExpenseMethodType()
        },
        resetForm() {
            this.initForm()
            // this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
            this.form.establishment_id = this.establishment.id
            // this.form.mill_type_id = (this.mill_types.length > 0) ? this.mill_types[0].id : null
            // this.form.mill_reason_id = (this.mill_reasons.length > 0) ? this.mill_reasons[0].id : null

            this.changeDateOfIssue()
            this.changeCurrencyType()
        },
        changeDateOfIssue() {
            /*
            this.form.date_of_due = this.form.date_of_issue
            this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                this.form.exchange_rate_sale = response
            })
            */
        },
        clickCancel(index) {
            // this.form.payments.splice(index, 1);
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
            // this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
            let items = []
            this.form.items.forEach((row) => {
                items.push(row)
                // items.push(this.calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))


            });
            this.form.items = items
            this.calculateTotal()
        },
        /*
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
        },*/
        calculateTotal() {
            /*
            let height_to_mill = 0
            let total_height = 0
            this.form.items.forEach((row) => {
                height_to_mill += parseFloat(row.height_to_mill)
                total_height += parseFloat(row.total_height)
            });
            this.form.total = _.round(total, 2)
            this.form.payments[0].payment = this.form.total
            */
        },
        submit() {

            this.loading_submit = true
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {

                    if (response.data.success) {
                        this.initForm()
                        //this.millNewId = response.data.data.id
                        //this.showDialogOptions = true
                        this.isUpdate()

                        this.$message.success(response.data.message)

                        window.location.href = '/mill-production'


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
        close() {
            location.href = `/${this.resource}`
        },
        reloadDataSuppliers(supplier_id) {

            this.$http.get(`/${this.resource}/table/suppliers`).then((response) => {

                this.form.supplier_id = supplier_id
                this.suppliers = response.data

            })
        },

        ItemOptionDescriptionView(item) {
            return ItemOptionDescription(item)
        },
        ItemUnit(row){
            // unit_types
            let t = _.find(this.unit_types,{id:row.unit_type_id})
            if(t !== undefined){
                return t.description
            }
            return row.unit_type_id
        }
    }
}
</script>
