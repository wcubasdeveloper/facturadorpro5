<template>
    <div class="dialog-modal">
        <el-dialog :close-on-click-modal="false"
                   :close-on-press-escape="false"
                   :show-close="false"
                   :title="titleDialog"
                   :visible="showDialog"
                   width="30%"
                   @open="create">
            <div v-show="!showGenerate"
                 class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 text-center font-weight-bold">
                    <p>Imprimir A4</p>
                    <button class="btn btn-lg btn-info waves-effect waves-light"
                            type="button"
                            @click="clickToPrint('a4')">
                        <i class="fa fa-file-alt"></i>
                    </button>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 text-center font-weight-bold">
                    <p>Imprimir A5</p>
                    <button class="btn btn-lg btn-info waves-effect waves-light"
                            type="button"
                            @click="clickToPrint('a5')">
                        <i class="fa fa-file-alt"></i>
                    </button>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 text-center font-weight-bold">
                    <p>Imprimir Ticket</p>
                    <button class="btn btn-lg btn-info waves-effect waves-light"
                            type="button"
                            @click="clickToPrint('ticket')">
                        <i class="fa fa-receipt"></i>
                    </button>
                </div>
            </div>
            <div v-show="!showGenerate"
                 class="row">
                <div class="col-md-12">
                    <el-input v-model="customer_email">
                        <el-button slot="append"
                                   :loading="loading"
                                   icon="el-icon-message"
                                   @click="clickSendEmail">Enviar
                        </el-button>
                    </el-input>
                </div>
            </div>
            <div v-if="typeUser === 'admin'"
                 class="row">
                <div v-show="!showGenerate"
                     class="col-md-9">
                    <div class="form-group">
                        <el-checkbox v-model="generate">Generar comprobante electrónico</el-checkbox>
                    </div>
                </div>
            </div>
            <div v-if="generate"
                 class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="control-label">Cliente</label>
                        <el-select v-model="form.customer_id"
                                   :loading="loading_search"
                                   :remote-method="searchRemoteCustomers"
                                   class="border-left rounded-left border-info"
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
                <div class="col-lg-4">
                    <div :class="{'has-danger': errors.document_type_id}"
                         class="form-group">
                        <label class="control-label">Tipo comprobante</label>
                        <el-select v-model="form.document_type_id"
                                   class="border-left rounded-left border-info"
                                   popper-class="el-select-document_type"
                                   @change="changeDocumentType">
                            <el-option v-for="option in document_types"
                                       :key="option.id"
                                       :label="option.name"
                                       :value="option.id"></el-option>
                        </el-select>
                        <small v-if="errors.document_type_id"
                               class="form-control-feedback"
                               v-text="errors.document_type_id[0]"></small>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div :class="{'has-danger': errors.series_id}"
                         class="form-group">
                        <label class="control-label">Serie</label>
                        <el-select v-model="form.series">
                            <el-option v-for="option in series"
                                       :key="option.number"
                                       :label="option.number"
                                       :value="option.number"></el-option>
                        </el-select>
                        <small v-if="errors.series_id"
                               class="form-control-feedback"
                               v-text="errors.series_id[0]"></small>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div :class="{'has-danger': errors.date_of_issue}"
                         class="form-group">
                        <label class="control-label">Fecha de emisión</label>
                        <el-date-picker v-model="form.date_of_issue"
                                        :clearable="false"
                                        format="dd/MM/yyyy"
                                        readonly
                                        type="date"
                                        value-format="yyyy-MM-dd"
                                        @change="changeDateOfIssue"></el-date-picker>
                        <small v-if="errors.date_of_issue"
                               class="form-control-feedback"
                               v-text="errors.date_of_issue[0]"></small>
                    </div>
                </div>
                <!--                <div class="col-lg-6">-->
                <!--                    <div class="form-group" :class="{'has-danger': errors.date_of_due}">-->
                <!--                        <label class="control-label">Fecha de vencimiento</label>-->
                <!--                        <el-date-picker v-model="form.date_of_due"-->
                <!--                                        type="date"-->
                <!--                                        value-format="yyyy-MM-dd"-->
                <!--                                        :clearable="false"></el-date-picker>-->
                <!--                        <small class="form-control-feedback"-->
                <!--                               v-if="errors.date_of_due"-->
                <!--                               v-text="errors.date_of_due[0]"></small>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--                <div class="col-lg-4">-->
                <!--                    <div class="form-group" v-show="form.document_type_id === '03'">-->
                <!--                        <el-checkbox v-model="form.is_receivable" class="font-weight-bold">¿Es venta por cobrar?-->
                <!--                        </el-checkbox>-->
                <!--                    </div>-->
                <!--                </div>-->
                <div v-if="form.items.length > 0"
                     class="col-lg-12">
                    <label class="control-label">Descripción del servicio</label>
                    <el-input v-model="form.items[0].description"
                              autosize
                              type="textarea"></el-input>
                </div>
                <div
                     class="col-lg-12">
                    <table style="width: 100%">
                        <thead>
                        <tr>
                            <th v-if="form.payments.length>0">M.Pago</th>
                            <th v-if="form.payments.length>0">Destino</th>
                            <th v-if="form.payments.length>0">Referencia</th>
                            <th v-if="form.payments.length>0">Monto</th>
                            <th style="width: 15px">
                                <a class="text-center font-weight-bold text-center text-info"
                                   href="#"
                                   style="font-size:18px"
                                   @click.prevent="clickAddPayment">[+]</a>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in form.payments"
                            :key="index">
                            <td>
                                <el-select v-model="row.payment_method_type_id">
                                    <el-option
                                        v-for="option in payment_method_types"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                            </td>
                            <td>
                                <el-select v-model="row.payment_destination_id"
                                           :disabled="row.payment_destination_disabled"
                                           filterable>
                                    <el-option v-for="option in payment_destinations"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                            </td>
                            <td>
                                <el-input v-model="row.reference"></el-input>
                            </td>
                            <td>
                                <el-input v-model="row.payment"></el-input>
                            </td>
                            <td class="series-table-actions text-center">
                                <button class="btn waves-effect waves-light btn-xs btn-danger"
                                        type="button"
                                        @click.prevent="clickCancel(index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <span slot="footer"
                  class="dialog-footer">
                <template v-if="showClose">
                    <el-button @click="clickClose">Cerrar</el-button>
                    <el-button v-if="generate && !form.has_document_sale_note"
                               :loading="loading_submit"
                               class="submit"
                               type="primary"
                               @click="submit">Generar</el-button>
                </template>
                <template v-else>
                    <el-button v-if="generate"
                               :loading="loading_submit"
                               class="submit"
                               plain
                               type="primary"
                               @click="submit">Generar comprobante</el-button>
                    <el-button v-else
                               @click="clickFinalize">Ir al listado</el-button>
                    <el-button type="primary"
                               @click="clickNew">Nueva cotización</el-button>
                </template>
            </span>
        </el-dialog>

        <document-options :isContingency="false"
                          :recordId="documentNewId"
                          :showClose="true"
                          :showDialog.sync="showDialogDocumentOptions"></document-options>

        <sale-note-options :recordId="documentNewId"
                           :showClose="true"
                           :showDialog.sync="showDialogSaleNoteOptions"></sale-note-options>
    </div>
</template>

<script>

import DocumentOptions from "@views/documents/partials/options";
import SaleNoteOptions from "@views/sale_notes/partials/options";
import queryString from 'query-string'

// import DocumentOptions from "../../documents/partials/options.vue";
// import SaleNoteOptions from "../../sale_notes/partials/options.vue";
import SeriesForm from "./series_form";
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    components: {DocumentOptions, SaleNoteOptions, SeriesForm},

    computed: {
        ...mapState([
            'exchange_rate',
            'config',
            'currency_types',
        ]),
    },
    props: [
        "showDialog",
        "recordId",
        "showClose",
        "showGenerate",
        "type",
        "typeUser",
        "exchange_rate_sale",
    ],
    data() {
        return {
            customer_email: "",
            titleDialog: null,
            loading: false,
            resource: "technical-services",
            resource_documents: "documents",
            errors: {},
            form: {},
            document: {},
            document_types: [],
            all_document_types: [],
            all_series: [],
            series: [],
            establishment: {},
            customers: [],
            generate: false,
            loading_submit: false,
            showDialogDocumentOptions: false,
            showDialogSaleNoteOptions: false,
            documentNewId: null,
            is_document_type_invoice: true,
            payment_destinations: [],
            loading_search: false,
            payment_method_types: [],
            record: {}
        };
    },
    async created() {
        this.loadConfiguration();
        this.loadExchangeRate();
        this.loadCurrencyTypes();
        await this.initTables();
        await this.searchRemoteCustomers('');
        this.initForm();
        this.filterSeries();
        // this.initForm();
        //this.initDocument();
        // this.clickAddPayment();
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
            'loadExchangeRate',
            'loadCurrencyTypes',
        ]),
        async initTables() {
            await this.$http.get(`/generate-document/tables`)
                .then((response) => {
                    this.establishment = response.data.establishment;
                    this.document_types = response.data.document_types;
                    this.all_series = response.data.series;
                    this.payment_destinations = response.data.payment_destinations;
                    this.payment_method_types = response.data.payment_method_types;
                });
        },
        initForm() {
            this.generate = !!this.showGenerate;
            this.errors = {};
            this.form = {
                id: null,
                external_id: null,
                operation_type_id: '0101',
                establishment_id: null,
                document_type_id: '01',
                series: null,
                number: "#",
                date_of_issue: moment().format("YYYY-MM-DD"),
                time_of_issue: moment().format("HH:mm:ss"),
                date_of_due: moment().format("YYYY-MM-DD"),
                customer_id: null,
                is_receivable: false,
                currency_type_id: 'PEN',
                exchange_rate_sale: this.exchange_rate_sale,
                total_taxed: 0,
                total_igv: 0,
                total_taxes: 0,
                total_value: 0,
                total: 0,
                items: [],
                payments: [],
                prefix: null,
            };
        },
        async create() {
            await this.getRecord()
        },
        async getRecord(){

            this.loading = true;

            await this.$http.get(`/generate-document/record/technical-services/${this.recordId}`)
                .then((response) => {
                    this.record = response.data.data;
                    this.form.establishment_id = this.establishment.id;
                    this.form.customer_id = this.record.customer_id;
                    // this.form.date_of_issue = this.record.date_of_issue;
                    // this.form.time_of_issue = this.record.time_of_issue;
                    this.form.date_of_due = this.record.date_of_issue;
                    // this.form.items.push({
                    //     'description': `Descripción: ${this.record.description+"\n"}Estado: ${this.record.state+"\n"}Razón: ${this.record.reason+"\n"}`,
                    //     'unit_price': this.record.cost
                    // });
                    let total = _.round(parseFloat(this.record.cost), 2);
                    let unit_value = this.record.cost / 1.18;
                    let total_taxed = _.round(unit_value, 2);
                    let total_igv = _.round(total - total_taxed, 2);
                    let item_description = `Descripción: ${this.record.description}, Estado: ${this.record.state}, Razón: ${this.record.reason + "\n"}`;
                    if (this.record.items !== undefined) {
                        this.form.items = this.record.items
                    }

                    this.form.technical_service_id = this.recordId

                    this.form.items.push({
                        'id': null,
                        'item_id': null,
                        'internal_id': moment().format("YYYYMMDDHHmmss"),
                        'item_type_id': '02',
                        'has_igv': true,
                        'price_type_id': '01',
                        'unit_type_id': 'ZZ',
                        'affectation_igv_type_id': '10',
                        'description': item_description,
                        'percentage_igv': 18,
                        'currency_type_id': 'PEN',
                        'unit_value': unit_value,
                        'unit_price': total,
                        'total_base_igv': total_taxed,
                        'total_igv': total_igv,
                        'total_value': total_taxed,
                        'total_taxes': total_igv,
                        'total': total,
                        'quantity': 1,
                        'discounts': [],
                        'charges': [],

                    });

                    total = 0;
                    total_taxed = 0
                    total_igv = 0
                    this.form.items.forEach((row) => {
                        total += row.total;
                        total_igv += row.total_igv;
                        total_taxed += row.total_value;
                    });

                    this.form.items = this.onPrepareItems(this.form.items)
                    this.form.total_taxed = total_taxed;
                    this.form.total_igv = total_igv;
                    this.form.total_taxes = total_igv;
                    this.form.total_value = total_taxed;
                    this.form.total = total;

                    this.form.payments = this.record.payments
                    this.form.has_document_sale_note = this.record.has_document_sale_note

                    this.titleDialog = `Servicio de soporte técnico`;
                });

            this.loading = false;

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
        onPrepareIndividualItem(data) {

            let new_item = data.item
            if(data.item === undefined){
                new_item = {};
            }
            if (this.form.currency_type_id === undefined) {
                this.form.currency_type_id = 'PEN'
            }
            let currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})


            if (currency_type !== undefined) {
                new_item.currency_type_id = currency_type.id
                new_item.currency_type_symbol = currency_type.symbol
            }else{
                new_item.currency_type_id = 'PEN'
                new_item.currency_type_symbol  = "S/";
            }

            new_item.sale_affectation_igv_type_id = data.affectation_igv_type_id
            new_item.sale_unit_price = data.unit_price
            new_item.unit_price = data.unit_price
            return new_item
        },
        onPrepareItems(items) {
            return items.map(i => {

                i.unit_price_value = i.unit_value;
                i.input_unit_price_value = (i.has_igv) ? i.unit_value: i.unit_price ;

                // i.input_unit_price_value = i.unit_price;
                i.discounts = (i.discounts) ? Object.values(i.discounts) : []
                // i.discounts = i.discounts || [];
                i.charges = i.charges || [];
                i.attributes = i.attributes || [];
                if(i.item_id !== null) {
                    i.item.id = i.item_id;
                }

                i.additional_information = this.onPrepareAdditionalInformation(i.additional_information);
                i.item = this.onPrepareIndividualItem(i);

                return i;
            });
        },
        async searchRemoteCustomers(input) {
            this.loading_search = true;
            await this.$http.post(`/generate-document/customers`, {
                'input': input
            })
                .then((response) => {
                    this.customers = response.data.customers;
                })
                .then(() => {
                    this.loading_search = false;
                });
        },
        clickCancel(index) {
            this.form.payments.splice(index, 1);
        },
        clickAddPayment() {
            this.form.payments.push({
                id: null,
                document_id: null,
                date_of_payment: moment().format("YYYY-MM-DD"),
                payment_method_type_id: "01",
                payment_destination_id: null,
                reference: null,
                payment: 0,
            });
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
                exchange_rate_sale: this.exchange_rate_sale,
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
                technical_service_id: null,
                is_receivable: false,
                payments: [],
                hotel: {},
            };
        },
        changeDateOfIssue() {
            this.document.date_of_due = this.document.date_of_issue;
        },
        resetDocument() {
            this.generate = !!this.showGenerate;
            this.initDocument();
            this.document.document_type_id =
                this.document_types.length > 0
                    ? this.document_types[0].id
                    : null;
            this.changeDocumentType();
        },
        validatePaymentDestination() {

            let error_by_item = 0

            this.form.payments.forEach((item) => {
                if (item.payment_destination_id == null) error_by_item++;
            })

            return {
                error_by_item: error_by_item,
            }

        },
        async submit() {
            // await this.assignDocument();
            //
            let validate_payment_destination = await this.validatePaymentDestination()
            
            if (validate_payment_destination.error_by_item > 0) {
                return this.$message.error('El destino del pago es obligatorio');
            }

            this.loading_submit = true;
            if (this.form.document_type_id === "nv") {
                this.form.prefix = "NV";
                this.resource_documents = "sale-notes";
            } else {
                this.form.prefix = null;
                this.resource_documents = "documents";
            }

            // let item_id = null;
            // await this.$http.post(`/generate-document/store_item`, this.form.items[0])
            //     .then(res => {
            //         item_id = res.data;
            //     });
            //
            // console.log(item_id);
            // this.form.items[0].item_id = item_id;

            // await this.$http.post(`/${this.resource_documents}`, this.form)
            if(
                this.form.exchange_rate_sale === undefined
            ){
                this.form.exchange_rate_sale =  this.exchange_rate;
            }
                await this.$http.post(`/generate-document`, this.form)
                .then((response) => {
                    if (response.data.success) {
                        // console.log(response.data.data);
                        this.documentNewId = response.data.data.id;

                        this.getRecord()
                        // this.$http
                        //     .get(`/${this.resource}/changed/${this.form.id}`)
                        //     .then(() => {
                        //         this.$eventHub.$emit("reloadData");
                        //     });
                        // // console.log(this.document.document_type_id)
                        if (this.form.document_type_id === "nv") {
                            this.showDialogSaleNoteOptions = true;
                        } else {
                            this.showDialogDocumentOptions = true;
                        }

                        this.$eventHub.$emit("reloadData");
                        //this.resetDocument();
                        //this.document.customer_id = this.form.quotation.customer_id;
                        //this.changeCustomer();
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
            this.document.customer_id = this.form.customer_id
            this.document.currency_type_id = q.currency_type_id;
            this.document.purchase_order = null;
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
            this.document.total_base_isc = q.total_base_isc;
            this.document.total_isc = q.total_isc;
            this.document.total_base_other_taxes = q.total_base_other_taxes;
            this.document.total_other_taxes = q.total_other_taxes;
            this.document.total_taxes = q.total_taxes;
            this.document.total_value = q.total_value;
            this.document.total = q.total;
            this.document.operation_type_id = "0101";
            // this.document.date_of_due = q.date_of_issue
            this.document.items = q.items;
            this.document.charges = q.charges;
            this.document.discounts = q.discounts;
            this.document.attributes = [];
            // this.document.payments = q.payments;
            this.document.guides = q.guides;
            this.document.additional_information = null;
            this.document.actions = {
                format_pdf: "a4",
            };
            this.document.is_receivable = false;
            this.document.quotation_id = this.form.id;
            _.forEach(this.document.items, row => {
                row.name_product_pdf = row.item.name_product_pdf;
            })
        },
        changeDocumentType() {
            this.series = [];
            if (this.form.document_type_id !== "nv") {
                this.filterSeries();
                this.is_document_type_invoice = true;
            } else {
                this.series = _.filter(this.all_series, {
                    document_type_id: "80",
                });
                this.form.series = this.series.length > 0 ? this.series[0].number : null;
                this.is_document_type_invoice = false;
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
        },
        filterSeries() {
            this.form.series = null;
            this.series = _.filter(this.all_series, {'document_type_id': this.form.document_type_id});
            this.form.series = this.series.length > 0 ? this.series[0].number : null;
        },
        clickFinalize() {
            location.href = `/${this.resource}`;
        },
        clickNew() {
            this.clickClose();
        },
        clickClose() {
            this.$emit("update:showDialog", false);
            this.initForm();
            this.resetDocument();
        },
        clickToPrint(format) {
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
    },
};
</script>
