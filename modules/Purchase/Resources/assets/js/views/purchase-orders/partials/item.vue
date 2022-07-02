<template>
    <el-dialog :close-on-click-modal="false"
               :title="titleDialog"
               :visible="showDialog"
               top="7vh"
               @close="close"
               @open="create">
        <form autocomplete="off"
              @submit.prevent="clickAddItem">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7 col-lg-7 col-xl-7 col-sm-7">
                        <div id="custom-select"
                             :class="{'has-danger': errors.item_id}"
                             class="form-group">
                            <label class="control-label">
                                Producto/Servicio
                                <a href="#"
                                   @click.prevent="showDialogNewItem = true">[+ Nuevo]</a>
                            </label>
                            <template id="select-append">
                                <el-input id="custom-input">
                                    <el-select
                                        id="select-width"
                                        ref="selectSearchNormal"
                                        slot="prepend"
                                        v-model="form.item_id"

                                        :loading="loading_search"
                                        :remote-method="searchRemoteItems"
                                        filterable
                                        placeholder="Buscar"
                                        popper-class="el-select-items"
                                        remote
                                        @change="changeItem"
                                        @focus="focusSelectItem">

                                        <el-tooltip
                                            v-for="option in items"
                                            :key="option.id"
                                            placement="left">
                                            <div
                                                slot="content"
                                                v-html="ItemSlotTooltipView(option)"
                                            ></div>
                                            <el-option
                                                :label="ItemOptionDescriptionView(option)"
                                                :value="option.id"
                                            ></el-option>

                                        </el-tooltip>
                                    </el-select>
                                    <el-tooltip slot="append"
                                                class="item"
                                                content="Ver Stock del Producto"
                                                effect="dark"
                                                placement="bottom">
                                        <el-button @click.prevent="clickWarehouseDetail()"><i class="fa fa-search"></i>
                                        </el-button>
                                    </el-tooltip>
                                </el-input>
                            </template>
                            <small v-if="errors.item_id"
                                   class="form-control-feedback"
                                   v-text="errors.item_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div :class="{'has-danger': errors.affectation_igv_type_id}"
                             class="form-group">
                            <label class="control-label">Afectación Igv</label>
                            <el-select v-model="form.affectation_igv_type_id"
                                       :disabled="!change_affectation_igv_type_id"
                                       filterable>
                                <el-option v-for="option in affectation_igv_types"
                                           :key="option.id"
                                           :label="option.description"
                                           :value="option.id"></el-option>
                            </el-select>
                            <el-checkbox v-model="change_affectation_igv_type_id">Editar</el-checkbox>
                            <small v-if="errors.affectation_igv_type_id"
                                   class="form-control-feedback"
                                   v-text="errors.affectation_igv_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div :class="{'has-danger': errors.quantity}"
                             class="form-group">
                            <label class="control-label">Cantidad</label>
                            <el-input-number v-model="form.quantity"
                                             :disabled="form.item.calculate_quantity"
                                             :min="0.01"></el-input-number>
                            <small v-if="errors.quantity"
                                   class="form-control-feedback"
                                   v-text="errors.quantity[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div :class="{'has-danger': errors.unit_price}"
                             class="form-group">
                            <label class="control-label">Precio Unitario</label>
                            <el-input v-model="form.unit_price"
                                      @input="calculateQuantity">
                                <template v-if="form.item.currency_type_symbol"
                                          slot="prepend">{{ form.item.currency_type_symbol }}
                                </template>
                            </el-input>
                            <small v-if="errors.unit_price"
                                   class="form-control-feedback"
                                   v-text="errors.unit_price[0]"></small>
                        </div>
                    </div>

                    <!-- <div class="col-md-3" v-show="form.item_id">  <br>
                        <div class="form-group" :class="{'has-danger': errors.lot_code}" v-if="form.item.series_enabled">
                            <label class="control-label">
                                Ingrese series
                            </label>

                            <el-button style="margin-top:2%;" type="primary" icon="el-icon-edit-outline"  @click.prevent="clickLotcode"></el-button>

                            <small class="form-control-feedback" v-if="errors.lot_code" v-text="errors.lot_code[0]"></small>
                        </div>
                    </div> -->

                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.warehouse_id}"
                             class="form-group">
                            <label class="control-label">Almacén de destino</label>
                            <el-select v-model="form.warehouse_id"
                                       filterable>
                                <el-option v-for="option in warehouses"
                                           :key="option.id"
                                           :label="option.description"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.warehouse_id"
                                   class="form-control-feedback"
                                   v-text="errors.warehouse_id[0]"></small>
                        </div>
                    </div>
                    <div v-if="form.item_unit_types.length > 0"
                         class="col-md-12">
                        <div class="table-responsive"
                             style="margin:3px">
                            <h3>Lista de Precios</h3>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Unidad</th>
                                    <th class="text-center">Descripción</th>
                                    <th class="text-center">Factor</th>

                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in item_unit_types"
                                    :key="index">
                                    <td class="text-center">{{ row.unit_type_id }}</td>
                                    <td class="text-center">{{ row.description }}</td>
                                    <td class="text-center">{{ row.quantity_unit }}</td>

                                    <td class="series-table-actions text-right">
                                        <button class="btn waves-effect waves-light btn-xs btn-success"
                                                type="button"
                                                @click.prevent="selectedPrice(row)">
                                            <i class="el-icon-check"></i>
                                        </button>
                                    </td>


                                </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                    <div class="col-md-12 mt-3">
                        <section id="card-section"
                                 class="card mb-2 card-transparent card-collapsed">
                            <header id="card-click"
                                    class="card-header hoverable bg-light border-top rounded-0 py-1"
                                    data-card-toggle
                                    style="cursor: pointer;">
                                <div class="card-actions"
                                     style="margin-top: -12px;">
                                    <a class="card-action card-action-toggle text-info"
                                       data-card-toggle=""
                                       href="#"></a>
                                </div>

                                <p class="pl-1">Información adicional atributos UBL 2.1</p>
                            </header>
                            <div class="card-body px-0 pt-2"
                                 style="display: none;">
                                <div v-if="discount_types.length > 0"
                                     class="col-md-12 px-0">
                                    <label class="control-label">
                                        Descuentos
                                        <a href="#"
                                           @click.prevent="clickAddDiscount">[+ Agregar]</a>
                                    </label>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Descripción</th>
                                            <th>Porcentaje</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.discounts">
                                            <td>
                                                <el-select v-model="row.discount_type_id"
                                                           @change="changeDiscountType(index)">
                                                    <el-option v-for="option in discount_types"
                                                               :key="option.id"
                                                               :label="option.description"
                                                               :value="option.id"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-input v-model="row.description"></el-input>
                                            </td>
                                            <td>
                                                <el-input v-model="row.percentage"></el-input>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger"
                                                        type="button"
                                                        @click.prevent="clickRemoveDiscount(index)">x
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-if="charge_types.length > 0"
                                     class="col-md-12 px-0">
                                    <label class="control-label">
                                        Cargos
                                        <a href="#"
                                           @click.prevent="clickAddCharge">[+ Agregar]</a>
                                    </label>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Descripción</th>
                                            <th>Porcentaje</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.charges">
                                            <td>
                                                <el-select v-model="row.charge_type_id"
                                                           @change="changeChargeType(index)">
                                                    <el-option v-for="option in charge_types"
                                                               :key="option.id"
                                                               :label="option.description"
                                                               :value="option.id"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-input v-model="row.description"></el-input>
                                            </td>
                                            <td>
                                                <el-input v-model="row.percentage"></el-input>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger"
                                                        type="button"
                                                        @click.prevent="clickRemoveCharge(index)">x
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-if="attribute_types.length > 0"
                                     class="col-md-12 px-0">
                                    <label class="control-label">
                                        Atributos
                                        <a href="#"
                                           @click.prevent="clickAddAttribute">[+ Agregar]</a>
                                    </label>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Descripción</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.attributes">
                                            <td>
                                                <el-select v-model="row.attribute_type_id"
                                                           filterable
                                                           @change="changeAttributeType(index)">
                                                    <el-option v-for="option in attribute_types"
                                                               :key="option.id"
                                                               :label="option.description"
                                                               :value="option.id"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-input v-model="row.value"></el-input>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger"
                                                        type="button"
                                                        @click.prevent="clickRemoveAttribute(index)">x
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-show="form.item.calculate_quantity"
                                     class="col-md-3 col-sm-6">
                                    <div :class="{'has-danger': errors.total_item}"
                                         class="form-group">
                                        <label class="control-label">Total venta producto</label>
                                        <el-input ref="total_item"
                                                  v-model="total_item"
                                                  :min="0.01"
                                                  @input="calculateQuantity">
                                            <template v-if="form.item.currency_type_symbol"
                                                      slot="prepend">{{ form.item.currency_type_symbol }}
                                            </template>
                                        </el-input>
                                        <small v-if="errors.total_item"
                                               class="form-control-feedback"
                                               v-text="errors.total_item[0]"></small>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button v-if="form.item_id"
                           native-type="submit"
                           type="primary">Agregar
                </el-button>
            </div>
        </form>
        <item-form :external="true"
                   :showDialog.sync="showDialogNewItem"></item-form>
        <lots-form
            :lots="lots"
            :showDialog.sync="showDialogLots"
            :stock="form.quantity"
            @addRowLot="addRowLot"
        >
        </lots-form>
    </el-dialog>
</template>
<style>
.el-select-dropdown {
    margin-right: 5% !important;
    max-width: 80% !important;
}
</style>
<script>

import itemForm from '../../../../../../../../resources/js/views/tenant/items/form.vue'
import {calculateRowItem} from '../../../../../../../../resources/js/helpers/functions'
import LotsForm from './lots.vue'

import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import VueCkeditor from "vue-ckeditor5";
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import {ItemOptionDescription, ItemSlotTooltip} from "../../../../../../../../resources/js/helpers/modal_item";
import WarehousesDetail
    from "../../../../../../../Sale/Resources/assets/js/views/sale_opportunities/partials/warehouses";

export default {
    props: [
        'showDialog',
        'currencyTypeIdActive',
        'exchangeRateSale'
    ],
    components: {itemForm, LotsForm, 'vue-ckeditor': VueCkeditor.component},

    data() {
        return {
            can_add_new_product: false,
            loading_search: false,
            titleAction: '',
            is_client: false,
            titleDialog: 'Agregar Producto o Servicio',
            resource: 'purchase-orders',
            showDialogNewItem: false,
            has_list_prices: false,
            errors: {},
            form: {},
            all_items: [],
            items: [],
            warehouses: [],
            operation_types: [],
            all_affectation_igv_types: [],
            aux_items: [],
            affectation_igv_types: [],
            system_isc_types: [],
            discount_types: [],
            charge_types: [],
            attribute_types: [],
            use_price: 1,
            change_affectation_igv_type_id: false,
            activePanel: 0,
            total_item: 0,
            item_unit_types: [],
            item_unit_type: {},
            showWarehousesDetail: false,
            warehousesDetail: [],
            showListStock: false,
            search_item_by_barcode: false,
            isUpdateWarehouseId: null,
            showDialogLots: false,
            showDialogSelectLots: false,
            lots: [],
            editors: {
                classic: ClassicEditor
            },
        }
    },
    created() {
        this.loadConfiguration()
        // this.$store.commit('setConfiguration', this.configuration)
        this.initForm()
        this.$http.get(`/${this.resource}/item/tables`).then(response => {
            this.items = response.data.items
            this.affectation_igv_types = response.data.affectation_igv_types
            this.system_isc_types = response.data.system_isc_types
            this.discount_types = response.data.discount_types
            this.charge_types = response.data.charge_types
            this.attribute_types = response.data.attribute_types
            this.warehouses = response.data.warehouses
            // this.filterItems()
        })

        this.$eventHub.$on('reloadDataItems', (item_id) => {
            this.reloadDataItems(item_id)
        })
    },
    computed: {

        ...mapState([
            'config',
        ]),
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        hasAttributes() {
            if (
                this.form.item !== undefined &&
                this.form.item.attributes !== undefined &&
                this.form.item.attributes !== null &&
                this.form.item.attributes.length > 0
            ) {
                return true
            }

            return false;
        },
        ItemSlotTooltipView(item) {
            return ItemSlotTooltip(item);
        },
        ItemOptionDescriptionView(item) {
            return ItemOptionDescription(item)
        },
        async searchRemoteItems(input) {
            if (input.length > 2) {
                this.loading_search = true
                const params = {
                    'input': input,
                    'search_by_barcode': this.search_item_by_barcode ? 1 : 0
                }
                await this.$http.get(`/${this.resource}/search-items/`, {params})
                    .then(response => {
                        this.items = response.data.items
                        this.loading_search = false
                        this.enabledSearchItemsBarcode()
                        this.enabledSearchItemBySeries()
                        if (this.items.length == 0) {
                            this.filterItems()
                        }
                    })
            } else {
                await this.filterItems()
            }

        },
        filterItems() {
            this.items = this.items.filter(item => item.warehouses.length > 0)
        },
        enabledSearchItemsBarcode() {
            if (this.search_item_by_barcode) {
                this.$refs.selectBarcode.$data.selectedLabel = '';
                if (this.items.length == 1) {
                    this.form.item_id = this.items[0].id;
                    this.$refs.selectBarcode.blur();
                    this.changeItem();
                }
            }
        },
        async enabledSearchItemBySeries() {

            if (this.config.search_item_by_series && this.items.length == 1) {

                this.$notify({title: "Serie ubicada", message: "Producto añadido!", type: "success", duration: 1200});
                this.form.item_id = this.items[0].id;
                this.$refs.selectSearchNormal.$data.selectedLabel = '';

                await this.changeItem();

                this.lots = await this.form.item.lots.map((lot) => {
                    lot.has_sale = true
                })

                await this.clickAddItem()

                this.$refs.selectSearchNormal.$data.selectedLabel = '';
            }

            if (this.config.search_item_by_series && this.items.length == 0) {
                this.$notify({title: "Serie no ubicada", message: "", type: "warning", duration: 1200});
            }

        },

        filterMethod(query) {

            let item = _.find(this.items, {'internal_id': query});

            if (item) {
                this.form.item_id = item.id
                this.changeItem()
            }
        },
        initForm() {
            this.errors = {};

            this.form = {
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
                item_unit_types: [],
                purchase_has_igv: false,
                has_igv: null,
                item_unit_type_id: null,
                unit_type_id: null,
                is_set: false,
            };

            this.total_item = 0;
            this.item_unit_type = {};
            this.has_list_prices = false;
        },
        // initializeFields() {
        //     this.form.affectation_igv_type_id = this.affectation_igv_types[0].id
        // },
        create() {
            //     this.initializeFields()
        },
        clickAddDiscount() {
            this.form.discounts.push({
                discount_type_id: null,
                discount_type: null,
                description: null,
                percentage: 0,
                factor: 0,
                amount: 0,
                base: 0
            })
        },
        clickRemoveDiscount(index) {
            this.form.discounts.splice(index, 1)
        },
        changeDiscountType(index) {
            let discount_type_id = this.form.discounts[index].discount_type_id
            this.form.discounts[index].discount_type = _.find(this.discount_types, {id: discount_type_id})
        },
        clickAddCharge() {
            this.form.charges.push({
                charge_type_id: null,
                charge_type: null,
                description: null,
                percentage: 0,
                factor: 0,
                amount: 0,
                base: 0
            })
        },
        clickRemoveCharge(index) {
            this.form.charges.splice(index, 1)
        },
        changeChargeType(index) {
            let charge_type_id = this.form.charges[index].charge_type_id
            this.form.charges[index].charge_type = _.find(this.charge_types, {id: charge_type_id})
        },
        clickAddAttribute() {
            this.form.attributes.push({
                attribute_type_id: null,
                description: null,
                value: null,
                start_date: null,
                end_date: null,
                duration: null,
            })
        },
        clickRemoveAttribute(index) {
            this.form.attributes.splice(index, 1)
        },
        changeAttributeType(index) {
            let attribute_type_id = this.form.attributes[index].attribute_type_id
            let attribute_type = _.find(this.attribute_types, {id: attribute_type_id})
            this.form.attributes[index].description = attribute_type.description
        },
        close() {
            this.initForm()
            this.$emit('update:showDialog', false)
        },
        selectedPrice(row) {

            this.form.item_unit_type_id = row.id
            this.item_unit_type = row

            // this.form.unit_price = valor
            this.form.item.unit_type_id = row.unit_type_id
        },
        changeItem() {
            this.form.item = _.find(this.items, {'id': this.form.item_id})
            console.error(this.form.item)
            console.log(this.form.item.purchase_unit_price +' <<< ')
            this.form.unit_price = this.form.item.purchase_unit_price
            this.form.affectation_igv_type_id = this.form.item.purchase_affectation_igv_type_id

            this.form.item_unit_types = _.find(this.items, {'id': this.form.item_id}).item_unit_types
            this.form.purchase_has_igv = this.form.item.purchase_has_igv;

            this.lots = []
        },
        clickAddItem() {


            let affectation_igv_types_exonerated_unaffected = ['20', '21', '30', '31', '32', '33', '34', '35', '36', '37']

            let unit_price = this.form.unit_price

            if (!affectation_igv_types_exonerated_unaffected.includes(this.form.affectation_igv_type_id)) {

                unit_price = (this.form.purchase_has_igv) ? this.form.unit_price : this.form.unit_price * 1.18;

            }

            this.form.item.unit_price = unit_price
            this.form.item.presentation = this.item_unit_type;
            this.form.affectation_igv_type = _.find(this.affectation_igv_types, {'id': this.form.affectation_igv_type_id})
            this.row = calculateRowItem(this.form, this.currencyTypeIdActive, this.exchangeRateSale)
            this.row = this.changeWarehouse(this.row)

            this.initForm()
            // this.initializeFields()
            this.$emit('add', this.row)
            this.setFocusSelectItem()

        },
        focusSelectItem() {
            this.$refs.selectSearchNormal.$el.getElementsByTagName('input')[0].focus()
        },
        setFocusSelectItem() {

            this.$refs.selectSearchNormal.$el.getElementsByTagName('input')[0].focus()

        },
        changeWarehouse(row) {
            let warehouse = _.find(this.warehouses, {'id': this.form.warehouse_id})
            row.warehouse_id = warehouse.id
            row.warehouse_description = warehouse.description
            return row
        },

        calculateQuantity() {
            if (this.form.item.calculate_quantity) {
                this.form.quantity = _.round((this.total_item / this.form.unit_price), 4)
            }
        },
        reloadDataItems(item_id) {
            this.$http.get(`/${this.resource}/table/items`).then((response) => {
                this.items = response.data
                this.form.item_id = item_id
                this.changeItem()
                // this.filterItems()

            })
        },
        clickLotcode() {
            this.showDialogLots = true
        },
        addRowLot(val) {
            this.form.item.lots = val
        }
    }
}

</script>
