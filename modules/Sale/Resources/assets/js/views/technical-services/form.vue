<template>
    <el-dialog :title="titleDialog"
               :visible="showDialog"
               append-to-body
               @close="close"
               @open="create">
        <form autocomplete="off"
              @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <!-- Cliente -->
                    <div class="col-md-6 pb-2">
                        <div :class="{'has-danger': errors.customer_id}"
                             class="form-group">
                            <label class="control-label font-weight-bold text-info">
                                Cliente
                                <a href="#"
                                   @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                            </label>
                            <el-select v-model="form.customer_id"
                                       :loading="loading_search"
                                       :remote-method="searchRemoteCustomers"
                                       class="border-left rounded-left border-info"
                                       dusk="customer_id"
                                       filterable
                                       placeholder="Escriba el nombre o número de documento del cliente"
                                       popper-class="el-select-customers"
                                       remote>

                                <el-option v-for="option in customers"
                                           :key="option.id"
                                           :label="option.description"
                                           :value="option.id"></el-option>

                            </el-select>
                            <small v-if="errors.customer_id"
                                   class="form-control-feedback"
                                   v-text="errors.customer_id[0]"></small>
                        </div>
                    </div>

                    <!-- Telefono Contacto -->
                    <div class="col-md-6 pb-2">
                        <div :class="{'has-danger': errors.cellphone}"
                             class="form-group">
                            <label class="control-label">Celular </label>
                            <el-input v-model="form.cellphone"></el-input>
                            <small v-if="errors.cellphone"
                                   class="form-control-feedback"
                                   v-text="errors.cellphone[0]"></small>
                        </div>
                    </div>
                    <!-- Descripcion -->
                    <div class="col-md-12">
                        <div :class="{'has-danger': errors.description}"
                             class="form-group">
                            <label class="control-label">Descripción </label>
                            <el-input v-model="form.description"
                                      type="textarea"></el-input>
                            <small v-if="errors.description"
                                   class="form-control-feedback"
                                   v-text="errors.description[0]"></small>
                        </div>
                    </div>
                </div>
                <el-tabs v-model="activeName">
                    <el-tab-pane class="mb-3"
                                 name="first">
                        <span slot="label"><h3>General</h3></span>
                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.reason}"
                                     class="form-group">
                                    <label class="control-label">Motivo de ingreso</label>
                                    <el-input v-model="form.reason"
                                              type="textarea"></el-input>
                                    <small v-if="errors.reason"
                                           class="form-control-feedback"
                                           v-text="errors.reason[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.state}"
                                     class="form-group">
                                    <label class="control-label">Estado </label>
                                    <el-input v-model="form.state"
                                              type="textarea"></el-input>
                                    <small v-if="errors.state"
                                           class="form-control-feedback"
                                           v-text="errors.state[0]"></small>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.serial_number}"
                                     class="form-group">
                                    <label class="control-label">Número de serie </label>
                                    <el-input v-model="form.serial_number"></el-input>
                                    <small v-if="errors.serial_number"
                                           class="form-control-feedback"
                                           v-text="errors.serial_number[0]"></small>
                                </div>
                            </div>
                            <!--
                            <div class="col-md-3 align-self-end">
                                <div :class="{'has-danger': errors.currency_type_id}"
                                     class="form-group">
                                    <label class="control-label">Moneda</label>
                                    <el-select v-model="form.currency_type_id"
                                               @change="changeCurrencyType">
                                        <el-option v-for="option in currency_types"
                                                   :key="option.id"
                                                   :label="option.description"
                                                   :value="option.id"></el-option>
                                    </el-select>
                                    <small v-if="errors.currency_type_id"
                                           class="form-control-feedback"
                                           v-text="errors.currency_type_id[0]"></small>
                                </div>
                            </div>
                            -- >
                            <div class="col-md-3 align-self-end">
                                <div :class="{'has-danger': errors.exchange_rate_sale}"
                                     class="form-group">
                                    <label class="control-label">Tipo de cambio
                                        <el-tooltip class="item"
                                                    content="Tipo de cambio del día, extraído de SUNAT"
                                                    effect="dark"
                                                    placement="top-end">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </label>
                                    <el-input v-model="form.exchange_rate_sale"></el-input>
                                    <small v-if="errors.exchange_rate_sale"
                                           class="form-control-feedback"
                                           v-text="errors.exchange_rate_sale[0]"></small>
                                </div>
                            </div>
                            <! -- <div class="col-md-6">
                                <div class="form-group" :class="{'has-danger': errors.prepayment}">
                                    <label class="control-label">Pago adelantado </label>
                                    <el-input v-model="form.prepayment" ></el-input>
                                    <small class="form-control-feedback" v-if="errors.prepayment" v-text="errors.prepayment[0]"></small>
                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.brand}"
                                     class="form-group">
                                    <label class="control-label">Marca </label>
                                    <el-input v-model="form.brand"></el-input>
                                    <small v-if="errors.brand"
                                           class="form-control-feedback"
                                           v-text="errors.brand[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.equipment}"
                                     class="form-group">
                                    <label class="control-label">Equipo </label>
                                    <el-input v-model="form.equipment"></el-input>
                                    <small v-if="errors.equipment"
                                           class="form-control-feedback"
                                           v-text="errors.equipment[0]"></small>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.cost}"
                                     class="form-group">
                                    <label class="control-label">Costo del Servicio</label>
                                    <el-input-number
                                        v-model="form.cost"
                                        :controls="false"
                                        :min="0"
                                        @change="updateCost"></el-input-number>
                                    <small v-if="errors.cost"
                                           class="form-control-feedback"
                                           v-text="errors.cost[0]"></small>
                                </div>
                            </div>

                            <div v-if="recordId"
                                 class="col-md-12">
                                <div :class="{'has-danger': errors.activities}"
                                     class="form-group">
                                    <label class="control-label">Actividades realizadas (Equipo internado)</label>
                                    <el-input v-model="form.activities"
                                              type="textarea"></el-input>
                                    <small v-if="errors.activities"
                                           class="form-control-feedback"
                                           v-text="errors.activities[0]"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row"
                             style="padding:2%;">
                            <div class="col-md-3">
                                <el-checkbox v-model="form.repair">Reparación</el-checkbox>
                            </div>
                            <div class="col-md-3">
                                <el-checkbox v-model="form.warranty">Garantía</el-checkbox>
                            </div>
                            <div class="col-md-3">
                                <el-checkbox v-model="form.maintenance">Mantenimiento</el-checkbox>
                            </div>
                            <div class="col-md-3">
                                <el-checkbox v-model="form.diagnosis">Diagnostico</el-checkbox>
                            </div>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane class="mb-3"
                                 name="second">
                        <span slot="label"><h3>Notas</h3></span>
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="control-label">
                                        Notas
                                        <a class="text-center font-weight-bold text-info"
                                           href="#"
                                           @click.prevent="clickAddNote">[+ Agregar]</a>
                                    </label>

                                    <table style="width: 100%">
                                        <tr v-for="(guide,index) in form.important_note"
                                            :key="index">
                                            <td>
                                                <el-input v-model="guide.description"></el-input>
                                            </td>
                                            <td align="center">
                                                <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                        type="button"
                                                        @click.prevent="clickRemoveGuide(index)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <!-- <a href="#" @click.prevent="clickRemoveGuide" style="color:red">Remover</a> -->
                                            </td>
                                        </tr>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </el-tab-pane>

                    <el-tab-pane class="mb-3"
                                 name="third">
                        <span slot="label"><h3>Productos</h3></span>
                        <div class="row">
                            <div class="col-12 row ">
                                <!--
                                                                <div class="col-lg-2 align-self-end">
                                                                    <div :class="{'has-danger': errors.operation_type_id}"
                                                                         class="form-group">
                                                                        <label class="control-label">Tipo Operación
                                                                            <template v-if="(form.operation_type_id == '1001' || form.operation_type_id == '1004') && has_data_detraction">
                                                                                <a class="text-center font-weight-bold text-info"
                                                                                   href="#"
                                                                                   @click.prevent="showDialogDocumentDetraction = true"> [+ Ver
                                                                                                                                         datos]</a>
                                                                            </template>

                                                                        </label>
                                                                        <el-select v-model="form.operation_type_id"
                                                                                   @change="changeOperationType">
                                                                            <el-option v-for="option in operation_types"
                                                                                       :key="option.id"
                                                                                       :label="option.description"
                                                                                       :value="option.id"></el-option>
                                                                        </el-select>
                                                                        <small v-if="errors.operation_type_id"
                                                                               class="form-control-feedback"
                                                                               v-text="errors.operation_type_id[0]"></small>
                                                                    </div>
                                                                </div>
                                                                -->

                            </div>
                            <div class="col-12">

                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th class="font-weight-bold"
                                                width="30%">Descripción
                                            </th>
                                            <th class="text-center font-weight-bold">Unidad</th>
                                            <th class="text-right font-weight-bold">Cantidad</th>
                                            <th class="text-right font-weight-bold">Valor Unitario</th>
                                            <th class="text-right font-weight-bold">Precio Unitario</th>
                                            <th class="text-right font-weight-bold">Subtotal</th>
                                            <!--<th class="text-right font-weight-bold">Cargo</th>-->
                                            <th class="text-right font-weight-bold">Total</th>
                                            <th width="8%"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.items"
                                            :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ row.item.description }}
                                                {{
                                                    row.item.presentation.hasOwnProperty('description') ? row.item.presentation.description : ''
                                                }}<br/><small>
                                                    {{
                                                        getDescriptionFromAffectationIgvType(row.affectation_igv_type_id)
                                                    }}
                                                </small>
                                            </td>
                                            <td class="text-center">{{ row.item.unit_type_id }}</td>

                                            <td class="text-right">{{ row.quantity }}</td>

                                            <td class="text-right">{{ currency_type.symbol }}
                                                                   {{ getFormatUnitPriceRow(row.unit_value) }}
                                            </td>
                                            <td class="text-right">{{ currency_type.symbol }}
                                                                   {{ getFormatUnitPriceRow(row.unit_price) }}
                                            </td>


                                            <td class="text-right">{{ currency_type.symbol }} {{ row.total_value }}</td>
                                            <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>
                                            <td class="text-right">
                                                <template v-if="config.change_free_affectation_igv">
                                                    <el-tooltip class="item"
                                                                content="Modificar afectación Gravado – Bonificaciones"
                                                                effect="dark"
                                                                placement="top-start">
                                                        <el-checkbox v-model="row.item.change_free_affectation_igv"
                                                                     @change="changeRowFreeAffectationIgv(row, index)"></el-checkbox>
                                                    </el-tooltip>
                                                </template>

                                                <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                        type="button"
                                                        @click.prevent="clickRemoveItem(index)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="btn waves-effect waves-light btn-xs btn-info"
                                                        type="button"
                                                        @click="ediItem(row, index)">
                                                    <span style='font-size:10px;'>&#9998;</span></button>

                                            </td>
                                        </tr>
                                        <tr>

                                            <td class="text-center pt-3"
                                                colspan="9" >
                                                <button class="btn waves-effect waves-light btn-primary btn-sm hidden-sm-down"
                                                        style="width: 180px;"
                                                        type="button"
                                                        :disabled="load_record"
                                                        @click.prevent="clickAddItemInvoice">+ Agregar Producto
                                                </button>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </el-tab-pane>

                </el-tabs>

            </div>
            <div class="row">
                <div class="col-12 text-right text-sm">
                    Total de servicio tecnico {{  total.toLocaleString() }}
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button :loading="loading_submit"
                           native-type="submit"
                           type="primary">Guardar
                </el-button>
            </div>
        </form>


        <tenant-documents-items-list
            :config="config"
            :currency-type-id-active="form.currency_type_id"
            :displayDiscount="false"
            :editNameProduct="config.edit_name_product"
            :exchange-rate-sale="form.exchange_rate_sale"

            :isEditItemNote="false"
            :operationTypeId="'0101'"
            :recordItem="recordItem"
            :showDialog.sync="showDialogAddItem"
            :typeUser="config.typeUser"
            @add="addRow"
        >

        </tenant-documents-items-list>

        <person-form :document_type_id=form.document_type_id
                     :external="true"
                     :exchange-rate-sale="form.exchange_rate_sale"
                     :currency_types = currency_types
                     :showDialog.sync="showDialogNewPerson"
                     type="customers"></person-form>
    </el-dialog>
</template>
<script>
import PersonForm from '@views/persons/form.vue'
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import {calculateRowItem} from "../../../../../../../resources/js/helpers/functions";
import moment from "moment";
import {exchangeRate, functions} from "../../../../../../../resources/js/mixins/functions";

export default {
    props: [
        'showDialog',
        'recordId'
    ],
    computed: {
        ...mapState([
            'exchange_rate',
            'config',
            'currency_types',
        ]),
    },
    mixins: [functions, exchangeRate],
    components: {PersonForm},
    data() {
        return {
            load_record: true,
            showDialogNewPerson: false,
            showDialogAddItem: false,
            recordItem: null,
            activeName: 'first',
            loading_submit: false,
            all_customers: [],
            customers: [],
            titleDialog: null,
            resource: 'technical-services',
            loading_search: false,
            errors: {},
            total: 0,
            form: {
                operation_type_id: "0101",
            },
            datEmision: {
                disabledDate(time) {
                    return time.getTime() > moment();
                }
            },
            default_document_type: null,
            default_series_type: null,
            dateValid: false,
            input_person: {},
            showDialogDocumentDetraction: false,
            has_data_detraction: false,
            showDialogFormHotel: false,
            showDialogFormTransport: false,
            is_client: false,
            showDialogOptions: false,
            loading_form: false,
            document_types: [],
            // currency_types: [],
            discount_types: [],
            charges_types: [],
            business_turns: [],
            form_payment: {},
            document_types_guide: [],
            sellers: [],
            company: null,
            document_type_03_filter: null,
            operation_types: [],
            establishments: [],
            payment_method_types: [],
            establishment: null,
            all_series: [],
            series: [],
            prepayment_documents: [],
            currency_type: {},
            documentNewId: null,
            prepayment_deduction: false,
            activePanel: 0,
            total_global_discount: 0,
            total_global_charge: 0,
            is_amount: true,
            enabled_discount_global: false,
            user: null,
            is_receivable: false,
            is_contingency: false,
            cat_payment_method_types: [],
            select_first_document_type_03: false,
            detraction_types: [],
            all_detraction_types: [],
            customer_addresses: [],
            payment_destinations: [],
            form_cash_document: {},
            enabled_payments: true,
            readonly_date_of_due: false,
            seller_class: 'col-lg-6 pb-2',
            btnText: 'Generar',
            payment_conditions: [],
            affectation_igv_types: [],
            total_discount_no_base: 0,

        }
    },
    async created() {
        this.load_record = true
        this.loadConfiguration()
        this.loadExchangeRate()
        this.loadCurrencyTypes()
        await this.initForm()
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.all_customers = response.data.customers
                this.allCustomers()

                this.document_types = response.data.document_types_invoice;
                this.document_types_guide = response.data.document_types_guide;
                // this.currency_types = response.data.currency_types
                this.$store.commit('setCurrencyTypes',response.data.currency_types)
                this.business_turns = response.data.business_turns
                this.establishments = response.data.establishments
                this.operation_types = response.data.operation_types
                this.all_series = response.data.series
                this.sellers = response.data.sellers
                this.discount_types = response.data.discount_types
                this.charges_types = response.data.charges_types
                this.payment_method_types = response.data.payment_method_types
                this.enabled_discount_global = response.data.enabled_discount_global
                this.company = response.data.company;
                this.user = response.data.user;
                this.document_type_03_filter = response.data.document_type_03_filter;
                this.select_first_document_type_03 = response.data.select_first_document_type_03
                // this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null;
                this.form.establishment_id = (this.establishments.length > 0) ? this.establishments[0].id : null;
                this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null;
                this.form.operation_type_id = (this.operation_types.length > 0) ? this.operation_types[0].id : null;
                this.form.seller_id = (this.sellers.length > 0) ? this.idUser : null;
                this.affectation_igv_types = response.data.affectation_igv_types
                // this.prepayment_documents = response.data.prepayment_documents;
                this.is_client = response.data.is_client;
                // this.cat_payment_method_types = response.data.cat_payment_method_types;
                // this.all_detraction_types = response.data.detraction_types;
                this.payment_destinations = response.data.payment_destinations
                this.payment_conditions = response.data.payment_conditions;

                this.seller_class = (this.user == 'admin') ? 'col-lg-4 pb-2' : 'col-lg-6 pb-2';

                this.default_document_type = response.data.document_id;
                this.default_series_type = response.data.series_id;
            }).then(() => {
                this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                    this.$store.commit('setExchangeRate', response)
                    this.form.exchange_rate_sale = this.exchange_rate

                });
            }).finally(()=>{
                this.load_record = false
            })

        this.$eventHub.$on('reloadDataPersons', (customer_id) => {
            this.reloadDataCustomers(customer_id)
        })
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
            'loadExchangeRate',
            'loadCurrencyTypes',
        ]),
        async changeOperationType() {
            this.form.customer_id = null
            await this.filterCustomers();
            await this.setDataDetraction();
        },

        getDescriptionFromAffectationIgvType(type_id) {
            let t = this.findAffectationIgvType(type_id);
            if (t.description !== undefined) {
                return t.description
            }
            return null;
        },
        findAffectationIgvType(type_id) {

            let affectation_igv_type = _.find(this.affectation_igv_types, {id: type_id})
            if (affectation_igv_type === undefined) {
                affectation_igv_type = {
                    active: 1,
                    description: "Gravado - Operación Onerosa",
                    exportation: 0,
                    free: 0,
                    id: "10",
                }
            }
            return affectation_igv_type;
        },
        async changeRowFreeAffectationIgv(row, index) {

            if (row.item.change_free_affectation_igv) {

                this.form.items[index].affectation_igv_type_id = '15'
                this.form.items[index].affectation_igv_type = await _.find(this.affectation_igv_types, {id: this.form.items[index].affectation_igv_type_id})

            } else {

                this.form.items[index].affectation_igv_type_id = this.form.items[index].item.original_affectation_igv_type_id
                this.form.items[index].affectation_igv_type = await _.find(this.affectation_igv_types, {id: this.form.items[index].affectation_igv_type_id})
            }

            this.form.items[index] = await calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale)
            await this.calculateTotal()

        },
        changeCurrencyType() {
            this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
            let items = []
            this.form.items.forEach((row) => {
                items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
            });
            this.form.items = items
            this.calculateTotal()
        },
        async setDataDetraction() {

            if (this.form.operation_type_id === '1001') {

                this.showDialogDocumentDetraction = true

                // this.$message.warning('Sujeta a detracción');
                // await this.filterDetractionTypes();
                let legend = await _.find(this.form.legends, {'code': '2006'})
                if (!legend) this.form.legends.push({code: '2006', value: 'Operación sujeta a detracción'})
                this.form.detraction.bank_account = this.company.detraction_account

            } else if (this.form.operation_type_id === '1004') {

                this.showDialogDocumentDetraction = true
                let legend = await _.find(this.form.legends, {'code': '2006'})
                if (!legend) this.form.legends.push({
                    code: '2006',
                    value: 'Operación Sujeta a Detracción - Servicios de Transporte - Carga'
                })
                this.form.detraction.bank_account = this.company.detraction_account

            } else {

                _.remove(this.form.legends, {'code': '2006'})
                this.form.detraction = {}

            }
        },
        filterCustomers() {
            if (['0101', '1001', '1004'].includes(this.form.operation_type_id)) {

                if (this.form.document_type_id === '01') {
                    this.customers = _.filter(this.all_customers, {'identity_document_type_id': '6'})
                } else {
                    if (this.document_type_03_filter) {
                        this.customers = _.filter(this.all_customers, (c) => {
                            return c.identity_document_type_id !== '6'
                        })
                    } else {
                        this.customers = this.all_customers
                    }
                }

            } else {
                this.customers = this.all_customers
            }
        }, clickAddItemInvoice() {
            this.recordItem = null
            this.showDialogAddItem = true
        },

        async ediItem(row, index) {
            row.indexi = index
            // se evalua que sea numero. sino lo es sera cero
            let val = parseFloat(row.unit_price)
            if (isNaN(val)) val = 0;
            // Valor de precio unitario, si no se ajusta, será NaN
            row.input_unit_price_value = val
            this.recordItem = row
            this.showDialogAddItem = true
        },
        addRow(row) {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
            if (this.recordItem) {
                //this.form.items.$set(this.recordItem.indexi, row)
                this.form.items[this.recordItem.indexi] = row
                this.recordItem = null
            } else {
                this.form.items.push(JSON.parse(JSON.stringify(row)));
            }

            this.calculateTotal();
        },
        calculateTotal() {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
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
            this.total_discount_no_base = 0

            let total_igv_free = 0

            // let total_free_igv = 0

            this.form.items.forEach((row) => {
                total_discount += parseFloat(row.total_discount)
                total_charge += parseFloat(row.total_charge)

                if (row.affectation_igv_type_id === '10') {
                    total_taxed += parseFloat(row.total_value)
                }

                if (
                    row.affectation_igv_type_id === '20'  // 20,Exonerado - Operación Onerosa
                    // || row.affectation_igv_type_id === '21' // 21,Exonerado – Transferencia Gratuita
                ) {
                    total_exonerated += parseFloat(row.total_value)
                }

                if (
                    row.affectation_igv_type_id === '30'  // 30,Inafecto - Operación Onerosa
                    || row.affectation_igv_type_id === '31'  // 31,Inafecto – Retiro por Bonificación
                    || row.affectation_igv_type_id === '32'  // 32,Inafecto – Retiro
                    || row.affectation_igv_type_id === '33'  // 33,Inafecto – Retiro por Muestras Médicas
                    || row.affectation_igv_type_id === '34'  // 34,Inafecto - Retiro por Convenio Colectivo
                    || row.affectation_igv_type_id === '35'  // 35,Inafecto – Retiro por premio
                    || row.affectation_igv_type_id === '36' // 36,Inafecto - Retiro por publicidad
                    // || row.affectation_igv_type_id === '37'  // 37,Inafecto - Transferencia gratuita
                ) {
                    total_unaffected += parseFloat(row.total_value)
                }

                if (row.affectation_igv_type_id === '40') {
                    total_exportation += parseFloat(row.total_value)
                }

                if (['10',
                    // '20', '21',
                    '20',
                    '30', '31', '32', '33', '34', '35', '36',
                    '40'].indexOf(row.affectation_igv_type_id) < 0) {
                    total_free += parseFloat(row.total_value)
                }

                if (['10',
                    '20', '21',
                    '30', '31', '32', '33', '34', '35', '36',
                    '40'].indexOf(row.affectation_igv_type_id) > -1) {
                    total_igv += parseFloat(row.total_igv)
                    total += parseFloat(row.total)
                }

                // console.log(row.total_value)

                if (!['21', '37'].includes(row.affectation_igv_type_id)) {
                    total_value += parseFloat(row.total_value)
                }

                total_plastic_bag_taxes += parseFloat(row.total_plastic_bag_taxes)

                if (['12', '13', '14', '15', '16'].includes(row.affectation_igv_type_id)) {

                    let unit_value = row.total_value / row.quantity
                    let total_value_partial = unit_value * row.quantity
                    row.total_taxes = row.total_value - total_value_partial
                    row.total_igv = total_value_partial * (row.percentage_igv / 100)
                    row.total_base_igv = total_value_partial
                    total_value -= row.total_value

                    total_igv_free += row.total_igv

                }

                //sum discount no base
                this.total_discount_no_base += this.sumDiscountsNoBaseByItem(row)

            });


            this.form.total_igv_free = _.round(total_igv_free, 2)
            this.form.total_discount = _.round(total_discount, 2)
            this.form.total_exportation = _.round(total_exportation, 2)
            this.form.total_taxed = _.round(total_taxed, 2)
            this.form.total_exonerated = _.round(total_exonerated, 2)
            this.form.total_unaffected = _.round(total_unaffected, 2)
            this.form.total_free = _.round(total_free, 2)
            // this.form.total_igv = _.round(total_igv + total_free_igv, 2)
            this.form.total_igv = _.round(total_igv, 2)
            this.form.total_value = _.round(total_value, 2)
            this.form.total_taxes = _.round(total_igv, 2)
            this.form.total_plastic_bag_taxes = _.round(total_plastic_bag_taxes, 2)
            // this.form.total = _.round(total, 2)
            this.form.subtotal = _.round(total + this.form.total_plastic_bag_taxes, 2)
            this.form.total = _.round(total + this.form.total_plastic_bag_taxes - this.total_discount_no_base, 2)

            if (this.enabled_discount_global)
                this.discountGlobal()

            if (this.prepayment_deduction)
                this.discountGlobalPrepayment()

            if (['1001', '1004'].includes(this.form.operation_type_id))
                this.changeDetractionType()

            this.setTotalDefaultPayment()
            this.setPendingAmount()

            this.calculateFee();

            this.chargeGlobal()
            this.updateCost()
        },

        updateCost() {
            let cost = parseFloat(this.form.cost)
            let total = parseFloat(this.form.total)
            if (isNaN(cost)) {
                this.form.cost = 0;
            }
            if (isNaN(total)) {
                this.form.total = 0;
            }
            this.total = this.form.cost + this.form.total
        },
        getFormatUnitPriceRow(unit_price) {
            return _.round(unit_price, 6)
            // return unit_price.toFixed(6)
        },
        discountGlobalPrepayment() {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
            let global_discount = 0
            let sum_total_prepayment = 0

            this.form.prepayments.forEach((item) => {
                global_discount += parseFloat(item.amount)
                sum_total_prepayment += parseFloat(item.total)
            })

            // let base = (this.form.affectation_type_prepayment == 10) ? parseFloat(this.form.total_taxed):parseFloat(this.form.total_exonerated)
            let base = 0

            switch (this.form.affectation_type_prepayment) {
                case 10:
                    base = parseFloat(this.form.total_taxed) + global_discount
                    // base = parseFloat(this.form.total_taxed)
                    break;
                case 20:
                    base = parseFloat(this.form.total_exonerated) + global_discount
                    break;
                case 30:
                    base = parseFloat(this.form.total_unaffected) + global_discount
                    break;
            }

            let amount = _.round(global_discount, 2)
            let factor = _.round(amount / base, 4)

            this.form.total_prepayment = _.round(sum_total_prepayment, 2)
            // this.form.total_prepayment = _.round(global_discount, 2)

            if (this.form.affectation_type_prepayment == 10) {


                let discount = _.find(this.form.discounts, {'discount_type_id': '04'})

                if (global_discount > 0 && !discount) {

                    this.form.total_discount = _.round(amount, 2)
                    this.form.total_taxed = _.round(this.form.total_taxed - amount, 2)
                    this.form.total_igv = _.round(this.form.total_taxed * 0.18, 2)
                    this.form.total_taxes = _.round(this.form.total_igv, 2)
                    this.form.total = _.round(this.form.total_taxed + this.form.total_taxes, 2)

                    this.form.discounts.push({
                        discount_type_id: "04",
                        description: "Descuentos globales por anticipos gravados que afectan la base imponible del IGV/IVAP",
                        factor: factor,
                        amount: amount,
                        base: base
                    })

                } else {

                    let pos = this.form.discounts.indexOf(discount);

                    if (pos > -1) {

                        this.form.total_discount = _.round(amount, 2)
                        this.form.total_taxed = _.round(this.form.total_taxed - amount, 2)
                        this.form.total_igv = _.round(this.form.total_taxed * 0.18, 2)
                        this.form.total_taxes = _.round(this.form.total_igv, 2)
                        this.form.total = _.round(this.form.total_taxed + this.form.total_taxes, 2)

                        this.form.discounts[pos].base = base
                        this.form.discounts[pos].amount = amount
                        this.form.discounts[pos].factor = factor

                    }

                }

            } else if (this.form.affectation_type_prepayment == 20) {

                let exonerated_discount = _.find(this.form.discounts, {'discount_type_id': '05'})

                this.form.total_discount = _.round(amount, 2)
                this.form.total_exonerated = _.round(this.form.total_exonerated - amount, 2)
                this.form.total = this.form.total_exonerated

                if (global_discount > 0 && !exonerated_discount) {
                    this.form.discounts.push({
                        discount_type_id: '05',
                        description: 'Descuentos globales por anticipos exonerados',
                        factor: factor,
                        amount: amount,
                        base: base
                    })

                } else {

                    let position = this.form.discounts.indexOf(exonerated_discount);

                    if (position > -1) {

                        this.form.discounts[position].base = base
                        this.form.discounts[position].amount = amount
                        this.form.discounts[position].factor = factor

                    }

                }

            } else if (this.form.affectation_type_prepayment == 30) {

                let unaffected_discount = _.find(this.form.discounts, {'discount_type_id': '06'})

                this.form.total_discount = _.round(amount, 2)
                this.form.total_unaffected = _.round(this.form.total_unaffected - amount, 2)
                this.form.total = this.form.total_unaffected

                if (global_discount > 0 && !unaffected_discount) {
                    this.form.discounts.push({
                        discount_type_id: '06',
                        description: 'Descuentos globales por anticipos inafectos',
                        factor: factor,
                        amount: amount,
                        base: base
                    })
                } else {
                    let position = this.form.discounts.indexOf(unaffected_discount);
                    if (position > -1) {
                        this.form.discounts[position].base = base
                        this.form.discounts[position].amount = amount
                        this.form.discounts[position].factor = factor

                    }

                }
            }

        },
        discountGlobal() {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
            let base = this.form.total_taxed

            let amount = (this.is_amount) ? parseFloat(this.total_global_discount) : parseFloat(this.total_global_discount) / 100 * base
            let factor = (this.is_amount) ? _.round(amount / base, 5) : _.round(parseFloat(this.total_global_discount) / 100, 5)

            if (this.total_global_discount > 0 && this.form.discounts.length == 0) {

                this.form.discounts.push({
                    discount_type_id: "02",
                    description: "Descuento Global afecta a la base imponible",
                    factor: 0,
                    amount: 0,
                    base: 0
                })

            }


            if (this.form.discounts.length) {

                this.form.total_discount = _.round(amount, 2)
                this.form.total_value = _.round(base - amount, 2)
                this.form.total_igv = _.round(this.form.total_value * 0.18, 2)
                this.form.total_taxes = _.round(this.form.total_igv, 2)
                this.form.total = _.round(this.form.total_value + this.form.total_taxes, 2)

                this.form.total_taxed = this.form.total_value

                this.form.discounts[0].base = base
                this.form.discounts[0].amount = _.round(amount, 2)
                this.form.discounts[0].factor = factor
            }
        },
        async changeDetractionType() {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
            if (this.form.detraction) {
                this.form.detraction.amount = (this.form.currency_type_id == 'PEN') ? _.round(parseFloat(this.form.total) * (parseFloat(this.form.detraction.percentage) / 100), 2) : _.round((parseFloat(this.form.total) * this.form.exchange_rate_sale) * (parseFloat(this.form.detraction.percentage) / 100), 2)
            }
        },
        setTotalDefaultPayment() {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
            if (this.form.payments !== undefined && this.form.payments.length > 0) {

                this.form.payments[0].payment = this.form.total
            }
        },
        setPendingAmount() {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
            this.form.pending_amount_prepayment = this.form.has_prepayment ? this.form.total : 0
        },
        calculateFee() {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
            let fee_count = 0;
            if (this.form.fee === undefined) {
                return null;
            }
            fee_count = this.form.fee.length;

            let total = this.form.total;
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
        chargeGlobal() {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */

            let base = parseFloat(this.form.total)

            if (this.config.active_allowance_charge) {
                let percentage_allowance_charge = parseFloat(this.config.percentage_allowance_charge)
                this.total_global_charge = _.round(base * (percentage_allowance_charge / 100), 2)
            }

            if (this.total_global_charge == 0) {
                this.deleteChargeGlobal()
                return
            }


            let amount = parseFloat(this.total_global_charge)
            // let base = this.form.total_taxed + amount
            let factor = _.round(amount / base, 5)

            // console.log(base,factor, amount)

            let charge = _.find(this.form.charges, {charge_type_id: '50'})

            if (amount > 0 && !charge) {

                this.form.total_charge = _.round(amount, 2)
                this.form.total = _.round(this.form.total + this.form.total_charge, 2)

                this.form.charges.push({
                    charge_type_id: '50',
                    description: 'Cargos globales que no afectan la base imponible del IGV/IVAP',
                    factor: factor,
                    amount: amount,
                    base: base
                })

            } else {

                let pos = this.form.charges.indexOf(charge);

                if (pos > -1) {

                    this.form.total_charge = _.round(amount, 2)
                    this.form.total = _.round(this.form.total + this.form.total_charge, 2)

                    this.form.charges[pos].base = base
                    this.form.charges[pos].amount = amount
                    this.form.charges[pos].factor = factor

                }
            }


        },


        clickAddNote() {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
            if (this.form.important_note.length == 2) {
                return
            }

            this.form.important_note.push({
                description: null,
            })
        },
        clickRemoveGuide(index) {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
            this.form.important_note.splice(index, 1)
        },
        searchRemoteCustomers(input) {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */

            if (input.length > 0) {
                this.loading_search = true
                let parameters = `input=${input}`

                this.$http.get(`/${this.resource}/search/customers?${parameters}`)
                    .then(response => {
                        this.customers = response.data.customers
                        this.loading_search = false
                        if (this.customers.length == 0) {
                            this.allCustomers()
                        }
                    })
            } else {
                this.allCustomers()
            }

        },
        allCustomers() {
            /* Extraido de resources/js/views/tenant/documents/invoice.vue */
            this.customers = this.all_customers
        },
        reloadDataCustomers(customer_id) {
            this.$http.get(`/${this.resource}/search/customer/${customer_id}`).then((response) => {
                this.customers = response.data.customers
                this.form.customer_id = customer_id
            })
        },
        initForm() {
            this.errors = {}
            //this.form.exchange_rate_sale = this.exchange_rate
            this.form = {
                id: null,
                customer_id: null,
                cellphone: null,
                date_of_issue: moment().format('YYYY-MM-DD'),
                time_of_issue: moment().format('HH:mm:ss'),
                description: null,
                currency_type_id: this.config.currency_type_id,
                state: null,
                reason: null,
                serial_number: null,
                activities: null,
                cost: 0,
                prepayment: 0,
                equipment: null,
                brand: null,
                repair: false,
                warranty: false,
                maintenance: false,
                diagnosis: false,
                important_note: [],
                operation_type_id: "0101",
                exchange_rate_sale: this.exchange_rate,
                items: [],
                charges: [],
                discounts: [],
                attributes: [],
                guides: [],
                payments: [],
                prepayments: [],
                legends: [],
                detraction: {},
                additional_information: null,
                plate_number: null,
                has_prepayment: false,
                affectation_type_prepayment: null,
                actions: {
                    format_pdf: 'a4',
                },
                hotel: {},
                transport: {},
                customer_address_id: null,
                pending_amount_prepayment: 0,
                payment_method_type_id: null,
                show_terms_condition: true,
                terms_condition: '',
                payment_condition_id: '01',
                fee: [],
                establishment_id: null,
                document_type_id: null,
                series_id: null,
                number: '#',
                purchase_order: null,
                total_prepayment: 0,
                total_charge: 0,
                total_discount: 0,
                total_exportation: 0,
                total_free: 0,
                total_taxed: 0,
                total_unaffected: 0,
                total_exonerated: 0,
                total_igv: 0,
                total_base_isc: 0,
                total_isc: 0,
                total_base_other_taxes: 0,
                total_other_taxes: 0,
                total_plastic_bag_taxes: 0,
                total_taxes: 0,
                total_value: 0,
                total: 0,
                subtotal: 0,
                total_igv_free: 0,
                date_of_due: moment().format('YYYY-MM-DD'),

            }
        },
        create() {
            this.total = 0;
            this.load_record = true;
            this.initForm()

            this.titleDialog = (this.recordId) ? 'Editar servicio técnico' : 'Nuevo servicio técnico'
            this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                this.$store.commit('setExchangeRate', response)
                this.form.exchange_rate_sale = this.exchange_rate
            });

            if (this.recordId) {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        let data = response.data;
                        this.form = data.data
                    })
                    .then(() => {
                        this.reloadDataCustomers(this.form.customer_id)
                        this.calculateTotal()
                    })
                    .finally(()=>{
                        this.load_record = false;
                })
            }else{
                this.load_record = false
            }
        },
        submit() {

            if (parseFloat(this.form.prepayment) > parseFloat(this.form.cost)) {
                return this.$message.error('Pago adelantado no puede ser mayor al costo')
            }

            this.loading_submit = true
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.$eventHub.$emit('reloadData')
                        this.close()
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
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },

        changeTypeDiscount() {
            this.calculateTotal()
        },

        changePaymentCondition() {
            this.form.fee = [];
            this.form.payments = [];
            if (this.form.payment_condition_id === '01') {
                this.clickAddPayment();
            }
            if (this.form.payment_condition_id === '02') {
                this.clickAddFeeNew();
            }
            if (this.form.payment_condition_id === '03') {
                this.clickAddFee();
            }
        },

        changePaymentMethodType(index) {

            let id = '01';
            if (this.form.payments[index] !== undefined &&
                this.form.payments[index].payment_method_type_id !== undefined) {
                id = this.form.payments[index].payment_method_type_id;
            } else if (this.form.fee[index] !== undefined &&
                this.form.fee[index].payment_method_type_id !== undefined) {
                id = this.form.fee[index].payment_method_type_id;
            }
            let payment_method_type = _.find(this.payment_method_types, {'id': id});

            if (payment_method_type.number_days) {
                this.form.date_of_due = moment().add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')
                // this.form.payments = []
                this.enabled_payments = false
                this.readonly_date_of_due = true
                this.form.payment_method_type_id = payment_method_type.id

                let date = moment()
                    .add(payment_method_type.number_days, 'days')
                    .format('YYYY-MM-DD')
                if (this.form.fee !== undefined) {
                    for (let index = 0; index < this.form.fee.length; index++) {
                        this.form.fee[index].date = date;
                    }
                }

            } else if (payment_method_type.id == '09') {

                this.form.payment_method_type_id = payment_method_type.id
                this.form.date_of_due = this.form.date_of_issue
                // this.form.payments = []
                this.enabled_payments = false

            } else {

                this.form.date_of_due = this.form.date_of_issue
                this.readonly_date_of_due = false
                this.form.payment_method_type_id = null
                this.enabled_payments = true

            }

        },

        clickRemoveItem(index) {
            this.form.items.splice(index, 1)
            this.calculateTotal()
        },

        clickRemoveFee(index) {
            this.form.fee.splice(index, 1);
            this.calculateFee();
        },

        clickAddFee() {
            this.form.date_of_due = moment().format('YYYY-MM-DD');
            this.form.fee.push({
                id: null,
                date: moment().format('YYYY-MM-DD'),
                currency_type_id: this.form.currency_type_id,
                amount: 0,
            });
            this.calculateFee();
        },

        clickCancel(index) {
            this.form.payments.splice(index, 1);
        },

        clickAddPayment() {

            let id = '01';
            if (this.cash_payment_metod !== undefined &&
                this.cash_payment_metod[0] !== undefined) {
                id = this.cash_payment_metod[0].id
            }
            let total = 0;
            if (this.form.total !== undefined) {
                total = this.form.total
            }
            this.form.date_of_due = moment().format('YYYY-MM-DD');
            this.form.payments.push({
                id: null,
                document_id: null,
                date_of_payment: moment().format('YYYY-MM-DD'),
                payment_method_type_id: id,
                reference: null,
                payment_destination_id: this.getPaymentDestinationId(),
                payment: total,
            });
            this.calculatePayments()

        },
        clickAddFeeNew() {
            let first = {
                id: '05',
                number_days: 0,
            };
            if (this.credit_payment_metod[0] !== undefined) {
                first = this.credit_payment_metod[0];
            }
            let date = moment()
                .add(first.number_days, 'days')
                .format('YYYY-MM-DD')
            this.form.date_of_due = date;
            this.form.fee.push({
                id: null,
                document_id: null,
                payment_method_type_id: first.id,
                // reference: null,
                // payment_destination_id: this.getPaymentDestinationId(),
                // payment: 0,

                date: date,
                currency_type_id: this.form.currency_type_id,
                amount: 0,
            });
            this.calculateFee();
        },


        sumDiscountsNoBaseByItem(row) {

            let sum_discount_no_base = 0

            if (row.discounts) {
                // if(row.discounts.length > 0){
                sum_discount_no_base = _.sumBy(row.discounts, function (discount) {
                    return (discount.discount_type_id == '01') ? discount.amount : 0
                })
                // }
            }

            return sum_discount_no_base
        },

        deleteChargeGlobal() {

            if (this.form.charges === undefined) return null;
            let charge = _.find(this.form.charges, {charge_type_id: '50'})
            if (charge === undefined) return null;
            let index = this.form.charges.indexOf(charge)

            if (index > -1) {
                this.form.charges.splice(index, 1)
                this.form.total_charge = 0
            }

        },


        /******/

        async processItemsForNotesNotGroup() {

            let itemsNotGroupForNotes = localStorage.getItem('itemsNotGroupForNotes')

            if (itemsNotGroupForNotes) {

                let itemsParsed = JSON.parse(itemsNotGroupForNotes)

                // prepare - validate prop presentation and others
                this.form.items = await this.onPrepareItems(itemsParsed).map(element => {
                    element.item.presentation = element.item.presentation ? element.item.presentation : []
                    return element
                });

                await this.calculateTotal()
                localStorage.removeItem('itemsNotGroupForNotes');
            }

        },
        setItemFromResponse(item, itemsParsed) {
            /* Obtiene el igv del item, si no existe, coloca el gravado*/
            if (item.sale_affectation_igv_type !== undefined) {
                item.affectation_igv_type = item.sale_affectation_igv_type
            } else {
                item.affectation_igv_type = {
                    active: 1,
                    description: "Gravado - Operación Onerosa",
                    exportation: 0,
                    free: 0,
                    id: "10",
                }
            }
            item.presentation = {};
            item.unit_price = item.sale_unit_price;
            item.item = {
                amount_plastic_bag_taxes: item.amount_plastic_bag_taxes,
                attributes: item.attributes,
                brand: item.brand,
                calculate_quantity: item.calculate_quantity,
                category: item.category,
                currency_type_id: item.currency_type_id,
                currency_type_symbol: item.currency_type_symbol,
                description: item.description,
                full_description: item.full_description,
                has_igv: item.has_igv,
                has_plastic_bag_taxes: item.has_plastic_bag_taxes,
                id: item.id,
                internal_id: item.internal_id,
                item_unit_types: item.item_unit_types,
                lots: item.lots,
                lots_enabled: item.lots_enabled,
                lots_group: item.lots_group,
                model: item.model,
                presentation: {},
                purchase_affectation_igv_type_id: item.purchase_affectation_igv_type_id,
                purchase_unit_price: item.purchase_unit_price,
                sale_affectation_igv_type_id: item.sale_affectation_igv_type_id,
                sale_unit_price: item.sale_unit_price,
                series_enabled: item.series_enabled,
                stock: item.stock,
                unit_price: item.sale_unit_price,
                unit_type_id: item.unit_type_id,
                warehouses: item.warehouses,
            };
            item.IdLoteSelected = null;
            if (item.affectation_igv_type_id === undefined) {
                item.affectation_igv_type_id = item.affectation_igv_type.id;
                // item.affectation_igv_type_id = "10";
            }
            item.discounts = [];
            item.charges = [];
            item.item_id = item.id;
            item.unit_price_value = item.sale_unit_price;
            item.input_unit_price_value = item.sale_unit_price;

            item.quantity = 1;

            let tempItem = itemsParsed.find(ip => (ip.item_id == item.id) || (ip.id == item.id));
            if (tempItem !== undefined) {
                item.quantity = tempItem.quantity
            }
            // item.quantity = itemsParsed.find(ip => ip.item_id == item.id).quantity;
            item.warehouse_id = null;

            return item
        },
        // #307 Ajuste para seleccionar automaticamente el tipo de comprobante y serie
        setDefaultDocumentType() {
            if (this.default_document_type === undefined) this.default_document_type = null;
            if (this.default_series_type === undefined) this.default_series_type = null;
            let alt = _.find(this.document_types, {'id': this.default_document_type});
            if (this.default_document_type !== null && alt !== undefined) {
                this.form.document_type_id = this.default_document_type;
                this.changeDocumentType()
                alt = _.find(this.series, {'id': this.default_series_type});
                if (this.default_series_type !== null && alt !== undefined) {
                    this.form.series_id = this.default_series_type;
                }
            }
        },
        async onSetFormData(data) {

            this.currency_type = await _.find(this.currency_types, {'id': data.currency_type_id})

            this.form.establishment_id = data.establishment_id;
            this.form.document_type_id = data.document_type_id;
            this.form.id = data.id;
            this.form.hash = data.hash;
            this.form.number = data.number;
            this.form.date_of_issue = moment(data.date_of_issue).format('YYYY-MM-DD');
            this.form.time_of_issue = data.time_of_issue;
            this.form.customer_id = data.customer_id;
            this.form.currency_type_id = data.currency_type_id;
            this.form.exchange_rate_sale = data.exchange_rate_sale;
            this.form.additional_information = this.onPrepareAdditionalInformation(data.additional_information);
            this.form.external_id = data.external_id;
            this.form.filename = data.filename;
            this.form.group_id = data.group_id;
            this.form.perception = data.perception;
            this.form.note = data.note;
            this.form.plate_number = data.plate_number;
            this.form.payments = data.payments;
            this.form.prepayments = data.prepayments || [];
            this.form.legends = [];
            this.form.detraction = data.detraction;
            this.form.affectation_type_prepayment = data.affectation_type_prepayment;
            this.form.purchase_order = data.purchase_order;
            this.form.pending_amount_prepayment = data.pending_amount_prepayment || 0;
            this.form.payment_method_type_id = data.payment_method_type_id;
            this.form.charges = data.charges || [];
            this.form.discounts = data.discounts || [];
            this.form.seller_id = data.seller_id;
            this.form.items = this.onPrepareItems(data.items);
            // this.form.series = data.series; //form.series no llena el selector
            this.series = this.onSetSeries(data.document_type_id, data.series);
            this.form.state_type_id = data.state_type_id;
            this.form.total_discount = parseFloat(data.total_discount);
            this.form.total_exonerated = parseFloat(data.total_exonerated);
            this.form.total_exportation = parseFloat(data.total_exportation);
            this.form.total_free = parseFloat(data.total_free);
            this.form.total_igv = parseFloat(data.total_igv);
            this.form.total_isc = parseFloat(data.total_isc);
            this.form.total_base_isc = parseFloat(data.total_base_isc);
            this.form.total_base_other_taxes = parseFloat(data.total_base_other_taxes);
            this.form.total_other_taxes = parseFloat(data.total_other_taxes);
            this.form.total_plastic_bag_taxes = parseFloat(data.total_plastic_bag_taxes);
            this.form.total_prepayment = parseFloat(data.total_prepayment);
            this.form.total_taxed = parseFloat(data.total_taxed);
            this.form.total_taxes = parseFloat(data.total_taxes);
            this.form.total_unaffected = parseFloat(data.total_unaffected);
            this.form.total_value = parseFloat(data.total_value);
            this.form.total_charge = parseFloat(data.total_charge);
            this.form.total = parseFloat(data.total);
            this.form.subtotal = parseFloat(data.subtotal);
            this.form.total_igv_free = parseFloat(data.total_igv_free);
            this.form.series_id = this.onSetSeriesId(data.document_type_id, data.series);
            this.form.operation_type_id = data.invoice.operation_type_id;
            this.form.terms_condition = data.terms_condition || '';
            this.form.guides = data.guides || [];
            this.form.show_terms_condition = data.terms_condition ? true : false;
            this.form.attributes = [];
            this.form.customer = data.customer;
            this.form.has_prepayment = false;
            this.form.actions = {
                format_pdf: 'a4',
            };
            this.form.hotel = {};
            this.form.transport = {};
            this.form.customer_address_id = null;
            this.form.type = 'invoice';
            this.form.invoice = {
                operation_type_id: data.invoice.operation_type_id,
                date_of_due: data.invoice.date_of_due,
            };
            // this.form.payment_condition_id = '01';

            let is_credit_installments = await _.find(data.fee, {payment_method_type_id: null})
            this.form.payment_condition_id = (is_credit_installments) ? '03' : data.payment_condition_id;
            this.form.fee = data.fee;
            // this.form.fee = [];

            if (!data.guides) {
                this.clickAddInitGuides();
            }

            this.establishment = data.establishment;

            this.changeDateOfIssue();
            this.filterCustomers();
            this.updateChangeDestinationSale();
            this.calculateTotal();
            // this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
        },
        updateChangeDestinationSale() {

            if (this.form.payment_condition_id == '01') {

                if (this.configuration.destination_sale && this.payment_destinations.length > 0) {
                    let cash = _.find(this.payment_destinations, {id: 'cash'})
                    if (cash) {
                        this.form.payments[0].payment_destination_id = cash.id
                    } else {
                        this.form.payment_destination_id = this.payment_destinations[0].id
                        this.form.payments[0].payment_destination_id = this.payment_destinations[0].id
                    }
                }

            }
        },
        onPrepareAdditionalInformation(data) {

            let obs = null

            if (Array.isArray(data)) {

                if (data.length > 0) {
                    if (data[0] == '') {
                        return obs;
                    }
                }

                obs = data.join('|')

            }
            // if (typeof data === 'object') {
            //     if (data[0]) {
            //         return data;
            //     }
            //     return null;
            // }

            return obs;
        },
        onPrepareItems(items) {
            return items.map(i => {

                i.unit_price_value = i.unit_value;
                i.input_unit_price_value = (i.item.has_igv) ? i.unit_price : i.unit_value;

                // i.input_unit_price_value = i.unit_price;
                i.discounts = (i.discounts) ? Object.values(i.discounts) : []
                // i.discounts = i.discounts || [];
                i.charges = i.charges || [];
                i.attributes = i.attributes || [];
                i.item.id = i.item_id;
                i.additional_information = this.onPrepareAdditionalInformation(i.additional_information);
                i.item = this.onPrepareIndividualItem(i);
                return i;
            });
        },
        onPrepareIndividualItem(data) {

            let new_item = data.item
            let currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})

            new_item.currency_type_id = currency_type.id
            new_item.currency_type_symbol = currency_type.symbol

            new_item.sale_affectation_igv_type_id = data.affectation_igv_type_id
            new_item.sale_unit_price = data.unit_price
            new_item.unit_price = data.unit_price

            return new_item
        },
        onSetSeriesId(documentType, serie) {
            const find = this.all_series.find(s => s.document_type_id == documentType && s.number == serie);
            if (find) {
                return find.id;
            }
            return null;
        },
        onSetSeries(documentType, serie) {
            const find = this.all_series.find(s => s.document_type_id == documentType && s.number == serie);
            if (find) {
                return [find];
            }
            return [];
        },
        getPrepayment(index) {
            return _.find(this.prepayment_documents, {id: this.form.prepayments[index].document_id})
        },
        inputAmountPrepayment(index) {

            let prepayment = this.getPrepayment(index)

            if (parseFloat(this.form.prepayments[index].amount) > parseFloat(prepayment.amount)) {

                this.form.prepayments[index].amount = prepayment.amount
                this.$message.error('El monto debe ser menor o igual al del anticipo');

            }

            this.form.prepayments[index].total = (this.form.affectation_type_prepayment == 10) ? _.round(this.form.prepayments[index].amount * 1.18, 2) : this.form.prepayments[index].amount

            this.changeTotalPrepayment()

        },
        changeDestinationSale() {
            if (this.configuration.destination_sale && this.payment_destinations.length > 0) {
                let cash = _.find(this.payment_destinations, {id: 'cash'})
                if (cash) {
                    this.form.payments[0].payment_destination_id = cash.id
                } else {
                    this.form.payment_destination_id = this.payment_destinations[0].id
                    this.form.payments[0].payment_destination_id = this.payment_destinations[0].id
                }
            }
        },
        changePaymentDestination(index) {
            // if(this.form.payments[index].payment_method_type_id=='01'){
            //     this.payment_destinations = this.cash
            // }else{
            //     this.payment_destinations = this.payment_destinations
            // }
        },
        changeEnabledPayments() {
            // this.clickAddPayment()
            // this.form.date_of_due = this.form.date_of_issue
            // this.readonly_date_of_due = false
            // this.form.payment_method_type_id = null
        },
        selectDocumentType() {
            this.form.document_type_id = (this.select_first_document_type_03) ? '03' : '01'
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
        addDocumentDetraction(detraction) {
            this.form.detraction = detraction
            // this.has_data_detraction = (detraction.pay_constancy || detraction.detraction_type_id || detraction.payment_method_id || (detraction.amount && detraction.amount >0)) ? true:false
            this.has_data_detraction = (detraction) ? detraction.has_data_detraction : false
        },
        async changeDocumentPrepayment(index) {

            let prepayment = await _.find(this.prepayment_documents, {id: this.form.prepayments[index].document_id})

            this.form.prepayments[index].number = prepayment.description
            this.form.prepayments[index].document_type_id = prepayment.document_type_id
            this.form.prepayments[index].amount = prepayment.amount
            this.form.prepayments[index].total = prepayment.total

            await this.changeTotalPrepayment()


        },
        clickAddPrepayment() {
            this.form.prepayments.push({
                document_id: null,
                number: null,
                document_type_id: null,
                amount: 0,
                total: 0,
            });

            this.changeTotalPrepayment()
        },
        clickRemovePrepayment(index) {

            this.form.prepayments.splice(index, 1)
            this.changeTotalPrepayment()
            if (this.form.prepayments.length == 0)
                this.deletePrepaymentDiscount()

        },
        async changePrepaymentDeduction() {

            this.form.prepayments = []
            this.form.total_prepayment = 0
            await this.deletePrepaymentDiscount()

            if (this.prepayment_deduction) {

                await this.initialValueATPrepayment()
                await this.changeTotalPrepayment()
                await this.getDocumentsPrepayment()

            } else {

                // this.form.total_prepayment = 0
                // await this.deletePrepaymentDiscount()
                this.cleanValueATPrepayment()
            }

        },
        initialValueATPrepayment() {
            this.form.affectation_type_prepayment = (!this.form.affectation_type_prepayment) ? 10 : this.form.affectation_type_prepayment
        },
        cleanValueATPrepayment() {
            this.form.affectation_type_prepayment = null
        },
        changeHasPrepayment() {

            if (this.form.has_prepayment) {
                this.initialValueATPrepayment()
            } else {
                this.cleanValueATPrepayment()
            }

            this.setPendingAmount()

        },
        async changeAffectationTypePrepayment() {

            await this.initialValueATPrepayment()

            if (this.prepayment_deduction) {

                this.form.total_prepayment = 0
                await this.deletePrepaymentDiscount()
                await this.changePrepaymentDeduction()
            }

        },
        async deletePrepaymentDiscount() {

            let discount = await _.find(this.form.discounts, {'discount_type_id': '04'})
            let discount_exonerated = await _.find(this.form.discounts, {'discount_type_id': '05'})
            let discount_unaffected = await _.find(this.form.discounts, {'discount_type_id': '06'})

            let pos = this.form.discounts.indexOf(discount)
            if (pos > -1) {
                this.form.discounts.splice(pos, 1)
                this.changeTotalPrepayment()
            }

            let pos_exonerated = this.form.discounts.indexOf(discount_exonerated)
            if (pos_exonerated > -1) {
                this.form.discounts.splice(pos_exonerated, 1)
                this.changeTotalPrepayment()
            }

            let pos_unaffected = this.form.discounts.indexOf(discount_unaffected)
            if (pos_unaffected > -1) {
                this.form.discounts.splice(pos_unaffected, 1)
                this.changeTotalPrepayment()
            }

        },
        getDocumentsPrepayment() {
            this.$http.get(`/${this.resource}/prepayments/${this.form.affectation_type_prepayment}`).then((response) => {
                this.prepayment_documents = response.data
            })
        },
        changeTotalPrepayment() {
            this.calculateTotal()
        },
        isActiveBussinessTurn(value) {
            return (_.find(this.business_turns, {'value': value})) ? true : false
        },
        clickAddDocumentHotel() {
            this.showDialogFormHotel = true
        },
        clickAddDocumentTransport() {
            this.showDialogFormTransport = true
        },

        addDocumentHotel(hotel) {
            this.form.hotel = hotel
        },
        addDocumentTransport(transport) {
            this.form.transport = transport
        },
        changeIsReceivable() {

        },
        getPaymentDestinationId() {

            if (this.configuration.destination_sale &&
                this.payment_destinations.length > 0) {

                let cash = _.find(this.payment_destinations, {id: 'cash'})

                return (cash) ? cash.id : this.payment_destinations[0].id

            }

            return null

        },
        initInputPerson() {
            this.input_person = {
                number: null,
                identity_document_type_id: null
            }
        },
        resetForm() {
            this.activePanel = 0
            this.initForm()
            // this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null
            this.form.establishment_id = (this.establishments.length > 0) ? this.establishments[0].id : null
            this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null
            this.form.operation_type_id = (this.operation_types.length > 0) ? this.operation_types[0].id : null
            this.form.seller_id = (this.sellers.length > 0) ? this.idUser : null;
            this.selectDocumentType()
            this.changeEstablishment()
            this.changeDocumentType()
            this.changeDateOfIssue()
            this.changeCurrencyType()
            // this.changeDestinationSale()

        },
        // async filterDetractionTypes(){
        //     this.detraction_types =  await _.filter(this.all_detraction_types, {'operation_type_id':this.form.operation_type_id})
        // },
        validateDetraction() {

            if (['1001', '1004'].includes(this.form.operation_type_id)) {

                let detraction = this.form.detraction

                let tot = (this.form.currency_type_id == 'PEN') ? this.form.total : (this.form.total * this.form.exchange_rate_sale)
                let total_restriction = (this.form.operation_type_id == '1001') ? 700 : 400

                if (tot <= total_restriction)
                    return {
                        success: false,
                        message: `El importe de la operación debe ser mayor a S/ ${total_restriction}.00 o equivalente en USD`
                    }

                if (!detraction.detraction_type_id)
                    return {success: false, message: 'El campo bien o servicio sujeto a detracción es obligatorio'}

                if (!detraction.payment_method_id)
                    return {success: false, message: 'El campo método de pago - detracción es obligatorio'}

                if (!detraction.bank_account)
                    return {success: false, message: 'El campo cuenta bancaria es obligatorio'}

                if (detraction.amount <= 0)
                    return {success: false, message: 'El campo total detracción debe ser mayor a cero'}

            }

            return {success: true}

        },
        changeEstablishment() {
            this.establishment = _.find(this.establishments, {'id': this.form.establishment_id})
            this.filterSeries()
            this.selectDefaultCustomer()
        },
        async selectDefaultCustomer() {

            if (this.establishment.customer_id) {

                let temp_all_customers = this.all_customers;
                let temp_customers = this.customers;
                await this.$http.get(`/${this.resource}/search/customer/${this.establishment.customer_id}`).then((response) => {
                    let data_customer = response.data.customers
                    temp_all_customers = temp_all_customers.push(...data_customer)
                    temp_customers = temp_customers.push(...data_customer)
                })
                temp_all_customers = this.all_customers.filter((item, index, self) =>
                        index === self.findIndex((t) => (
                            t.id === item.id
                        ))
                )
                temp_customers = this.customers.filter((item, index, self) =>
                        index === self.findIndex((t) => (
                            t.id === item.id
                        ))
                )
                this.all_customers = temp_all_customers;
                this.customers = temp_customers;
                await this.filterCustomers()
                // this.form.customer_id = (this.customers.length > 0) ? this.establishment.customer_id : null
                let alt = _.find(this.customers, {'id': this.establishment.customer_id});
                if (alt !== undefined) {
                    this.form.customer_id = this.establishment.customer_id
                }
            }
        },
        changeDocumentType() {
            this.filterSeries();
            this.cleanCustomer();
            this.filterCustomers();
        },
        cleanCustomer() {
            this.form.customer_id = null
        },
        changeDateOfIssue() {
            let minDate = moment().subtract(7, 'days')
            if (moment(this.form.date_of_issue) < minDate && this.configuration.restrict_receipt_date) {
                this.$message.error('No puede seleccionar una fecha menor a 6 días.');
                this.dateValid = false
            } else {
                this.dateValid = true
            }
            this.form.date_of_due = this.form.date_of_issue
            // if (! this.isUpdate) {
            this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                this.form.exchange_rate_sale = response
            });
            // }
        },
        assignmentDateOfPayment() {
            this.form.payments.forEach((payment) => {
                payment.date_of_payment = this.form.date_of_issue
            })
        },
        filterSeries() {
            this.form.series_id = null
            this.series = _.filter(this.all_series, {
                'establishment_id': this.form.establishment_id,
                'document_type_id': this.form.document_type_id,
                'contingency': this.is_contingency
            });
            this.form.series_id = (this.series.length > 0) ? this.series[0].id : null
        },
        clickAddInitGuides() {
            this.form.guides.push({
                document_type_id: '09',
                number: null
            }, {
                document_type_id: '31',
                number: null
            })
        },
        clickAddGuide() {
            this.form.guides.push({
                document_type_id: null,
                number: null
            })
        },
        async deleteInitGuides() {
            await _.remove(this.form.guides, {'number': null})
        },
        async asignPlateNumberToItems() {

            if (this.form.plate_number) {

                await this.form.items.forEach(item => {

                    let at = _.find(item.attributes, {'attribute_type_id': '5010'})

                    if (!at) {
                        item.attributes.push({
                            attribute_type_id: '5010',
                            description: "Numero de Placa",
                            value: this.form.plate_number,
                            start_date: null,
                            end_date: null,
                            duration: null,
                        })
                    } else {

                        if (this.isUpdate) {
                            at.value = this.form.plate_number
                        }
                    }

                });

            }
        },
        async validateAffectationTypePrepayment() {

            let not_equal_affectation_type = 0

            await this.form.items.forEach(item => {
                if (item.affectation_igv_type_id != this.form.affectation_type_prepayment) {
                    not_equal_affectation_type++
                }
            });

            return {
                success: (not_equal_affectation_type > 0) ? false : true,
                message: 'Los items deben tener tipo de afectación igual al seleccionado en el anticipo'
            }
        },
        validatePaymentDestination() {

            let error_by_item = 0

            this.form.payments.forEach((item) => {

                if (!['05', '08', '09'].includes(item.payment_method_type_id)) {
                    if (item.payment_destination_id == null) error_by_item++;
                }

            })

            return {
                error_by_item: error_by_item,
            }

        },
        saveCashDocument() {
            this.$http.post(`/cash/cash_document`, this.form_cash_document)
                .then(response => {
                    if (!response.data.success) {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => console.log(error))
        },
        validate_payments() {

            //eliminando items de pagos
            for (let index = 0; index < this.form.payments.length; index++) {
                if (parseFloat(this.form.payments[index].payment) === 0)
                    this.form.payments.splice(index, 1)
            }

            let error_by_item = 0
            let acum_total = 0

            this.form.payments.forEach((item) => {
                acum_total += parseFloat(item.payment)
                if (item.payment <= 0 || item.payment == null) error_by_item++;
            })

            return {
                error_by_item: error_by_item,
                acum_total: acum_total
            }

        },

        changeCustomer() {
            this.customer_addresses = [];
            let customer = _.find(this.customers, {'id': this.form.customer_id});
            this.customer_addresses = customer.addresses;
            if (customer.address) {
                this.customer_addresses.unshift({
                    id: null,
                    address: customer.address
                })
            }


            /*if(this.customer_addresses.length > 0) {
                let address = _.find(this.customer_addresses, {'main' : 1});
                this.form.customer_address_id = address.id;
            }*/
        },
        calculatePayments() {
            let payment_count = this.form.payments.length;
            let total = this.form.total;
            let payment = 0;
            let amount = _.round(total / payment_count, 2);
            // console.log(amount);
            _.forEach(this.form.payments, row => {
                payment += amount;
                if (total - payment < 0) {
                    amount = _.round(total - payment + amount, 2);
                }
                row.payment = amount;
                // console.error(row.payment)
            })
        },


    }
}
</script>
