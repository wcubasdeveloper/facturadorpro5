<template>
    <div class="card mb-0" v-loading="loading">
        <div class="card-header bg-info">
            Nueva Nota ({{ document.series }}-{{ document.number }})
        </div>
        <div class="card-body">
            <form autocomplete="off" @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <el-checkbox v-model="is_contingency" @change="changeDocumentType">¿Es comprobante de contigencia?</el-checkbox>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group" :class="{'has-danger': errors.document_type_id}">
                                <label class="control-label">Tipo comprobante</label>
                                <el-select v-model="form.document_type_id" @change="changeDocumentType">
                                    <el-option v-for="option in document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.document_type_id" v-text="errors.document_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" :class="{'has-danger': errors.series_id}">
                                <label class="control-label">Serie</label>
                                <el-select v-model="form.series_id">
                                    <el-option v-for="option in series" :key="option.id" :value="option.id" :label="option.number"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.series_id" v-text="errors.series_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <template v-if="form.document_type_id === '08'">
                                <div class="form-group" :class="{'has-danger': errors['note.note_debit_type_id']}">
                                    <label class="control-label">Tipo nota de débito</label>
                                    <el-select v-model="form.note_credit_or_debit_type_id" @change="changeNoteDebitType">
                                        <el-option v-for="option in note_debit_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors['note.note_debit_type_id']" v-text="errors['note.note_debit_type_id'][0]"></small>
                                </div>
                            </template>
                            <template v-else>
                                <div class="form-group" :class="{'has-danger': errors['note.note_credit_type_id']}">
                                    <label class="control-label">Tipo nota de crédito</label>
                                    <el-select v-model="form.note_credit_or_debit_type_id" @change="changeNoteCreditType">
                                        <el-option v-for="option in note_credit_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors['note.note_credit_type_id']" v-text="errors['note.note_credit_type_id'][0]"></small>
                                </div>
                            </template>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group" :class="{'has-danger': errors['note.note_description']}">
                                <label class="control-label">Descripción</label>
                                <el-input v-model="form.note_description"></el-input>
                                <small class="form-control-feedback" v-if="errors['note.note_description']" v-text="errors['note.note_description'][0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Formato de PDF</label>
                                <el-select v-model="form.actions.format_pdf" >
                                    <el-option key="a4" value="a4" label="Tamaño A4"></el-option>
                                    <el-option key="ticket" value="ticket" label="Tamaño Ticket"></el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.customer_id}">
                                <label class="control-label">Cliente</label>
                                <el-select v-model="form.customer_id" filterable :disabled="true">
                                    <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                                <label class="control-label">Moneda</label>
                                <el-select v-model="form.currency_type_id" :disabled="true">
                                    <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                <label class="control-label">Fec. Emisión</label>
                                <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" :class="{'has-danger': errors.purchase_order}">
                                <label class="control-label">Orden Compra</label>
                                <el-input v-model="form.purchase_order"></el-input>
                                <small class="form-control-feedback" v-if="errors.purchase_order" v-text="errors.purchase_order[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
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
                        <div class="col-md-4 custom-mt-button" v-if="isCreditNote && hasDiscounts">
                            <div class="form-group">
                                <el-popover
                                    placement="right"
                                    width="700"
                                    trigger="click"> 
                                    
                                    <div class="table-responsive" style="max-height: 450px;overflow: scroll;max-width: 700px">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Descripción</th>
                                                    <th>Cantidad</th>
                                                    <th>P. Unitario</th>
                                                    <th>Descuento</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(row, index) in document_affected.items" :key="index">
                                                    <td>{{ index + 1 }}</td>
                                                    <td>{{ row.item.description }}</td>
                                                    <td>{{ row.quantity }}</td>
                                                    <td>{{ row.unit_price }}</td>
                                                    <td>{{ row.total_discount }}</td>
                                                    <td>{{ row.total }}</td> 
                                                </tr>
                                                <tr>
                                                    <td colspan="6"><b>Descuento: </b> {{ document_affected.total_discount }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6"><b>Total cpe: </b> {{ document_affected.total }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <el-button slot="reference" type="success"> <i class="fa fa-eye"></i> Documento relacionado</el-button>
                                </el-popover>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-6 d-flex align-items-end pt-2">
                            <div class="form-group">
                                <button type="button" class="btn waves-effect waves-light btn-primary" @click.prevent="clickAddItemNote()">+ Agregar Producto</button>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="form.items.length > 0">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th class="text-center">Unidad</th>
                                        <th class="text-right">Cantidad</th>
                                        <th class="text-right">Precio Unitario</th>
                                        <th class="text-right">Descuento</th>
                                        <th class="text-right">Cargo</th>
                                        <th class="text-right">Total</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in form.items" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ row.item.description }}<br/><small>{{ row.affectation_igv_type.description }}</small></td>
                                        <td class="text-center">{{ row.item.unit_type_id }}</td>
                                        <td class="text-right">{{ row.quantity }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.unit_price }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total_discount }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total_charge }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>
                                        <td class="text-right">
                                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="ediItem(row, index)" ><span style='font-size:10px;'>&#9998;</span> </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-right" v-if="form.total_exportation > 0">OP.EXPORTACIÓN: {{ currency_type.symbol }} {{ form.total_exportation }}</p>
                            <p class="text-right" v-if="form.total_free > 0">OP.GRATUITAS: {{ currency_type.symbol }} {{ form.total_free }}</p>
                            <p class="text-right" v-if="form.total_unaffected > 0">OP.INAFECTAS: {{ currency_type.symbol }} {{ form.total_unaffected }}</p>
                            <p class="text-right" v-if="form.total_exonerated > 0">OP.EXONERADAS: {{ currency_type.symbol }} {{ form.total_exonerated }}</p>
                            <p class="text-right" v-if="form.total_taxed > 0">OP.GRAVADA: {{ currency_type.symbol }} {{ form.total_taxed }}</p>
                            <p class="text-right" v-if="form.total_igv > 0">IGV: {{ currency_type.symbol }} {{ form.total_igv }}</p>
                            <p class="text-right" v-if="form.total_isc > 0">ISC: {{ currency_type.symbol }} {{ form.total_isc }}</p>
                            <p class="text-right" v-if="form.total_charge > 0">OTROS CARGOS: {{ currency_type.symbol }} {{ form.total_charge }}</p>

                            <template v-if="isCreditNoteAndType13 || isCreditNoteAndType03">
                                <h3 class="text-right"><b>TOTAL A PAGAR: </b>{{ currency_type.symbol }} {{ form.total }}</h3>
                            </template>
                            <template v-else>
                                <h3 class="text-right" v-if="form.total > 0"><b>TOTAL A PAGAR: </b>{{ currency_type.symbol }} {{ form.total }}</h3>
                            </template>
                        </div>
                        <div class="col-md-8"></div>
                        
                        <div class="col-md-4">
                            <!-- Crédito -->
                            <template v-if="form.payment_condition_id === '02' && isCreditNoteAndType13">
                                <table v-if="form.fee.length>0"
                                        class="text-left"
                                        width="100%">
                                    <thead>
                                    <tr>
                                        <th class="text-left"
                                            style="width: 100px">Fecha
                                        </th>
                                        <th class="text-left"
                                            style="width: 100px">Monto
                                        </th>
                                        <th style="width: 30px"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in form.fee"
                                        :key="index">
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
                                                    @click.prevent="clickAddFee"><i class="fa fa-plus font-weight-bold text-info"></i>
                                                    <span style="color: #777777">Agregar cuota</span></a>

                                            </label>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <template v-if="isCreditNoteAndType13 || isCreditNoteAndType03">
                        <el-button type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0">Generar</el-button>
                    </template>
                    <template v-else>
                        <el-button type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0 && form.total > 0">Generar</el-button>
                    </template>
                </div>
            </form>
        </div>

        <document-form-item :showDialog.sync="showDialogAddItem"
                            :recordItem="recordItem"
                            :isEditItemNote="isEditItemNote"
                            :documentTypeId="form.document_type_id"
                            :noteCreditOrDebitTypeId="form.note_credit_or_debit_type_id"
                            :operation-type-id="form.operation_type_id"
                            :currency-type-id-active="form.currency_type_id"
                            :typeUser="user"
                            :exchange-rate-sale="form.exchange_rate_sale"
                            :configuration="configuration"
                            :editNameProduct="configuration.edit_name_product"
                            @add="addRow"></document-form-item>

        <document-options :showDialog.sync="showDialogOptions"
                          :recordId="documentNewId"
                          :showClose="false"></document-options>
    </div>
</template>

<script>

    import DocumentFormItem from './partials/item.vue'
    import DocumentOptions from '../documents/partials/options.vue'
    import {functions, exchangeRate} from '../../../mixins/functions'
    import {calculateRowItem} from '../../../helpers/functions'

    export default {
        components: {DocumentFormItem, DocumentOptions},
        mixins: [functions, exchangeRate],
        props: ['document_affected', 'configuration'],
        data() {
            return {
                recordItem: null,
                isEditItemNote:false,
                showDialogAddItem: false,
                showDialogOptions: false,
                loading_submit: false,
                loading: false,
                resource: 'documents',
                errors: {},
                form: {},
                document_types: [],
                currency_types: [],
                customers: [],
                all_series: [],
                series: [],
                currency_type: {},
                documentNewId: null,
                note_credit_types: [],
                note_debit_types: [],
                user: {},
                document: {},
                operation_types: [],
                is_contingency: false,
                affected_documents: [],
                temp_total:0,
                selected_credit_note_type_13: false,
                apply_change_has_discounts: false,
                selected_credit_note_type_03: false,
            }
        },
        created() {
            this.document = this.document_affected
            this.initForm()
             this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.document_types = response.data.document_types_note
                    this.currency_types = response.data.currency_types
                    this.all_series = response.data.series
                    // this.customers = response.data.customers
                    this.note_credit_types = response.data.note_credit_types
                    this.note_debit_types = response.data.note_debit_types
                    this.operation_types = response.data.operation_types
                    this.user = response.data.user;

                    this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
                    this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
                    // this.form.operation_type_id = (this.operation_types.length > 0)?this.operation_types[0].id:null

                    this.changeDocumentType()
                    this.changeDateOfIssue()
                })

             this.getCustomer()
             this.getHasDocuments()


        },
        mounted() {

        },
        computed: { 
            isCreditNoteAndType13: function () {
                return (this.form.document_type_id === '07' && this.form.note_credit_or_debit_type_id === '13')
            },
            isCreditNoteAndType03: function () {
                return (this.form.document_type_id === '07' && this.form.note_credit_or_debit_type_id === '03')
            },
            hasDiscounts: function () {
                return (parseFloat(this.document_affected.total_discount) > 0)
            },
            isCreditNote: function () {
                return (this.form.document_type_id === '07')
            },
        }, 
        methods: {
            onPrepareIndividualItem(data) {

                let new_item = data.item
                new_item.currency_type_id = this.form.currency_type_id
                new_item.sale_affectation_igv_type_id = data.affectation_igv_type_id
                new_item.sale_unit_price = 0
                new_item.unit_price = 0

                return new_item
            },
            onPrepareItems(items) {
                return items.map(i => {

                    i.unit_price_value = 0;
                    i.input_unit_price_value = 0;
                    i.discounts = [] //no existe tags en xml para descuentos
                    // i.discounts = (i.discounts) ? Object.values(i.discounts) : []
                    i.charges = i.charges || [];
                    i.attributes = i.attributes || [];
                    i.item.id = i.item_id;
                    i.additional_information = this.onPrepareAdditionalInformation(i.additional_information);
                    i.item = this.onPrepareIndividualItem(i);
                    return i;
                });
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

                return obs;
            },
            async recalculateItems() {
                
                let items = []

                //si el cpe relacionado tiene descuentos, se asigna los items para que pueda recalcular a monto 0
                if(this.hasDiscounts && this.form.items.length == 0){
                    this.form.items = this.document.items
                }
                
                await this.onPrepareItems(this.form.items).forEach((row) => {
                    items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
                })

                this.form.items = items
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
            calculateFee() {
                let fee_count = this.form.fee.length;
                let total = this.temp_total;
                // let total = this.form.total;

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
            changeNoteDebitType(){
            },
            changeNoteCreditType(){

                if(this.isCreditNoteAndType13)
                {
                    // Ajustes – montos y/o fechas de pago

                    //si la condicion de pago del cpe relacionado es diferente de credito, no es posible usar el tipo de nota = 13
                    if(this.document_affected.payment_condition_id !== '02'){
                        this.form.note_credit_or_debit_type_id = null
                        return this.$message.error('Para el tipo de nota de crédito seleccionada, el comprobante relacionado debe ser al crédito');
                    }

                    this.form.payment_condition_id = '02'

                    //cuando es tipo de nota credito = 13, se deben enviar los valores en 0, por ello se recalculan todos los montos
                    this.clickAddFee()
                    this.recalculateItems()

                    // variable usada para determinar si al menos usaron una vez el tipo de nota 13
                    this.selected_credit_note_type_13 = true

                }
                else if(this.isCreditNoteAndType03)
                {
                    //Corrección por error en la descripción

                    //cuando es tipo de nota credito = 03 se recalculan los montos a 0
                    this.recalculateItems()
                    // variable usada para determinar si al menos usaron una vez el tipo de nota 03
                    this.selected_credit_note_type_03 = true
                }
                else
                {
                    // Otros
                    this.initData()
                }

            },
            async initData(){

                // si se seleccionó el tipo de nota 13, se deberá reiniciar la data
                if(this.selected_credit_note_type_13 || this.selected_credit_note_type_03){

                    //si no tiene descuento, se puede inicializar la data, caso contrario los items se deben agregar manualmente
                    if(!this.hasDiscounts){

                        this.form.payment_condition_id = null
                        this.form.fee = []
                        await this.getNote()
                        await this.initFormCreditNoteAndType13()
                        // this.form.operation_type_id = (this.operation_types.length > 0)?this.operation_types[0].id:null
                        // this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
                        // this.form.series_id = (this.series.length > 0)?this.series[0].id:null

                    }else{
                        this.form.items = []
                        this.form.fee = []
                    }

                }

            },
            async initFormCreditNoteAndType13() {
                
                this.errors = {}

                this.form.establishment_id= this.document.establishment_id
                // this.form.document_type_id= null
                // this.form.series_id= null
                this.form.number = '#'
                // this.form.date_of_issue= moment().format('YYYY-MM-DD')
                // this.form.time_of_issue= moment().format('HH:mm:ss')
                this.form.customer_id= this.document.customer_id
                this.form.currency_type_id= this.document.currency_type_id
                this.form.purchase_order= null
                this.form.exchange_rate_sale= this.document.exchange_rate_sale
                this.form.total_prepayment =this.document.total_prepayment
                this.form.total_charge= this.document.total_charge
                // this.form.total_discount= this.document.total_discount
                this.form.total_exportation= this.document.total_exportation
                this.form.total_free= this.document.total_free
                this.form.total_taxed= this.document.total_taxed
                this.form.total_unaffected= this.document.total_unaffected
                this.form.total_exonerated= this.document.total_exonerated
                this.form.total_igv= this.document.total_igv
                this.form.total_base_isc= this.document.total_base_isc
                this.form.total_isc= this.document.total_isc
                this.form.total_base_other_taxes= this.document.total_base_other_taxes
                this.form.total_other_taxes= this.document.total_other_taxes
                this.form.total_plastic_bag_taxes= this.document.total_plastic_bag_taxes
                this.form.total_taxes= this.document.total_taxes
                this.form.total_value= this.document.total_value
                this.form.total= this.document.total
                this.form.items= this.document.items
                this.form.affected_document_id= this.document.id
                this.form.note_description= null
                this.form.actions= {
                    format_pdf: 'a4'
                }
                // this.form.operation_type_id= null
                this.form.hotel= {}
                this.form.charges= this.document.charges ? Object.values(this.document.charges) : null
                this.form.payment_condition_id = null
                this.form.fee = []

                await this.form.items.forEach((item)=>{
                    item.input_unit_price_value = item.unit_price
                    item.additional_information = null
                    item.IdLoteSelected = item.item.IdLoteSelected
                })

                this.temp_total = this.form.total

            },
            async initForm() {

                // console.log(this.hasDiscounts)

                this.errors = {}
                this.form = {
                    establishment_id: this.document.establishment_id,
                    document_type_id: null,
                    series_id: null,
                    number: '#',
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    customer_id: this.document.customer_id,
                    currency_type_id: this.document.currency_type_id,
                    purchase_order: null,
                    exchange_rate_sale: 0,
                    total_prepayment:this.document.total_prepayment,
                    total_charge: this.document.total_charge,
                    // total_discount: this.document.total_discount,
                    total_exportation: this.document.total_exportation,
                    total_free: this.document.total_free,
                    total_taxed: this.document.total_taxed,
                    total_unaffected: this.document.total_unaffected,
                    total_exonerated: this.document.total_exonerated,
                    total_igv: this.document.total_igv,
                    total_base_isc: this.document.total_base_isc,
                    total_isc: this.document.total_isc,
                    total_base_other_taxes: this.document.total_base_other_taxes,
                    total_other_taxes: this.document.total_other_taxes,
                    total_plastic_bag_taxes: this.document.total_plastic_bag_taxes,
                    total_taxes: this.document.total_taxes,
                    total_value: this.document.total_value,
                    total: this.document.total,
                    items: this.document.items,
                    affected_document_id: this.document.id,
                    note_credit_or_debit_type_id: null,
                    note_description: null,
                    actions: {
                        format_pdf: 'a4'
                    },
                    // operation_type_id: null,
                    operation_type_id: this.document.invoice.operation_type_id, //se asigna el t. operacion del documento relacionado para filtrar en form item el tipo de afectacion
                    hotel: {},
                    charges: this.document.charges ? Object.values(this.document.charges) : null,
                    payment_condition_id : null,
                    fee : [],
                }


                await this.form.items.forEach((item)=>{
                    item.input_unit_price_value = item.unit_price
                    item.additional_information = null
                    item.IdLoteSelected = item.item.IdLoteSelected
                })

                this.temp_total = this.form.total
                this.selected_credit_note_type_13 = false
                this.selected_credit_note_type_03 = false
                this.apply_change_has_discounts = false

            },
            validateHasDiscounts(){

                if(this.isCreditNote && this.hasDiscounts){

                    this.$message.error('El comprobante relacionado tiene descuentos, debe agregar los productos');
                    this.form.items = []
                    this.calculateTotal()
                    this.apply_change_has_discounts = true

                }else{
                    //inicializar data cuando se haya aplicado modificacion a los items por descuento
                    if(this.apply_change_has_discounts){
                        this.initFormCreditNoteAndType13()
                        // console.log("app")
                    }
                }

            },
            clickAddItemNote(){
                this.recordItem = null
                this.isEditItemNote = false
                this.showDialogAddItem = true
            },
            ediItem(row, index)
            {

                if(this.form.document_type_id == '07' && !this.form.note_credit_or_debit_type_id){
                    return this.$message.error('Elija una opción del campo Tipo nota de crédito');
                }

                row.indexi = index
                this.recordItem = row
                this.isEditItemNote = true
                this.showDialogAddItem = true

            },
            async resetForm() {
                await this.getNote()
                await this.getHasDocuments()
                await this.initForm()
                // this.form.operation_type_id = (this.operation_types.length > 0)?this.operation_types[0].id:null
                this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
                this.changeDocumentType()
                this.changeDateOfIssue()
            },
            async getNote(){
                this.loading = true
                await this.$http.get(`/${this.resource}/note/record/${this.form.affected_document_id}`)
                    .then(response => {
                        // console.log(response)
                        this.document = response.data
                        // this.getHasDocuments()
                    })
                    .then(()=>{
                        this.loading = false
                    })
            },
            getHasDocuments(){

                this.$http.get(`/${this.resource}/note/has-documents/${this.form.affected_document_id}`)
                    .then(response => {

                        if(response.data.success){

                            this.affected_documents = response.data.data

                            let message = `<strong>El CPE ${ this.document.series }-${ this.document.number } ya tiene notas generadas</strong><br/>`

                            this.affected_documents.forEach(document => {
                                message += `${document.document_type_description}: ${document.description}<br/>`
                            });

                            this.$notify({
                                title: "",
                                dangerouslyUseHTMLString: true,
                                message: message,
                                type: "warning",
                                duration: 6000
                            })
                        }

                    })

            },
            changeDocumentType() {
                this.form.note_credit_or_debit_type_id = null
                this.form.series_id = null
                if(this.is_contingency) {
                    this.series = _.filter(this.all_series, {'document_type_id': this.form.document_type_id,
                                                             'contingency': this.is_contingency});
                } else {
                    let document_type = _.find(this.document_types, {id: this.form.document_type_id})
                    let firstChar = (this.document.group_id === '01')?'F':'B'
                    this.series = _.filter(this.all_series, (s) =>{
                        return (s.document_type_id === document_type.id && s.number.substr(0, 1) === firstChar)
                    });
                }

                this.form.series_id = (this.series.length > 0)?this.series[0].id:null

                this.initData()
                this.validateHasDiscounts()

            },
            changeDateOfIssue() {
                this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                    this.form.exchange_rate_sale = response
                })
            },
            addRow(row) {

                if(this.recordItem){

                    this.form.items[this.recordItem.indexi] = row
                    this.recordItem = null

                }
                else{

                    this.form.items.push(JSON.parse(JSON.stringify(row)));

                }

                // this.form.items.push(row)
                this.calculateTotal()
            },
            clickRemoveItem(index) {
                this.form.items.splice(index, 1)
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
                    total_value += parseFloat(row.total_value)
                    total_igv += parseFloat(row.total_igv)
                    total += parseFloat(row.total)
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
                this.form.total = _.round(total, 2) + this.form.total_plastic_bag_taxes

            },
            async submit() {

                if(this.isCreditNote && this.hasDiscounts && this.form.total > this.document.total){
                    return this.$message.error(`El monto total de la nota de credito debe ser menor o igual al monto del documento relacionado (${this.document.total})`)
                }

                this.loading_submit = true
                await this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.resetForm()
                            this.documentNewId = response.data.data.id
                            this.showDialogOptions = true
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
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            getCustomer(){
                this.$http.get(`/${this.resource}/search/customer/${this.document.customer_id}`).then((response) => {
                    this.customers = response.data.customers
                    this.form.customer_id = this.document.customer_id
                })
            },
            close() {
                location.href = '/documents'
            }
        }
    }
</script>
