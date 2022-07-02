<template>
    <div class="card mb-0 pt-2 pt-md-0"
    >
        <Keypress
            key-event="keyup"

            @success="checkKey"
        />
        <Keypress key-event="keyup" :multiple-keys="multiple" @success="checkKeyWithAlt" />


        <div class="tab-content"  v-if="company && establishment">
            <div class="invoice">
                <header class="clearfix">
                    <div class="row">
                        <div class="col-sm-2 text-center mt-3 mb-0">
                            <logo url="/" :path_logo="(company.logo != null) ? `/storage/uploads/logos/${company.logo}` : ''" ></logo>
                        </div>
                        <div class="col-sm-10 text-left mt-3 mb-0">
                            <address class="ib mr-2" >
                                <span class="font-weight-bold d-block">NOTA DE VENTA</span>
                                <span class="font-weight-bold d-block">NV-XXX</span>
                                <span class="font-weight-bold">{{company.name}}</span>
                                <br>
                                <div v-if="establishment.address != '-'">{{ establishment.address }}, </div> {{ establishment.district.description }}, {{ establishment.province.description }}, {{ establishment.department.description }} - {{ establishment.country.description }}
                                <br>
                                {{establishment.email}} - <span v-if="establishment.telephone != '-'">{{establishment.telephone}}</span>
                            </address>
                        </div>
                    </div>
                </header>
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row mt-1">
                             <div class="col-lg-6 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.customer_id}">
                                    <label class="control-label font-weight-bold text-info">
                                        Cliente
                                        <a href="#" @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                    </label>
                                    <el-select v-model="form.customer_id" filterable remote class="border-left rounded-left border-info" popper-class="el-select-customers"
                                        dusk="customer_id"
                                        placeholder="Escriba el nombre o número de documento del cliente"
                                        :remote-method="searchRemoteCustomers"
                                               @change="changeCustomer"
                                               @focus="focus_on_client = true"
                                               @blur="focus_on_client = false"
                                               :loading="loading_search">

                                        <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.description"></el-option>

                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.establishment_id}">
                                    <label class="control-label">Establecimiento</label>
                                    <el-select v-model="form.establishment_id" @change="changeEstablishment">
                                        <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.establishment_id" v-text="errors.establishment_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.series_id}">
                                    <label class="control-label">Serie</label>
                                    <el-select v-model="form.series_id">
                                        <el-option v-for="option in series" :key="option.id" :value="option.id" :label="option.number"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.series_id" v-text="errors.series_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                                    <label class="control-label">Moneda</label>
                                    <el-select v-model="form.currency_type_id" @change="changeCurrencyType">
                                        <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                    <label class="control-label">Fec. Emisión</label>
                                    <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.due_date}">
                                    <label class="control-label">Fec. Vencimiento</label>
                                    <el-date-picker
                                        v-model="form.due_date"
                                        type="date"
                                        value-format="yyyy-MM-dd"
                                        :clearable="true"
                                        :picker-options="pickerOptions"
                                    ></el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.due_date" v-text="errors.due_date[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                    <label class="control-label">Tipo de cambio
                                        <el-tooltip class="item" effect="dark" content="Tipo de cambio del día, extraído de SUNAT" placement="top-end">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </label>
                                    <el-input v-model="form.exchange_rate_sale"></el-input>
                                    <small class="form-control-feedback" v-if="errors.exchange_rate_sale" v-text="errors.exchange_rate_sale[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <div class="form-group" >
                                    <label class="control-label">
                                        Tipo periodo
                                        <el-tooltip
                                            class="item"
                                            effect="dark"
                                            content="Creación recurrente de N. Venta de forma automática, por periodo."
                                            placement="top-start">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </label>
                                    <el-select v-model="form.type_period" clearable>
                                        <el-option
                                            v-for="option in type_periods"
                                            :key="option.id"
                                            :value="option.id"
                                            :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.type_period" v-text="errors.type_period[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group">
                                    <label class="control-label">Cant. Periodos</label>
                                    <el-input-number
                                        v-model="form.quantity_period"
                                        :min="0"></el-input-number>
                                    <small class="form-control-feedback"
                                           v-show="sms_periodo.length > 1 "
                                           v-text="sms_periodo"></small>
                                </div>
                            </div>

                            
                            <div v-if="config.active_allowance_charge && form.total > 0" class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Porcentaje otros cargos</label>

                                    <el-input-number v-model="config.percentage_allowance_charge"
                                                        :min="0"
                                                        controls-position="right"
                                                        size="mini"
                                                        @change="calculateTotal">
                                                        </el-input-number>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2" >
                                <div class="form-group">
                                    <label class="control-label">Placa</label>
                                    <el-input v-model="form.license_plate" :maxlength="200"></el-input>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 form-group">
                                    <label class="control-label">Orden de compra</label>
                                    <el-input v-model="form.purchase_order" :maxlength="50"></el-input>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Observación
                                    </label>
                                    <el-input  type="textarea"  v-model="form.observation"></el-input>
                                    <small class="form-control-feedback" v-if="errors.observation" v-text="errors.observation[0]"></small>
                                </div>
                            </div>
                            <div class="col-4">
                                    <div class="form-group">
                                        <label>Vendedor</label>
                                        <el-select v-model="form.seller_id" clearable>
                                            <el-option v-for="sel in sellers" :key="sel.id" :value="sel.id" :label="sel.name">{{ sel.name }}</el-option>
                                        </el-select>
                                </div>
                            </div>
                            <!-- Pagos -->
                            <div class="col-12 pt-3">
                                <table>
                                    <thead>
                                        <tr width="100%">
                                            <th>F. Pago</th>
                                            <th v-if="form.payments.length>0">Método de pago</th>
                                            <template v-if="enabled_payments">
                                                <th v-if="form.payments.length>0">Destino
                                                    <el-tooltip class="item" effect="dark" content="Aperture caja o cuentas bancarias" placement="top-start">
                                                        <i class="fa fa-info-circle"></i>
                                                    </el-tooltip>
                                                </th>
                                                <th v-if="form.payments.length>0">Referencia</th>
                                                <th v-if="form.payments.length>0">Monto</th>
                                                <th width="15%">
                                                    <a href="#"
                                                       @click.prevent="clickAddPayment" class="text-center font-weight-bold text-info">[+ Agregar]</a>
                                                </th>
                                            </template>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in form.payments" :key="index">
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-date-picker
                                                        v-model="row.date_of_payment"
                                                        type="date"
                                                        :clearable="false"
                                                        value-format="yyyy-MM-dd"
                                                    ></el-date-picker>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-select v-model="row.payment_method_type_id" @change="changePaymentMethodType(index)">
                                                        <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                                    </el-select>
                                                </div>
                                            </td>
                                            <template v-if="enabled_payments">
                                                <td>
                                                    <div class="form-group mb-2 mr-2">
                                                        <el-select v-model="row.payment_destination_id" filterable >
                                                            <el-option v-for="option in payment_destinations" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                                        </el-select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group mb-2 mr-2"  >
                                                        <el-input v-model="row.reference"></el-input>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group mb-2 mr-2" >
                                                        <el-input v-model="row.payment"></el-input>
                                                    </div>
                                                </td>
                                                <td class="series-table-actions text-center">
                                                    <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancel(index)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </template>
                                            <br>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Fin Pagos -->
                        </div>




                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th class="font-weight-bold"
                                                    width="30%">Descripción</th>
                                                <th width="8%" class="text-center font-weight-bold">Unidad</th>
                                                <th width="8%" class="text-center font-weight-bold">Cantidad</th>
                                                <th class="text-center font-weight-bold">Valor Unitario</th>
                                                <th class="text-center font-weight-bold">Precio Unitario</th>
                                                <th class="text-center font-weight-bold">Subtotal</th>
                                                <!--<th class="text-right font-weight-bold">Cargo</th>-->
                                                <th class="text-center font-weight-bold">Total</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="form.items.length > 0">
                                            <tr v-for="(row, index) in form.items">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ row.item.description }} <template v-if="row.item.presentation">{{row.item.presentation.hasOwnProperty('description') ? row.item.presentation.description : ''}}</template><br/><small>{{ row.affectation_igv_type.description }}</small></td>
                                                <td class="text-center">{{ row.item.unit_type_id }}</td>
                                                <td class="text-center">{{ row.quantity }}</td>
                                                <!-- <td class="text-right">{{ currency_type.symbol }} {{ row.unit_price }}</td> -->
                                                <td class="text-center">{{currency_type.symbol}} {{getFormatUnitPriceRow(row.unit_value)}}</td>
                                                <td class="text-center">{{ currency_type.symbol }} {{ getFormatUnitPriceRow(row.unit_price) }}</td>

                                                <td class="text-center">{{ currency_type.symbol }} {{ row.total_value }}</td>
                                                <!--<td class="text-right">{{ currency_type.symbol }} {{ row.total_charge }}</td>-->
                                                <td class="text-center">{{ currency_type.symbol }} {{ row.total }}</td>
                                                <td class="text-center">

                                                    <!-- <template v-if="row.id">
                                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDeleteSNItem(row.id, index)">x</button>
                                                    </template> -->
                                                    <template v-if="row.record_id">
                                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDeleteSNItem(row.record_id, index)">x</button>
                                                    </template>
                                                    <template v-else>
                                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                                    </template>

                                                </td>
                                            </tr>
                                            <tr><td colspan="9"></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6 d-flex align-items-end">
                                <div class="form-group">
                                    <el-popover
                                        placement="top-start"
                                        :open-delay="1000"

                                        width="145"
                                        trigger="hover"
                                        content="Presiona F2">
                                        <el-button slot="reference"
                                                   type="button" class="btn waves-effect waves-light btn-primary"
                                                   @click.prevent="showDialogAddItem = true"
                                        >
                                            + Agregar Producto
                                            </el-button>
                                    </el-popover>
                                </div>
                            </div>

                            <div class="col-md-8 mt-3">

                            </div>

                            <div class="col-md-12">
                                <p class="text-right" v-if="form.total_exportation > 0">OP.EXPORTACIÓN: {{ currency_type.symbol }} {{ form.total_exportation }}</p>
                                <p class="text-right" v-if="form.total_free > 0">OP.GRATUITAS: {{ currency_type.symbol }} {{ form.total_free }}</p>
                                <p class="text-right" v-if="form.total_unaffected > 0">OP.INAFECTAS: {{ currency_type.symbol }} {{ form.total_unaffected }}</p>
                                <p class="text-right" v-if="form.total_exonerated > 0">OP.EXONERADAS: {{ currency_type.symbol }} {{ form.total_exonerated }}</p>
                                <p class="text-right" v-if="form.total_taxed > 0">OP.GRAVADA: {{ currency_type.symbol }} {{ form.total_taxed }}</p>
                                <p class="text-right" v-if="form.total_igv > 0">IGV: {{ currency_type.symbol }} {{ form.total_igv }}</p>
                                <p class="text-right" v-if="form.total_discount > 0">DESCUENTO: {{ currency_type.symbol }} {{ form.total_discount }}</p>

                                
                                <div class="row mt-1" v-if="form.total > 0">
                                    <div class="col-lg-10 float-right mt-1">
                                        <label class="float-right control-label">OTROS CARGOS: </label>
                                    </div>
                                    <div class="col-lg-2 float-right">
                                        <div class="form-group">
                                            <table>
                                                <tr>
                                                    <td>
                                                        {{ currency_type.symbol }}
                                                    </td>
                                                    <td>
                                                        <el-input-number v-model="total_global_charge"
                                                            :disabled="config.active_allowance_charge == true ? true:false"
                                                            :min="0"
                                                            class="input-custom ml-2"
                                                            controls-position="right"
                                                            @change="calculateTotal">
                                                        </el-input-number>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="text-right" v-if="form.total > 0"><b>TOTAL A PAGAR: </b>{{ currency_type.symbol }} {{ form.total }}</h3>
                            </div>

                        </div>

                    </div>


                    <div class="form-actions text-right mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>

                        <el-popover
                            placement="top-start"
                            width="145"
                            trigger="hover"
                            content="Presiona ALT + G">
                            <el-button slot="reference"
                                       class="submit"
                                       type="primary"
                                       native-type="submit"
                                       :loading="loading_submit"
                                       v-if="form.items.length > 0"
                            >
                                Generar
                            </el-button>
                        </el-popover>
                    </div>
                </form>
            </div>
        </div>

        <sale-notes-form-item  :typeUser="typeUser" :showDialog.sync="showDialogAddItem"
                           :currency-type-id-active="form.currency_type_id"
                           :exchange-rate-sale="form.exchange_rate_sale"
                           :configuration="config"
                           @add="addRow"></sale-notes-form-item>

        <person-form :showDialog.sync="showDialogNewPerson"
                       type="customers"
                       :external="true"
                       :document_type_id = form.document_type_id></person-form>

        <sale-notes-options :showDialog.sync="showDialogOptions"
                          :recordId="saleNotesNewId"
                          :showClose="false"
                          :configuration="config"></sale-notes-options>

    </div>
</template>

<script>
    import SaleNotesFormItem from './partials/item.vue'
    import PersonForm from '../persons/form.vue'
    import SaleNotesOptions from './partials/options.vue'
    import {functions, exchangeRate} from '../../../mixins/functions'
    import {calculateRowItem, sumAmountDiscountsNoBaseByItem} from '../../../helpers/functions'
    import Logo from '../companies/logo.vue'
    import {mapActions, mapState} from "vuex/dist/vuex.mjs";
    import Keypress from "vue-keypress";

    export default {
        props: [
            'id',
            'typeUser',
            'configuration',
        ],
        components: {
            SaleNotesFormItem,
            PersonForm,
            SaleNotesOptions,
            Logo,
            Keypress
        },
        mixins: [functions, exchangeRate],
        computed:{
            ...mapState([
                'config',
            ]),
            sms_periodo : function(){
                let text = '';
                let type = this.form.type_period;
                let time = this.form.quantity_period;
                if(time > 0 && ( type === 'year' || type === 'month' )){
                    text = "Se duplicará cada " + time;
                    if (type === 'year') {
                        text = time > 1 ? text + ' años' : text + ' año';
                    }else if (type === 'month'){
                        text = time > 1 ? text + ' meses' : text + ' mes';
                    }else{
                        text = '';
                    }
                    return text;
                }
                return text;
            }

        },
        data() {
            return {
                pickerOptions :{
                    disabledDate: date => {
                        let now = new Date();
                        return date.getTime() < (now.getTime());
                    },
                },
                multiple: [
                    {
                        keyCode: 78, // N
                        modifiers: ['altKey'],
                        preventDefault: true,
                    },
                    {
                        keyCode: 71, // g
                        modifiers: ['altKey'],
                        preventDefault: true,
                    },
                ],
                focus_on_client :false,
                sellers: [],
                resource: 'sale-notes',
                showDialogAddItem: false,
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                loading_form: false,
                errors: {},
                form: {},
                currency_types: [],
                discount_types: [],
                charges_types: [],
                all_customers: [],
                customers: [],
                company: null,
                establishments: [],
                establishment: null,
                currency_type: {},
                saleNotesNewId: null,
                form_payment: {},
                payment_method_types: [],
                activePanel: 0,
                loading_search:false,
                type_periods:[],
                series: [],
                all_series: [],
                is_contingency: false,
                enabled_payments: true,
                payment_destinations:  [],
                total_discount_no_base: 0,
                total_global_charge: 0,
                global_charge_types: [],
            }
        },
        async created() {
            this.loadConfiguration()
            this.$store.commit('setConfiguration', this.configuration)
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.currency_types = response.data.currency_types
                    this.establishments = response.data.establishments
                    this.all_customers = response.data.customers
                    this.discount_types = response.data.discount_types
                    this.charges_types = response.data.charges_types
                    this.global_charge_types = response.data.global_charge_types
                    
                    this.payment_method_types = response.data.payment_method_types
                    this.company = response.data.company
                    if(this.config.currency_type_id === undefined) {
                        this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
                    }
                    this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null
                    this.type_periods = [{id:'month',description:'Mensual'}, {id:'year',description:'Anual'}]
                    this.all_series = response.data.series
                    this.payment_destinations = response.data.payment_destinations
                    // this.configuration = response.data.configuration
                    this.sellers = response.data.sellers;
                    this.changeEstablishment()
                    this.changeDateOfIssue()
                    this.changeCurrencyType()
                    this.allCustomers()
                    this.selectDestinationSale()
                })
            this.loading_form = true
            this.$eventHub.$on('reloadDataPersons', (customer_id) => {
                this.reloadDataCustomers(customer_id)
            })
            this.isUpdate()
            this.changeCurrencyType()
        },
        methods: {
            ...mapActions([
                'loadConfiguration',
            ]),
            changePaymentMethodType(index){

                let payment_method_type = _.find(this.payment_method_types, {'id':this.form.payments[index].payment_method_type_id})

                if(payment_method_type.id == '09'){

                    this.form.payment_method_type_id = payment_method_type.id
                    this.form.date_of_due = this.form.date_of_issue
                    // this.form.payments = []
                    this.enabled_payments = false

                }else{

                    this.form.date_of_due = this.form.date_of_issue
                    this.readonly_date_of_due = false
                    this.form.payment_method_type_id = null
                    this.enabled_payments = true

                }

            },
            selectDestinationSale() {

                if(this.config.destination_sale && this.payment_destinations.length > 0) {
                    let cash = _.find(this.payment_destinations, {id : 'cash'})
                    this.form.payments[0].payment_destination_id = (cash) ? cash.id : this.payment_destinations[0].id
                }

            },
            getPaymentDestinationId() {

                if(this.config.destination_sale && this.payment_destinations.length > 0) {

                    let cash = _.find(this.payment_destinations, {id : 'cash'})

                    return (cash) ? cash.id : this.payment_destinations[0].id

                }

                return null

            },
            setTotalDefaultPayment(){

                if(this.form.payments.length > 0){

                    this.form.payments[0].payment = this.form.total
                }
            },
            filterSeries() {
                this.form.series_id = null
                this.series = _.filter(this.all_series, {'establishment_id': this.form.establishment_id, 'document_type_id': '80', 'contingency': this.is_contingency});
                this.form.series_id = (this.series.length > 0)?this.series[0].id:null
            },
            async clickDeleteSNItem(id, index){

                this.$confirm('¿Desea eliminar el item?', 'Eliminar', {
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {

                    this.$http.delete(`/${this.resource}/destroy_sale_note_item/${id}`)
                        .then(res => {

                            this.clickRemoveItem(index)
                            this.$eventHub.$emit('reloadDataItems', null)

                            this.$http.post(`/${this.resource}`, this.form).then(response => {
                                if (response.data.success) {
                                    this.isUpdate()
                                }
                                else {
                                    this.$message.error(response.data.message);
                                }
                            }).catch(error => {
                                if (error.response.status === 422) {
                                    this.errors = error.response.data;
                                }
                                else {
                                    this.$message.error(error.response.data.message);
                                }
                            })

                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar eliminar');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })


                }).catch(error => {
                    console.log(error)
                });

            },
            getFormatUnitPriceRow(unit_price){
                return _.round(unit_price, 6)
                // return unit_price.toFixed(6)
            },
            async isUpdate(){

                if (this.id) {
                    // console.log(this.id);
                    await this.$http.get(`/${this.resource}/record2/${this.id}`)
                        .then(response => {
                            this.form = response.data.data
                            this.setDataUpdate()
                            this.changeCurrencyType()
                        })
                }

            },
            setDataUpdate(){

                if(this.form.total_charge > 0) this.total_global_charge = this.form.total_charge

                this.form.charges = (this.form.charges) ? Object.values(this.form.charges) : []
                
            },
            clickAddPayment() {
                this.form.payments.push({
                    id: null,
                    document_id: null,
                    date_of_payment:  moment().format('YYYY-MM-DD'),
                    payment_method_type_id: '01',
                    reference: null,
                    payment_destination_id: this.getPaymentDestinationId(),
                    payment: 0,
                });

                this.setTotalDefaultPayment()

            },
            clickCancel(index) {
                this.form.payments.splice(index, 1);
            },
            changeCustomer() {
                let customer = _.find(this.customers, {'id': this.form.customer_id});
                let seller = this.sellers.find(element => element.id == customer.seller_id)
                if(seller !== undefined){
                    this.form.seller_id = seller.id

                }

            },
            searchRemoteCustomers(input) {

                if (input.length > 0) {
                    this.loading_search = true
                    let parameters = `input=${input}`

                    this.$http.get(`/${this.resource}/search/customers?${parameters}`)
                            .then(response => {
                                this.customers = response.data.customers
                                this.loading_search = false
                                if(this.customers.length == 0){this.allCustomers()}
                            })
                } else {
                    this.allCustomers()
                }

            },
            initForm() {
                this.errors = {}
                this.form = {
                    id:null,
                    series_id: null,
                    prefix:'NV',
                    establishment_id: null,
                    due_date: null,
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    customer_id: null,
                    currency_type_id:  this.config.currency_type_id,
                    purchase_order: null,
                    exchange_rate_sale: 0,
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
                    total_taxes: 0,
                    total_value: 0,
                    subtotal: 0,
                    total_igv_free: 0,
                    total: 0,
                    operation_type_id: null,
                    items: [],
                    charges: [],
                    discounts: [],
                    attributes: [],
                    guides: [],
                    payments: [],
                    additional_information:null,
                    actions: {
                        format_pdf:'a4',
                    },
                    apply_concurrency:false,
                    type_period:null,
                    quantity_period:0,
                    automatic_date_of_issue:null,
                    enabled_concurrency:false,
                    license_plate: null,
                    payment_method_type_id:null,
                    paid: false,
                    observation: null,
                }

                this.total_discount_no_base = 0

                this.clickAddPayment()
                this.enabled_payments = true
                this.total_global_charge = 0

            },
            resetForm() {
                this.activePanel = 0
                this.initForm()
                //this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null
                if(this.config.currency_type_id === undefined) {
                    this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
                }
                this.form.establishment_id = (this.establishments.length > 0)?this.establishments[0].id:null
                this.changeEstablishment()
                this.changeDateOfIssue()
                this.changeCurrencyType()
                this.allCustomers()
            },
            changeEstablishment() {
                this.establishment = _.find(this.establishments, {'id': this.form.establishment_id})
                this.filterSeries()
                this.selectDefaultCustomer()

            },
            cleanCustomer(){
                this.form.customer_id = null
            },
            changeDateOfIssue() {

                this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                    this.form.exchange_rate_sale = response
                })
            },
            assignmentDateOfPayment(){
                this.form.payments.forEach((payment)=>{
                    payment.date_of_payment = this.form.date_of_issue
                })
            },
            allCustomers() {
                this.customers = this.all_customers
            },
            addRow(row) {
                // this.form.items.push(row)
                this.form.items.push(JSON.parse(JSON.stringify(row)));
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
                    items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
                });
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
                this.total_discount_no_base = 0
                
                let total_igv_free = 0

                this.form.items.forEach((row) => {
                    total_discount += parseFloat(row.total_discount)
                    total_charge += parseFloat(row.total_charge)

                    if (row.affectation_igv_type_id === '10') {
                        total_taxed += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '20'  // 20,Exonerado - Operación Onerosa
                        || row.affectation_igv_type_id === '21' // 21,Exonerado – Transferencia Gratuita
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
                        || row.affectation_igv_type_id === '37'  // 37,Inafecto - Transferencia gratuita
                    ) {
                        total_unaffected += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '40') {
                        total_exportation += parseFloat(row.total_value)
                    }
                    if (['10',
                        '20', '21',
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

                    
                    if (['11', '12', '13', '14', '15', '16'].includes(row.affectation_igv_type_id)) {

                        let unit_value = row.total_value / row.quantity
                        let total_value_partial = unit_value * row.quantity
                        row.total_taxes = row.total_value - total_value_partial

                        row.total_igv = total_value_partial * (row.percentage_igv / 100)
                        row.total_base_igv = total_value_partial
                        total_value -= row.total_value

                        total_igv_free += row.total_igv
                    }

                    total_value += parseFloat(row.total_value)

                    this.total_discount_no_base += sumAmountDiscountsNoBaseByItem(row)

                });

                this.form.total_igv_free = _.round(total_igv_free, 2)

                this.form.total_discount = _.round(total_discount, 2)
                this.form.total_exportation = _.round(total_exportation, 2)
                this.form.total_taxed = _.round(total_taxed, 2)
                this.form.total_exonerated = _.round(total_exonerated, 2)
                this.form.total_unaffected = _.round(total_unaffected, 2)
                this.form.total_free = _.round(total_free, 2)
                this.form.total_igv = _.round(total_igv, 2)
                this.form.total_value = _.round(total_value, 2)
                this.form.total_taxes = _.round(total_igv, 2)
                // this.form.total = _.round(total, 2)
                this.form_payment.payment = this.form.total

                this.form.subtotal = _.round(total, 2)
                this.form.total = _.round(total - this.total_discount_no_base, 2)

                this.chargeGlobal()

                this.setTotalDefaultPayment()

            },
            getGlobalCharge(id){
                return _.find(this.global_charge_types, { id : id })
            },
            chargeGlobal(){

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
                let factor = _.round(amount / base, 5)
                let charge = _.find(this.form.charges, {charge_type_id: '50'})

                if (amount > 0 && !charge) 
                {
                    this.form.total_charge = _.round(amount, 2)
                    this.form.total = _.round(this.form.total + this.form.total_charge, 2)
                    const global_charge = this.getGlobalCharge('50')

                    this.form.charges.push({
                        charge_type_id: global_charge.id,
                        description: global_charge.description,
                        factor: factor,
                        amount: amount,
                        base: base
                    })

                } 
                else 
                {

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
            deleteChargeGlobal() {

                let charge = _.find(this.form.charges, {charge_type_id: '50'})
                let index = this.form.charges.indexOf(charge)

                if (index > -1) {
                    this.form.charges.splice(index, 1)
                    this.form.total_charge = 0
                }

            },
            async saveCashDocument(sale_note_id){

                if(!this.id){

                    await this.$http.post(`/cash/cash_document`, {
                            document_id: null,
                            sale_note_id: sale_note_id,
                        })
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

                }

            },
            validatePaymentDestination(){

                let error_by_item = 0

                this.form.payments.forEach((item)=>{
                    if(!['05', '08', '09'].includes(item.payment_method_type_id)){
                        if(item.payment_destination_id == null) error_by_item++;
                    }
                })

                return  {
                    error_by_item : error_by_item,
                }

            },
            async submit() {

                let validate = await this.validate_payments()
                if(validate.acum_total > parseFloat(this.form.total) || validate.error_by_item > 0) {
                    return this.$message.error('Los montos ingresados superan al monto a pagar o son incorrectos');
                }

                if(this.form.type_period){
                    if(this.form.quantity_period == 0){
                        return this.$message.error('La cantidad de periodos debe ser mayor a 0');
                    }

                    this.form.enabled_concurrency = (this.form.quantity_period > 0) ? true:false
                }


                if(validate.acum_total == parseFloat(this.form.total) )
                {
                  this.form.paid = true
                }

                let validate_payment_destination = await this.validatePaymentDestination()

                if(validate_payment_destination.error_by_item > 0) {
                    return this.$message.error('El destino del pago es obligatorio');
                }

                if(!this.enabled_payments){
                    this.form.payments = []
                }
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                    if (response.data.success) {

                        this.form_payment.sale_note_id = response.data.data.id;
                        this.$eventHub.$emit('reloadDataItems', null)
                        // if(!this.id) this.sale_note_payment()
                        this.resetForm();
                        this.saleNotesNewId = response.data.data.id;
                        this.showDialogOptions = true;
                        this.saveCashDocument(response.data.data.id)

                        this.isUpdate()

                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    }
                    else {
                        this.$message.error(error.response.data.message);
                    }
                }).then(() => {
                        this.form.currency_type_id =  this.config.currency_type_id;
                        this.loading_submit = false;
                });
            },
            validate_payments(){

                for (let index = 0; index < this.form.payments.length; index++) {
                    if(parseFloat(this.form.payments[index].payment) === 0)
                        this.form.payments.splice(index, 1)
                }

                let error_by_item = 0
                let acum_total = 0

                this.form.payments.forEach((item)=>{
                    acum_total += parseFloat(item.payment)
                    if(item.payment <= 0 || item.payment == null) error_by_item++;
                })

                return  {
                    error_by_item : error_by_item,
                    acum_total : acum_total
                }

            },
            close() {
                location.href = '/sale-notes'
            },
            reloadDataCustomers(customer_id) {
                this.$http.get(`/${this.resource}/search/customer/${customer_id}`).then((response) => {
                    this.customers = response.data.customers
                    this.form.customer_id = customer_id
                })
            },
            async selectDefaultCustomer() {

                if (this.config.establishment.customer_id) {

                    let temp_all_customers = this.all_customers;
                    let temp_customers = this.customers;
                    await this.$http.get(`/${this.resource}/search/customer/${this.config.establishment.customer_id}`)
                        .then((response) => {
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
                    let alt = _.find(this.customers, {'id': this.config.establishment.customer_id});

                    if (alt !== undefined) {
                        this.form.customer_id = this.config.establishment.customer_id
                        let seller = this.sellers.find(element => element.id == alt.seller_id)
                        if(seller !== undefined){
                            this.form.seller_id = seller.id
                        }
                    }
                }
            },
            checkKeyWithAlt(e){
                let code = e.event.code;
                if(
                    this.showDialogOptions === true &&
                    code === 'KeyN'
                ){
                    this.showDialogOptions = false
                }

                if(
                    code === 'KeyG'  // key G
                    && !this.showDialogAddItem   // Modal hidden
                    && this.form.items.length > 0  // with items
                    && this.focus_on_client  === false // not client search
                )
                {
                     this.submit()
                }
            },
            checkKey(e){
                let code = e.event.code;
                if(code === 'F2'){
                    //abrir el modal de agergar producto
                    if(!this.showDialogAddItem ) this.showDialogAddItem = true
                }
                if(code === 'Escape'){
                    if(this.showDialogAddItem ) {
                        this.showDialogAddItem = false;
                    }

                }


            }

        }
    }
</script>
