<template>
    <div>
        <el-dialog
            :visible="showDialog"
            :close-on-click-modal="false"
            :close-on-press-escape="false"
            :show-close="false"
            :title="titleDialog"
            width="50%"
            @open="create"
        >   
            <div class="row"> 
 
                <div class="col-lg-4">
                    <div :class="{'has-danger': errors.series_id}" class="form-group">
                        <label class="control-label">Serie</label>
                        <el-select v-model="document.series_id">
                            <el-option
                                v-for="option in series"
                                :key="option.id"
                                :label="option.number"
                                :value="option.id"
                            ></el-option>
                        </el-select>
                        <small
                            v-if="errors.series_id"
                            class="form-control-feedback"
                            v-text="errors.series_id[0]"
                        ></small>
                    </div>
                </div>
 
                <div class="col-md-12" v-if="document.items">
                    <div style="margin:3px" class="table-responsive">
                        <h5 class="separator-title">
                            Productos
                        </h5>
                        <table class="table mt-2">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Cantidad</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in document.items" :key="index">
                                    <td class="text-center">{{ index + 1 }}</td>
                                    <td class="text-center">{{row.item.description}}</td>
                                    <td class="text-center">{{row.quantity}}</td>
                                    <td class="series-table-actions text-right">
                                        
                                        <template v-if="row.item.lots_enabled">
                                            <button class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="openDialogLotsGroup(index, row)">
                                                <i class="el-icon-check"></i> Lotes
                                            </button>
                                        </template>

                                        <template v-if="row.item.series_enabled">
                                            <button class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="openDialogLots(index, row)">
                                                <i class="el-icon-check"></i> Series
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
  
            </div>

            <span slot="footer" class="dialog-footer">
                <el-button @click="clickClose">Cerrar</el-button>
                <el-button
                    :loading="loading_submit"
                    class="submit"
                    type="primary"
                    @click="submit"
                    >Generar</el-button> 
            </span>
        </el-dialog>

        <select-lots-form
            :documentItemId="null"
            :itemId="itemId"
            :lots="lots"
            :showDialog.sync="showDialogSelectLots"
            @addRowSelectLot="addRowSelectLot">
        </select-lots-form>
         
        <lots-group
            :lots_group="lots_group"
            :quantity="lots_group_quantity"
            :showDialog.sync="showDialogLotsGroup"
            @addRowLotGroup="addRowLotGroup">
        </lots-group>

    </div>
</template>

<script> 

import SelectLotsForm from '../../documents/partials/lots.vue'
import LotsGroup from '../../sale_notes/partials/lots_group.vue'

export default {
    components: {SelectLotsForm, LotsGroup},

    props: [
        'showDialog',
        'orderId',
        'dataSaleNote'
    ],
    computed:{
    },
    data() {
        return {
            titleDialog: 'Generar nota de venta',
            loading: false,
            resource: "sale-notes",
            errors: {},
            document: {},
            all_series: [],
            series: [],
            lots: [],
            loading_submit: false,
            showDialogSelectLots: false,
            documentNewId: null,
            itemId: null,
            showDialogLotsGroup:false,
            lots_group_quantity:0,
            lots_group_quantity:0,
            lots_group: [],
            current_index_item: -1
        }
    },
    async created() {
        await this.initDocument()
    },
    methods: {   
        addRowLotGroup(lots_selecteds){
            // console.log(lots_selecteds)
            this.document.items[this.current_index_item].IdLoteSelected = lots_selecteds
            this.current_index_item = -1
        },
        openDialogLotsGroup(index, row){
            this.current_index_item = index
            this.showDialogLotsGroup = true
            this.lots_group_quantity = row.quantity
            this.lots_group = this.document.items[index].item.lots_group
        },
        openDialogLots(index, row){
            this.showDialogSelectLots = true
            this.lots = this.document.items[index].item.lots
            this.itemId = row.item_id
        },
        addRowSelectLot(lots) {
            this.lots = lots
        },
        initDocument() {

            this.document = {
                document_type_id: '80',
                series_id: null,
                establishment_id: null,
                number: "#",
                date_of_issue: null,
                time_of_issue: null,
                customer_id: null,
                currency_type_id: null,
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
                total: 0,
                items: [],
                charges: [],
                discounts: [],
                attributes: [],
            }


        }, 
        resetDocument() {
            this.initDocument()
        },
        async submit() {

            let validate_items = await this.validateQuantitySeriesLots()
            if (!validate_items.success)
                return this.$message.error(validate_items.message)

            this.loading_submit = true
            this.document.prefix = "NV"
            this.document.order_id = this.orderId

            await this.$http
                .post(`/${this.resource}`, this.document)
                .then((response) => {
                    if (response.data.success) {

                        this.documentNewId = response.data.data.id
                        this.saveUpdateStatus()
                        this.$eventHub.$emit("reloadData")
                        this.$message.success('Transaccion finalizada correctamente')
                        this.clickClose()

                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch((error) => {
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
        saveUpdateStatus(){
            this.$http.post(`/statusOrder/update`, { record: { id: this.orderId, status_order_id: 2} })
        },
        async getTransformDataForOrder(){

            await this.$http
                .post(`/${this.resource}/transform-data-order`, this.dataSaleNote)
                .then((response) => {
                    // console.log(response)
                    this.assignDocument(response.data.data)
                })
        },
        assignDocument(data_transform) {

            // let record = this.dataSaleNote
            this.document = data_transform
            
        }, 
        async create() {
            await this.getTransformDataForOrder()
            await this.getTables()
        },
        async getTables(){

            await this.$http
                .get(`/${this.resource}/option/tables`)
                .then((response) => { 
                    this.all_series = response.data.series
                    this.filterSeries()
                })

        }, 
        filterSeries() {
            this.document.series_id = null
            this.series = _.filter(this.all_series, {document_type_id: this.document.document_type_id})
            this.document.series_id = this.series.length > 0 ? this.series[0].id : null
        },
        clickFinalize() {
            location.href = `/${this.resource}`
        },
        clickNewQuotation() {
            this.clickClose()
        },
        clickClose() {
            this.$emit("update:showDialog", false)
            this.resetDocument()
        },  
        async validateQuantitySeriesLots() {

            let error = 0
            let error_lots_group = 0

            await this.document.items.forEach((element) => {

                if (element.item.series_enabled) {

                    let select_lots = _.filter(element.item.lots, {has_sale: true}).length
                    if (select_lots != element.quantity) error ++
                }

                if (element.item.lots_enabled) {
                    if (!element.IdLoteSelected) error_lots_group++
                }

            })

            if(error > 0) 
            {
                return {
                    success: false,
                    message: 'Las cantidades y series seleccionadas deben ser iguales.',
                }
            }
                
            if(error_lots_group > 0) 
            {
                return {
                    success: false,
                    message: 'Las cantidades y lotes seleccionados deben ser iguales.',
                }
            }

            return {
                success: true
            }
        },

    },

}
</script>
