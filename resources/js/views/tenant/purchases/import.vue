<template>
    <el-dialog
        :title="titleDialog"
        :visible="showDialog"
        class="dialog-import"
        @close="close"
        @open="create"
    >
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12 mt-4">
                        <div :class="{'has-danger': errors.file}" class="form-group text-center">
                            <el-upload
                                ref="upload"
                                :auto-upload="false"
                                :limit="1"
                                :multiple="false"
                                :on-change="handleChange"
                                :show-file-list="true"
                                action="''"
                            >
                                <el-button slot="trigger" type="primary">Seleccione un archivo (xml)</el-button>
                            </el-upload>
                            <small v-if="errors.file" class="form-control-feedback" v-text="errors.file[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button v-if='has_file' :loading="loading_submit" native-type="submit" type="primary">Procesar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import {calculateRowItem} from "../../../helpers/functions";
import {mapActions, mapState} from "vuex";

export default {
    props: ["showDialog"],
    data() {
        return {
            has_file: false,
            loading_submit: false,
            headers: headers_token,
            titleDialog: null,
            resource: "purchases",
            errors: {},
            form: {},
            formXmlJson: {},
            items: [],
            affectation_igv_types: [],
            system_isc_types: [],
            discount_types: [],
            charge_types: [],
            attribute_types: [],
        };
    },
    created() {
        this.loadWarehouses(this.$store)
        this.loadAllItems(this.$store)

        this.$http.get(`/${this.resource}/item/tables`).then(response => {
            this.items = response.data.items;
            this.affectation_igv_types = response.data.affectation_igv_types;
            this.system_isc_types = response.data.system_isc_types;
            this.discount_types = response.data.discount_types;
            this.charge_types = response.data.charge_types;
            this.attribute_types = response.data.attribute_types;
            this.$store.commit('setWarehouses', response.data.warehouses)
            //this.$store.commit('setAllItems', response.data.items)
        });
        this.initForm();
    },
    mounted(){
        this.$http.post(`get-items`).then(response => {

           this.$store.commit('setAllItems', response.data.data)
        });
    },
    methods: {
        ...mapActions([
            'loadWarehouses',
            'loadAllItems'
        ]),
        handleChange(file) {
            const self = this;
            const reader = new FileReader();
            reader.onload = e => self.parseXml(e.target.result);
            reader.readAsText(file.raw);
        },
        MensajeError(campo) {
            this.$message.error(`${campo} No se ha encontrado en el xml, no se puede continuar`);
            console.error(`${campo} no se encuentra en el xml`)
        },
        RetornoIndexIndefinido(array, index) {
            if (!(array[index] !== undefined && array[index]["_text"] !== undefined)) {
                this.MensajeError(index)
                return false;
            }
            return true;
        },
        async parseXml(source) {
            this.loading_submit = true;
            let convert = require("xml-js");
            this.formXmlJson = convert.xml2js(source, {compact: true, spaces: 4});
            this.has_file = false;
            await this.setdataForm();
            this.loading_submit = false;
        },
        async setdataForm() {
            let Invoice = this.formXmlJson.Invoice;
            let evalu = '';
            let ID = [];
            if (Invoice === undefined) {
                this.$message.error('No se encuentra datos de XML');
                console.error('No se encuentra datos de XML')
                return false;
            }

            evalu = 'cbc:DueDate';
            if (!this.RetornoIndexIndefinido(Invoice, evalu)) return false;
            this.form.date_of_due = Invoice["cbc:DueDate"]["_text"];
            evalu = 'cbc:IssueDate';
            if (!this.RetornoIndexIndefinido(Invoice, evalu)) return false;
            this.form.date_of_issue = Invoice[evalu]["_text"];
            evalu = 'cbc:IssueTime';
            if (!this.RetornoIndexIndefinido(Invoice, evalu)) return false;
            this.form.time_of_issue = Invoice[evalu]["_text"];
            evalu = 'cbc:ID';
            if (!this.RetornoIndexIndefinido(Invoice, evalu)) return false;
            ID = Invoice[evalu]["_text"].split("-");
            this.form.series = ID[0];
            this.form.number = ID[1];

            evalu = '["cac:AccountingCustomerParty"]["cac:Party"]["cac:PartyIdentification"]["cbc:ID"]["_text"]';
            if (
                Invoice["cac:AccountingCustomerParty"] !== undefined &&
                Invoice["cac:AccountingCustomerParty"]["cac:Party"] !== undefined &&
                Invoice["cac:AccountingCustomerParty"]["cac:Party"]["cac:PartyIdentification"] !== undefined &&
                Invoice["cac:AccountingCustomerParty"]["cac:Party"]["cac:PartyIdentification"]["cbc:ID"] !== undefined &&
                Invoice["cac:AccountingCustomerParty"]["cac:Party"]["cac:PartyIdentification"]["cbc:ID"]["_text"] !== undefined
            ) {
                this.form.supplier_ruc =
                    Invoice["cac:AccountingCustomerParty"]
                        ["cac:Party"]
                        ["cac:PartyIdentification"]
                        ["cbc:ID"]
                        ["_text"];
            } else {
                this.MensajeError(evalu)
                return false;
            }

            evalu = 'cac:InvoiceLine';
            if (Invoice["cac:InvoiceLine"] !== undefined) {
                await this.setFormItems(Invoice["cac:InvoiceLine"]);
            } else {
                this.MensajeError(evalu)
                return false;
            }


            evalu = 'cac:InvoiceLine';
            if (
                Invoice["cac:LegalMonetaryTotal"] !== undefined
            ) {
                evalu = '["cac:LegalMonetaryTotal"]["cbc:PayableAmount"]["_text"]';
                if (
                    Invoice["cac:LegalMonetaryTotal"] !== undefined &&
                    Invoice["cac:LegalMonetaryTotal"]["cbc:PayableAmount"] !== undefined &&
                    Invoice["cac:LegalMonetaryTotal"]["cbc:PayableAmount"]["_text"] !== undefined
                ) {
                    this.form.total =
                        Invoice
                            ["cac:LegalMonetaryTotal"]
                            ["cbc:PayableAmount"]
                            ["_text"];
                } else {
                    this.MensajeError(evalu)
                    return false;
                }
                /****/
                /****/
                evalu = '["cac:LegalMonetaryTotal"]["cbc:LineExtensionAmount"]["_text"]';
                if (
                    Invoice["cac:LegalMonetaryTotal"] !== undefined &&
                    Invoice["cac:LegalMonetaryTotal"]["cbc:LineExtensionAmount"] !== undefined &&
                    Invoice["cac:LegalMonetaryTotal"]["cbc:LineExtensionAmount"]["_text"] !== undefined
                ) {
                    this.form.total_taxed = Invoice["cac:LegalMonetaryTotal"]["cbc:LineExtensionAmount"]["_text"]

                } else {
                    this.MensajeError(evalu)
                    return false;
                }
                /****/

            } else {
                this.MensajeError(evalu)
                return false;
            }


            evalu = '["cac:TaxTotal"]["cbc:TaxAmount"]["_text"]';
            if (
                Invoice["cac:TaxTotal"] !== undefined &&
                Invoice["cac:TaxTotal"]["cbc:TaxAmount"] !== undefined &&
                Invoice["cac:TaxTotal"]["cbc:TaxAmount"]["_text"] !== undefined

            ) {
                this.form.total_taxes = Invoice
                    ["cac:TaxTotal"]
                    ["cbc:TaxAmount"]
                    ["_text"];
            } else {
                this.MensajeError(evalu)
                return false;
            }

            this.form.total_value = this.form.total_taxed;
            this.has_file = true;

        },
        findItem( search){
            if (search === '') return undefined;
            let item = this.all_items.find(obj => obj.item_code == search || obj.model == search||obj.internal_id == search)

            console.error(item)
            console.error(this.all_items)
            if (item !== undefined) return item

            return item
        },
        async setFormItems(items) {
            const self = this;
            console.error(items)
            if (Array.isArray(items)) {
                items.forEach(element => {
                    let code = '';
                    let unit_price = 0
                    let affectation_igv_code = '';


                    if(
                        element["cac:Item"] !== undefined &&
                        element["cac:Item"]["cac:CommodityClassification"] !== undefined &&
                        element["cac:Item"]["cac:CommodityClassification"]["cbc:ItemClassificationCode"] !== undefined &&
                        element["cac:Item"]["cac:CommodityClassification"]["cbc:ItemClassificationCode"]["_text"] !== undefined
                    ){
                        code = element["cac:Item"]["cac:CommodityClassification"]["cbc:ItemClassificationCode"]["_text"]
                    }

                    if(code == null &&
                        items["cac:Item"] !== undefined &&
                        items["cac:Item"]["cac:SellersItemIdentification"] !== undefined &&
                        items["cac:Item"]["cac:SellersItemIdentification"]["cbc:ID"] !== undefined &&
                        items["cac:Item"]["cac:SellersItemIdentification"]["cbc:ID"]["_text"]  !== undefined
                    ){
                        code =  items["cac:Item"]["cac:SellersItemIdentification"]["cbc:ID"]["_text"]
                    }
                    if(
                        element["cac:PricingReference"] !== undefined &&
                        element["cac:PricingReference"]["cac:AlternativeConditionPrice"] !== undefined &&
                        element["cac:PricingReference"]["cac:AlternativeConditionPrice"]["cbc:PriceAmount"] !== undefined &&
                        element["cac:PricingReference"]["cac:AlternativeConditionPrice"]["cbc:PriceAmount"]["_text"] !== undefined

                    ){
                        unit_price = element["cac:PricingReference"]["cac:AlternativeConditionPrice"]["cbc:PriceAmount"]["_text"];
                    }

                    if(
                        element["cac:TaxTotal"] !== undefined &&
                        element["cac:TaxTotal"]["cac:TaxSubtotal"] !== undefined &&
                        element["cac:TaxTotal"]["cac:TaxSubtotal"]["cbc:TaxExemptionReasonCode"] !== undefined &&
                        element["cac:TaxTotal"]["cac:TaxSubtotal"]["cbc:TaxExemptionReasonCode"]["_text"] !== undefined

                    ){
                        affectation_igv_code = element["cac:TaxTotal"]["cac:TaxSubtotal"]["cbc:TaxExemptionReasonCode"]["_text"]
                    }

                    // let item =  _.find(this.all_items, {item_code: code});
                    let item = this.findItem(code)
                    if(item !== undefined) {
                        let formItem = self.initFormItem();
                        formItem.item = item
                        formItem.unit_price = formItem.item.purchase_unit_price;
                        formItem.affectation_igv_type_id = formItem.item.purchase_affectation_igv_type_id;
                        formItem.item_unit_types = formItem.item.item_unit_types;
                        formItem.item.unit_price = unit_price;
                        formItem.item.presentation = {};
                        formItem.affectation_igv_type = affectation_igv_code;
                        let row = calculateRowItem(formItem, "PEN", 3.393);
                        row.warehouse_id = 1;
                        row.warehouse_description = "Almacén Oficina Principal";
                        self.form.items.push(row);
                    }
                    console.error(item)
                });
            } else {
                let code = null;
                let unit_price = 0
                let affectation_igv_code = '';
                if(
                    items["cac:Item"] !== undefined &&
                    items["cac:Item"]["cac:CommodityClassification"] !== undefined &&
                    items["cac:Item"]["cac:CommodityClassification"]["cbc:ItemClassificationCode"] !== undefined &&
                    items["cac:Item"]["cac:CommodityClassification"]["cbc:ItemClassificationCode"]["_text"] !== undefined
                ){
                    code = items["cac:Item"]["cac:CommodityClassification"]["cbc:ItemClassificationCode"]["_text"]
                }

                if(code == null &&
                    items["cac:Item"] !== undefined &&
                    items["cac:Item"]["cac:SellersItemIdentification"] !== undefined &&
                    items["cac:Item"]["cac:SellersItemIdentification"]["cbc:ID"] !== undefined &&
                    items["cac:Item"]["cac:SellersItemIdentification"]["cbc:ID"]["_text"]  !== undefined
                ){
                    code =  items["cac:Item"]["cac:SellersItemIdentification"]["cbc:ID"]["_text"]
                }
                if(
                    items["cac:PricingReference"] !== undefined &&
                    items["cac:PricingReference"]["cac:AlternativeConditionPrice"] !== undefined &&
                    items["cac:PricingReference"]["cac:AlternativeConditionPrice"]["cbc:PriceAmount"] !== undefined &&
                    items["cac:PricingReference"]["cac:AlternativeConditionPrice"]["cbc:PriceAmount"]["_text"] !== undefined

                ){
                    unit_price = items["cac:PricingReference"]["cac:AlternativeConditionPrice"]["cbc:PriceAmount"]["_text"];
                }

                if(
                    items["cac:TaxTotal"] !== undefined &&
                    items["cac:TaxTotal"]["cac:TaxSubtotal"] !== undefined &&
                    items["cac:TaxTotal"]["cac:TaxSubtotal"]["cbc:TaxExemptionReasonCode"] !== undefined &&
                    items["cac:TaxTotal"]["cac:TaxSubtotal"]["cbc:TaxExemptionReasonCode"]["_text"] !== undefined

                ){
                    affectation_igv_code = items["cac:TaxTotal"]["cac:TaxSubtotal"]["cbc:TaxExemptionReasonCode"]["_text"]
                }



                //let item =  _.find(this.all_items, {item_code: code});
                let item = this.findItem(code)
                if(item !== undefined) {
                        let formItem = self.initFormItem();
                        formItem.item = item;
                        formItem.unit_price = formItem.item.purchase_unit_price;
                        formItem.affectation_igv_type_id = formItem.item.purchase_affectation_igv_type_id;
                        formItem.item_unit_types = formItem.item.item_unit_types;
                        formItem.item.unit_price = unit_price;
                        formItem.item.presentation = {};
                        formItem.affectation_igv_type = affectation_igv_code;
                        let row = calculateRowItem(formItem, "PEN", 3.393);
                        row.warehouse_id = 1;
                        row.warehouse_description = "Almacén Oficina Principal";
                        self.form.items.push(row);
                    }
                console.error(item)
            }
        },

        initFormItem() {
            return {
                item_id: null,
                warehouse_id: 1,
                warehouse_description: null,
                item: {},
                affectation_igv_type_id: null,
                affectation_igv_type: {},
                has_isc: false,
                system_isc_type_id: null,
                percentage_isc: 0,
                suggested_price: 0,
                quantity: 1,
                unit_price: 0,
                charges: [],
                discounts: [],
                attributes: [],
                item_unit_types: []
            };
        },

        initForm() {
            this.errors = {};
            this.form = {
                establishment_id: 1,
                document_type_id: "01",
                series: null,
                number: null,
                date_of_issue: null,
                time_of_issue: null,
                supplier_id: null,
                payment_method_type_id: "01",
                currency_type_id: "PEN",
                purchase_order: null,
                exchange_rate_sale: 3.393,
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
                perception_date: null,
                perception_number: null,
                total_perception: 0,
                date_of_due: null,
                items: [],
                charges: [],
                discounts: [],
                attributes: [],
                guides: []
            };

            //this.initInputPerson();
        },
        create() {
            this.titleDialog = "Importar Factura Compra";
        },
        async submit() {
            this.loading_submit = true;
            await this.$http
                .post(`/${this.resource}/import`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                        this.$refs.upload.clearFiles();
                        this.close();
                    } else {
                        this.$message({message: response.data.message, type: "error"});
                    }
                })
                .catch(error => {
                    this.$message.error(error.response.message);
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
        close() {
            this.$emit("update:showDialog", false);
            this.initForm();
        },
        successUpload(response, file, fileList) {
            if (response.success) {
                //this.$message.success(response.message)
                //this.$eventHub.$emit('reloadData')
                //this.$refs.upload.clearFiles()
                //this.close()
            } else {
                this.$message({message: response.message, type: "error"});
            }
        },
        errorUpload(response) {
            console.log(response);
        },
        xmlToJson(xml) {
            // Create the return object
            var obj = {};

            if (xml.nodeType == 1) {
                // element
                // do attributes
                if (xml.attributes.length > 0) {
                    obj["@attributes"] = {};
                    for (var j = 0; j < xml.attributes.length; j++) {
                        var attribute = xml.attributes.item(j);
                        obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
                    }
                }
            } else if (xml.nodeType == 3) {
                // text
                obj = xml.nodeValue;
            }

            // do children
            // If all text nodes inside, get concatenated text from them.
            var textNodes = [].slice.call(xml.childNodes).filter(function (node) {
                return node.nodeType === 3;
            });
            if (xml.hasChildNodes() && xml.childNodes.length === textNodes.length) {
                obj = [].slice.call(xml.childNodes).reduce(function (text, node) {
                    return text + node.nodeValue;
                }, "");
            } else if (xml.hasChildNodes()) {
                for (var i = 0; i < xml.childNodes.length; i++) {
                    var item = xml.childNodes.item(i);
                    var nodeName = item.nodeName;
                    if (typeof obj[nodeName] == "undefined") {
                        obj[nodeName] = this.xmlToJson(item);
                    } else {
                        if (typeof obj[nodeName].push == "undefined") {
                            var old = obj[nodeName];
                            obj[nodeName] = [];
                            obj[nodeName].push(old);
                        }
                        obj[nodeName].push(this.xmlToJson(item));
                    }
                }
            }
            return obj;
        },
        demo() {
            parseXMLToJSON();
            return false;
        }
    },
    computed: {
        ...mapState([
            'config',
            'userType',
            'all_items',
        ]),
    },
};
</script>
