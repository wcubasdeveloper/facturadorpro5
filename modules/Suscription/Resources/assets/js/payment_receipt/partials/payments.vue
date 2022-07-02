<template>
    <el-dialog :close-on-click-modal="false"
               :close-on-press-escape="false"
               :title="title"
               :visible="showDialog"
               width="65%"
               @close="close"
               @open="getData">
        <div class="form-body">
            <div class="row">
                <div v-if="records.length > 0"
                     class="col-md-12">
                    <div class="table-responsive table-sm">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha de pago</th>
                                <th>Método de pago</th>
                                <th>Destino</th>
                                <th>Referencia</th>
                                <th>Archivo</th>
                                <th class="text-right">Monto</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in records"
                                :key="index">
                                <template v-if="row.id">
                                    <td>PAGO-{{ row.id }}</td>
                                    <td>{{ row.date_of_payment }}</td>
                                    <td>{{ row.payment_method_type_description }}</td>
                                    <td>{{ row.destination_description }}</td>
                                    <td>{{ row.reference }}</td>
                                    <td class="text-center">
                                        <button v-if="row.filename"
                                                class="btn waves-effect waves-light btn-xs btn-primary"
                                                type="button"
                                                @click.prevent="clickDownloadFile(row.filename)">
                                            <i class="fas fa-file-download"></i>
                                        </button>
                                    </td>
                                    <td class="text-right">{{ row.payment }}</td>
                                    <td class="series-table-actions text-right">
                                        <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                type="button"
                                                @click.prevent="clickDelete(row.id)"><i class="fas fa-trash"></i>
                                        </button>
                                        <!--<el-button type="danger" icon="el-icon-delete" plain @click.prevent="clickDelete(row.id)"></el-button>-->
                                    </td>
                                </template>
                                <template v-else>
                                    <td></td>
                                    <td>
                                        <div :class="{'has-danger': row.errors.date_of_payment}"
                                             class="form-group mb-0">
                                            <el-date-picker v-model="row.date_of_payment"
                                                            :clearable="false"
                                                            format="dd/MM/yyyy"
                                                            type="date"
                                                            value-format="yyyy-MM-dd"></el-date-picker>
                                            <small v-if="row.errors.date_of_payment"
                                                   class="form-control-feedback"
                                                   v-text="row.errors.date_of_payment[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div :class="{'has-danger': row.errors.payment_method_type_id}"
                                             class="form-group mb-0">
                                            <el-select v-model="row.payment_method_type_id">
                                                <el-option v-for="option in payment_method_types"
                                                           :key="option.id"
                                                           :label="option.description"
                                                           :value="option.id"></el-option>
                                            </el-select>
                                            <small v-if="row.errors.payment_method_type_id"
                                                   class="form-control-feedback"
                                                   v-text="row.errors.payment_method_type_id[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div :class="{'has-danger': row.errors.payment_destination_id}"
                                             class="form-group mb-0">
                                            <el-select v-model="row.payment_destination_id"
                                                       :disabled="row.payment_destination_disabled"
                                                       filterable>
                                                <el-option v-for="option in payment_destinations"
                                                           :key="option.id"
                                                           :label="option.description"
                                                           :value="option.id"></el-option>
                                            </el-select>
                                            <small v-if="row.errors.payment_destination_id"
                                                   class="form-control-feedback"
                                                   v-text="row.errors.payment_destination_id[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div :class="{'has-danger': row.errors.reference}"
                                             class="form-group mb-0">
                                            <el-input v-model="row.reference"></el-input>
                                            <small v-if="row.errors.reference"
                                                   class="form-control-feedback"
                                                   v-text="row.errors.reference[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0">

                                            <el-upload
                                                :action="`/finances/payment-file/upload`"
                                                :data="{'index': index}"
                                                :file-list="fileList"
                                                :headers="headers"
                                                :limit="1"
                                                :multiple="false"
                                                :on-remove="handleRemove"
                                                :on-success="onSuccess"
                                                :show-file-list="true"
                                            >
                                                <el-button slot="trigger"
                                                           type="primary"><i class="fas fa-file-upload"></i></el-button>
                                            </el-upload>
                                        </div>
                                    </td>
                                    <td>
                                        <div :class="{'has-danger': row.errors.payment}"
                                             class="form-group mb-0">
                                            <el-input v-model="row.payment"></el-input>
                                            <small v-if="row.errors.payment"
                                                   class="form-control-feedback"
                                                   v-text="row.errors.payment[0]"></small>
                                        </div>
                                    </td>
                                    <td class="series-table-actions text-right">
                                        <button class="btn waves-effect waves-light btn-xs btn-info"
                                                type="button"
                                                @click.prevent="clickSubmit(index)">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                type="button"
                                                @click.prevent="clickCancel(index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </template>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td class="text-right"
                                    colspan="6">TOTAL PAGADO
                                </td>
                                <td class="text-right">{{ document.total_paid }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right"
                                    colspan="6">TOTAL A PAGAR
                                </td>
                                <td class="text-right">{{ document.total }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right"
                                    colspan="6">PENDIENTE DE PAGO
                                </td>
                                <td class="text-right">{{ document.total_difference }}</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div v-if="showAddButton && (document.total_difference > 0)"
                     class="col-md-12 text-center pt-2">
                    <el-button icon="el-icon-plus"
                               type="primary"
                               @click="clickAddRow">Nuevo
                    </el-button>
                </div>
            </div>
        </div>
    </el-dialog>

</template>

<style>
.el-upload-list__item-name [class^="el-icon"] {
    display: none;
}

.el-upload-list__item-name {
    margin-right: 25px;
}

.el-upload-list__item {
    font-size: 10px;
}
</style>

<script>

// import {deletable} from '../../../../mixins/deletable'

import {deletable} from "../../../../../../../resources/js/mixins/deletable";

export default {
    props: ['showDialog', 'documentId'],
    mixins: [
        deletable
    ],
    data() {
        return {
            title: null,
            resource: 'sale_note_payments',
            records: [],
            payment_destinations: [],
            payment_method_types: [],
            headers: headers_token,
            index_file: null,
            fileList: [],
            showAddButton: true,
            document: {}
        }
    },
    async created() {
        await this.initForm();
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.payment_destinations = response.data.payment_destinations
                this.payment_method_types = response.data.payment_method_types;
                //this.initDocumentTypes()
            })
    },
    methods: {
        clickDownloadFile(filename) {
            window.open(
                `/finances/payment-file/download-file/${filename}/sale_notes`,
                "_blank"
            );
        },
        onSuccess(response, file, fileList) {

            // console.log(response, file, fileList)
            this.fileList = fileList

            if (response.success) {

                this.index_file = response.data.index
                this.records[this.index_file].filename = response.data.filename
                this.records[this.index_file].temp_path = response.data.temp_path

            } else {
                this.cleanFileList()
                this.$message.error(response.message)
            }

            // console.log(this.records)

        },
        cleanFileList() {
            this.fileList = []
        },
        handleRemove(file, fileList) {

            this.records[this.index_file].filename = null
            this.records[this.index_file].temp_path = null
            this.fileList = []
            this.index_file = null

        },
        initForm() {
            this.records = [];
            this.fileList = [];
            this.showAddButton = true;
        },
        async getData() {
            this.initForm();
            await this.$http.get(`/${this.resource}/document/${this.documentId}`)
                .then(response => {
                    this.document = response.data;
                    this.title = 'Pagos del comprobante: ' + this.document.number_full;
                });
            await this.$http.get(`/${this.resource}/records/${this.documentId}`)
                .then(response => {
                    this.records = response.data.data
                });
            this.$eventHub.$emit('reloadDataUnpaid')

        },
        clickAddRow() {
            this.records.push({
                id: null,
                date_of_payment: moment().format('YYYY-MM-DD'),
                payment_method_type_id: null,
                payment_destination_id: null,
                reference: null,
                filename: null,
                temp_path: null,
                payment: 0,
                errors: {},
                loading: false
            });
            this.showAddButton = false;
        },
        clickCancel(index) {
            this.records.splice(index, 1);
            this.showAddButton = true;
            this.fileList = []
        },
        clickSubmit(index) {
            if (this.records[index].payment > parseFloat(this.document.total_difference)) {
                this.$message.error('El monto ingresado supera al monto pendiente de pago, verifique.');
                return;
            }

            let paid = false
            if (this.records[index].payment == parseFloat(this.document.total_difference)) {
                paid = true
            }


            let form = {
                id: this.records[index].id,
                sale_note_id: this.documentId,
                date_of_payment: this.records[index].date_of_payment,
                payment_method_type_id: this.records[index].payment_method_type_id,
                payment_destination_id: this.records[index].payment_destination_id,
                reference: this.records[index].reference,
                filename: this.records[index].filename,
                temp_path: this.records[index].temp_path,
                payment: this.records[index].payment,
                paid: paid
            };
            this.$http.post(`/${this.resource}`, form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.getData();
                        // this.initDocumentTypes()
                        this.$eventHub.$emit('reloadData')
                        this.showAddButton = true;
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.records[index].errors = error.response.data;
                    } else {
                        console.log(error);
                    }
                })
        },
        // filterDocumentType(row){
        //
        //     if(row.contingency){
        //         this.document_types = _.filter(this.all_document_types, item => (item.id == '01' || item.id =='03'))
        //         row.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
        //     }else{
        //         row.document_type_id = null
        //         this.document_types = this.all_document_types
        //     }
        // },
        // initDocumentTypes(){
        //     this.document_types = (this.all_document_types.length > 0) ? this.all_document_types : []
        // },
        close() {
            this.$emit('update:showDialog', false);
            // this.initDocumentTypes()
            // this.initForm()
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() => {
                    this.getData()
                    this.$eventHub.$emit('reloadData')
                }
                // this.initDocumentTypes()
            )
        }
    }
}
</script>
