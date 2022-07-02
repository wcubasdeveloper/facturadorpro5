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
                                <a v-if="can_add_new_product"
                                   href="#"
                                   @click.prevent="showDialogNewItem = true">
                                    [+ Nuevo]
                                </a>
                            </label>

                            <template id="select-append">
                                <el-input id="custom-input">
                                    <el-select
                                        id="select-width"
                                        ref="selectSearchNormal"
                                        slot="prepend"
                                        v-model="form.item_id"
                                        :disabled="recordItem != null"
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
                                    <el-tooltip
                                        slot="append"
                                        :disabled="recordItem != null"
                                        class="item"
                                        content="Ver Stock del Producto"
                                        effect="dark"
                                        placement="bottom">
                                        <el-button
                                            @click.prevent="clickWarehouseDetail()">
                                            <i class="fa fa-search"></i>
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
                            <el-checkbox v-model="change_affectation_igv_type_id"
                                         :disabled="recordItem != null">
                                Editar
                            </el-checkbox>
                            <small v-if="errors.affectation_igv_type_id"
                                   class="form-control-feedback"
                                   v-text="errors.affectation_igv_type_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div :class="{'has-danger': errors.quantity}"
                             class="form-group">
                            <label class="control-label">Cantidad</label>
                            <el-input-number
                                ref="inputQuantity"
                                v-model="form.quantity"
                                :disabled="form.item.calculate_quantity"
                                :min="0.01"
                                @blur="validateQuantity"
                                @input.native="changeValidateQuantity"
                            >
                                <el-button slot="prepend"
                                           :disabled="form.quantity < 0.01 || form.item.calculate_quantity"
                                           icon="el-icon-minus"
                                           style="padding-right: 5px ;padding-left: 12px"
                                           @click="clickDecrease"></el-button>
                                <el-button slot="append"
                                           :disabled="form.item.calculate_quantity"
                                           icon="el-icon-plus"
                                           style="padding-right: 5px ;padding-left: 12px"
                                           @click="clickIncrease"></el-button>
                            </el-input-number>
                            <small v-if="errors.quantity"
                                   class="form-control-feedback"
                                   v-text="errors.quantity[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div :class="{'has-danger': errors.unit_price}"
                             class="form-group">
                            <label class="control-label">Precio Unitario</label>
                            <el-input v-model="form.unit_price"
                                      :readonly="!edit_unit_price"
                                      @input="calculateQuantity">
                                <template v-if="form.item.currency_type_symbol"
                                          slot="prepend">
                                    {{ form.item.currency_type_symbol }}
                                </template>
                            </el-input>
                            <small v-if="errors.unit_price"
                                   class="form-control-feedback"
                                   v-text="errors.unit_price[0]"></small>
                        </div>
                    </div>
                     <div v-if="showLots" class="col-md-3 col-sm-3" style="padding-top: 1%;">
                        <a class="text-center font-weight-bold text-info" href="#" @click.prevent="clickLotGroup">[&#10004;
                                                                                                                  Seleccionar
                                                                                                                  lote]</a>
                    </div>

                    <div v-if="showSeries"
                         class="col-md-3 col-sm-3"
                         style="padding-top: 1%;">
                        <!-- <el-button type="primary" native-type="submit" icon="el-icon-check">Elegir serie</el-button> -->
                        <a class="text-center font-weight-bold text-info"
                           href="#"
                           @click.prevent="clickSelectLots">[&#10004;
                                                            Seleccionar
                                                            series]</a>
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
                                          slot="prepend">
                                    {{ form.item.currency_type_symbol }}
                                </template>
                            </el-input>
                            <small v-if="errors.total_item"
                                   class="form-control-feedback"
                                   v-text="errors.total_item[0]"></small>
                        </div>
                    </div>
                    <div v-if="config.edit_name_product"
                         class="col-md-12 col-sm-12 mt-2">
                        <div class="form-group">
                            <label class="control-label">Nombre producto en PDF</label>
                            <vue-ckeditor v-model="form.name_product_pdf"
                                          :editors="editors"
                                          type="classic"></vue-ckeditor>
                        </div>
                    </div>
                    <template v-if="!is_client">

                        <div v-if="item_unit_types.length > 0"
                             class="col-md-12">
                            <div class="table-responsive"
                                 style="margin:3px">
                                <h5 class="separator-title">
                                    Lista de Precios
                                    <el-tooltip class="item"
                                                content="Aplica para realizar compra/venta en presentacion de diferentes precios y/o cantidades"
                                                effect="dark"
                                                placement="top">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </h5>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Unidad</th>
                                        <th class="text-center">Descripción</th>
                                        <th class="text-center">Factor</th>
                                        <th class="text-center">Precio 1</th>
                                        <th class="text-center">Precio 2</th>
                                        <th class="text-center">Precio 3</th>
                                        <th class="text-center">Precio Default</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in item_unit_types"
                                        :key="index">
                                        <td class="text-center">{{ row.unit_type_id }}</td>
                                        <td class="text-center">{{ row.description }}</td>
                                        <td class="text-center">{{ row.quantity_unit }}</td>
                                        <td class="text-center">{{ row.price1 }}</td>
                                        <td class="text-center">{{ row.price2 }}</td>
                                        <td class="text-center">{{ row.price3 }}</td>
                                        <td class="text-center">Precio {{ row.price_default }}</td>
                                        <td class="series-table-actions text-right">
                                            <button :class="getSelectedClass(row)" class="btn waves-effect waves-light btn-xs"
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


                        <!--<div class="col-md-6" v-show="has_list_prices">
                            <div class="form-group" :class="{'has-danger': errors.item_unit_type_id}">
                                <label class="control-label">Presentación</label>
                                <el-select v-model="form.item_unit_type_id" filterable @change="changePresentation">
                                    <el-option v-for="option in item_unit_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <el-radio-group v-if="form.item_unit_type_id" v-model="item_unit_type.price_default" @change="changePresentation">
                                    <el-radio :label="1">Precio 1</el-radio>
                                    <el-radio :label="2">Precio 2</el-radio>
                                    <el-radio :label="3">Precio 3</el-radio>
                                </el-radio-group>
                                <small class="form-control-feedback" v-if="errors.item_unit_type_id" v-text="errors.item_unit_type_id[0]"></small>
                            </div>
                        </div>-->
                        <div class="col-md-12 mt-2">
                            <el-collapse v-model="activePanel">
                                <el-collapse-item :disabled="recordItem != null"
                                                  name="1"
                                                  title="+ Agregar Descuentos/Cargos/Atributos especiales">
                                    <div v-if="discount_types.length > 0">
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
                                            <tr v-for="(row, index) in form.discounts"
                                                :key="index">
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
                                                    <el-checkbox v-model="row.is_amount">Ingresar monto fijo
                                                    </el-checkbox>
                                                    <br>
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
                                    <div v-if="charge_types.length > 0">
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
                                            <tr v-for="(row, index) in form.charges"
                                                :key="index">
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
                                    <div v-if="attribute_types.length > 0">
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
                                            <tr v-for="(row, index) in form.attributes"
                                                :key="index">
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
                                                    <el-input v-model="row.value"
                                                              @input="inputAttribute(index)"></el-input>
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
                                </el-collapse-item>
                            </el-collapse>
                        </div>
                    </template>
                </div>
            </div>

            <!-- @todo: Mejorar evitando duplicar codigo -->
            <!-- Mostrar en cel -->

            <div class="row hidden-md-up form-actions text-center">
                <div class="col-12">
                    &nbsp;
                </div>
                <div class="col-6">
                    <el-button class="form-control"
                               @click.prevent="close()">Cerrar
                    </el-button>
                </div>
                <div class="col-6">
                    <el-button v-if="form.item_id"
                               class="add form-control btn btn-primary"
                               native-type="submit"
                               type="primary">
                        Agregar
                    </el-button>
                </div>
            </div>
            <!-- @todo: Mejorar evitando duplicar codigo -->
            <!-- Mostrar en cel -->
            <!-- @todo: Mejorar evitando duplicar codigo -->
            <!-- Ocultar en cel -->

            <div class="form-actions text-right pt-2  hidden-sm-down">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button v-if="form.item_id"
                           class="add"
                           native-type="submit"
                           type="primary">Agregar
                </el-button>
            </div>
        </form>
        <item-form :external="true"
                   :showDialog.sync="showDialogNewItem"></item-form>


        <warehouses-detail
            :showDialog.sync="showWarehousesDetail"
            :warehouses="warehousesDetail">
        </warehouses-detail>

        <select-lots-form
            :lots="lots"
            :showDialog.sync="showDialogSelectLots"
            :itemId="form.item_id"
             :quantity="form.quantity"
            @addRowSelectLot="addRowSelectLot"
        >
        </select-lots-form>

        <lots-group
            :lots_group="form.lots_group"
            :quantity="form.quantity"
            :showDialog.sync="showDialogLots"
            @addRowLotGroup="addRowLotGroup">
        </lots-group>

    </el-dialog>
</template>
<style>
.el-select-dropdown {
    margin-right: 5% !important;
    max-width: 80% !important;
}
</style>
<script>

// import WarehousesDetail from './warehouses.vue'
import ItemForm from "../../../../../../../../resources/js/views/tenant/items/form";
import LotsGroup from "../../../../../../../../resources/js/views/tenant/sale_notes/partials/lots_group";

import {calculateRowItem} from '@helpers/functions'
import WarehousesDetail from '@views/documents/partials/select_warehouses.vue'
import SelectLotsForm from './lots.vue'

import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import VueCkeditor from "vue-ckeditor5";
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import {ItemOptionDescription, ItemSlotTooltip} from "../../../../../../../../resources/js/helpers/modal_item";



export default {
    props: [
        'recordItem',
        'showDialog',
        'currencyTypeIdActive',
        'exchangeRateSale',
        'typeUser',
        'configuration',
    ],
    components: {ItemForm, WarehousesDetail, LotsGroup, SelectLotsForm, 'vue-ckeditor': VueCkeditor.component},

    data() {
        return {
            can_add_new_product: false,
            loading_search: false,
            titleAction: '',
            is_client: false,
            titleDialog: 'Agregar Producto o Servicio',
            resource: 'order-notes',
            showDialogNewItem: false,
            has_list_prices: false,
            errors: {},
            form: {},
            all_items: [],
            items: [],
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
        this.$store.commit('setConfiguration', this.configuration)
        this.initForm()
    },
    mounted() {
        this.getTables()


        this.$eventHub.$on('reloadDataItems', (item_id) => {
            this.reloadDataItems(item_id)
        })

        this.$eventHub.$on('selectWarehouseId', (warehouse_id) => {
            this.form.warehouse_id = warehouse_id
        })
        this.canCreateProduct();
    },
    computed: {

        ...mapState([
            'config',
        ]),

        showLots() {

            if (this.form.item_id && this.form.item.lots_enabled) {
                return true;
            }

            return false;
        },
        showSeries() {
            if (
                this.form.item_id &&
                this.form.item.series_enabled
            ) {
                return true
            }
            return false;
        },
        canEditPrice: function () {
            if (
                (
                    this.typeUser === 'admin'
                ) || (
                    this.config !== undefined &&
                    this.config.allow_edit_unit_price_to_seller !== undefined &&
                    this.config.allow_edit_unit_price_to_seller === true
                )
            ) {
                return false;
            }
            return true;
        },
        documentItem() {
            if (this.recordItem !== undefined &&
                this.recordItem !== null &&
                this.recordItem.id !== undefined &&
                this.recordItem.id !== 0) {
                this.form.document_item_id = this.recordItem.id;
                return this.recordItem.id;
            }
            return this.form.document_item_id;
        },
        edit_unit_price() {
            if (this.typeUser === 'admin') {
                return true
            }
            if (this.typeUser === 'seller') {
                return this.config.allow_edit_unit_price_to_seller;
            }
            return false;
        }
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
        getTables() {
            this.$http.get(`/${this.resource}/item/tables`).then(response => {
                this.items = response.data.items
                this.affectation_igv_types = response.data.affectation_igv_types
                this.system_isc_types = response.data.system_isc_types
                this.discount_types = response.data.discount_types
                this.charge_types = response.data.charge_types
                this.attribute_types = response.data.attribute_types
                // this.filterItems()

            })
        },
        canCreateProduct() {
            if (this.typeUser === 'admin') {
                this.can_add_new_product = true
            } else if (this.typeUser === 'seller') {
                if (this.config !== undefined && this.config.seller_can_create_product !== undefined) {
                    this.can_add_new_product = this.config.seller_can_create_product;
                }
            }
            return this.can_add_new_product;
        },

        validateQuantity() {

            if (!this.form.quantity) {
                this.setMinQuantity()
            }

            if (isNaN(Number(this.form.quantity))) {
                this.setMinQuantity()
            }

            if (typeof parseFloat(this.form.quantity) !== 'number') {
                this.setMinQuantity()
            }

            if (this.form.quantity <= this.getMinQuantity()) {
                this.setMinQuantity()
            }

            this.calculateTotal()
        },

        changeValidateQuantity(event) {
            this.calculateTotal()
        },
        getMinQuantity() {
            return 0.01
        },
        setMinQuantity() {
            this.form.quantity = this.getMinQuantity()
        },

        clickDecrease() {

            this.form.quantity = parseInt(this.form.quantity - 1)

            if (this.form.quantity <= this.getMinQuantity()) {
                this.setMinQuantity()
                return
            }

            this.calculateTotal()

        },
        clickIncrease() {
            this.form.quantity = parseInt(this.form.quantity + 1)
            this.calculateTotal()
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
            this.items = this.all_items
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
        clickWarehouseDetail() {

            if (!this.form.item_id) {
                return this.$message.error('Seleccione un item');
            }

            let item = _.find(this.items, {'id': this.form.item_id});

            this.warehousesDetail = item.warehouses
            this.showWarehousesDetail = true
        },
        //filterItems() {
        // this.items = this.items.filter(item => item.warehouses.length >0)
        // },
        initForm() {
            this.errors = {};

            this.form = {
                // category_id: [1],
                // edit: false,
                item_id: null,
                item: {},
                affectation_igv_type_id: null,
                affectation_igv_type: {},
                has_isc: false,
                system_isc_type_id: null,
                percentage_isc: 0,
                suggested_price: 0,
                quantity: 1,
                unit_price: 0,
                unit_price_value: 0,
                input_unit_price: 0,
                input_unit_price_value: 0,
                charges: [],
                discounts: [],
                attributes: [],
                has_igv: null,
                item_unit_type_id: null,
                unit_type_id: null,
                is_set: false,
                item_unit_types: [],
                has_plastic_bag_taxes: false,
                series_enabled: false,
                warehouse_id: null,
                lots_group: [],
                IdLoteSelected: null,
                document_item_id: null,
                name_product_pdf: ''
            };

            this.activePanel = 0;
            this.total_item = 0;
            this.item_unit_type = {};
            this.lots = []
            this.has_list_prices = false;
        },
        // initializeFields() {
        //     this.form.affectation_igv_type_id = this.affectation_igv_types[0].id
        // },
        create() {
            /* Migrado de resources/js/views/tenant/sale_notes/partials/item.vue*/
            /*

            this.titleDialog = (this.recordItem) ? ' Editar Producto o Servicio' : ' Agregar Producto o Servicio';
            this.titleAction = (this.recordItem) ? ' Editar' : ' Agregar';
            if(this.operation_types !== undefined) {
                let operation_type = await _.find(this.operation_types, {id: this.operationTypeId})
                if(operation_type !== undefined) {
                    this.affectation_igv_types = await _.filter(this.all_affectation_igv_types, {exportation: operation_type.exportation})
                }
            }

            if (this.recordItem) {
                await this.reloadDataItems(this.recordItem.item_id)
                this.form.item_id = await this.recordItem.item_id
                await this.changeItem()
                this.form.quantity = this.recordItem.quantity
                this.form.unit_price_value = this.recordItem.input_unit_price_value
                this.form.has_plastic_bag_taxes = (this.recordItem.total_plastic_bag_taxes > 0) ? true : false
                this.form.warehouse_id = this.recordItem.warehouse_id
                this.isUpdateWarehouseId = this.recordItem.warehouse_id

                if (this.isEditItemNote) {
                    this.form.item.currency_type_id = this.currencyTypeIdActive
                    this.form.item.currency_type_symbol = (this.currencyTypeIdActive == 'PEN') ? 'S/' : '$'

                    if (this.documentTypeId == '07' && this.noteCreditOrDebitTypeId == '07') {

                        this.form.document_item_id = this.recordItem.id ? this.recordItem.id : this.recordItem.document_item_id
                        this.form.item.lots = this.recordItem.item.lots
                        await this.regularizeLots()
                        this.lots = this.form.item.lots
                    }

                }

                if (this.recordItem.item.name_product_pdf) {
                    this.form.name_product_pdf = this.recordItem.item.name_product_pdf
                }
                // if(this.recordItem.name_product_pdf){
                //     this.form.name_product_pdf = this.recordItem.name_product_pdf
                // }

                if(this.recordItem.item.change_free_affectation_igv){

                    this.form.affectation_igv_type_id = '15'
                    this.form.item.change_free_affectation_igv = true

                }else{
                    if(this.recordItem.item.original_affectation_igv_type_id){
                        this.form.affectation_igv_type_id = this.recordItem.item.original_affectation_igv_type_id
                    }
                }
                this.calculateQuantity()
            } else {
                this.isUpdateWarehouseId = null
            }

            */
            //     this.initializeFields()
        },
        async regularizeLots() {

            if (this.form.document_item_id && this.form.item.lots.length > 0) {

                await this.$http.get(`/${this.resource}/regularize-lots/${this.form.document_item_id}`).then((response) => {

                    let all_lots = this.form.item.lots
                    let available_lots = response.data

                    all_lots.forEach((lot, index) => {

                        let exist_lot = _.find(available_lots, (it) => {
                            return it.id == lot.id
                        })

                        if (!exist_lot) {
                            this.form.item.lots.splice(index, 1)
                        }

                    })
                })
                    .catch(error => {
                    })
                    .then(() => {
                    })

            }

        },
        clickAddDiscount() {
            this.form.discounts.push({
                discount_type_id: null,
                discount_type: null,
                description: null,
                percentage: 0,
                factor: 0,
                amount: 0,
                base: 0,
                is_amount: false
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
        changeItem() {
            this.getItems();
            this.form.item = _.find(this.items, {'id': this.form.item_id});
            this.form.unit_price = this.form.item.sale_unit_price;

            this.lots = this.form.item.lots

            this.form.has_igv = this.form.item.has_igv;

            this.form.affectation_igv_type_id = this.form.item.sale_affectation_igv_type_id;
            this.form.quantity = 1;
            this.item_unit_types = this.form.item.item_unit_types;

            (this.item_unit_types.length > 0) ? this.has_list_prices = true : this.has_list_prices = false;

            this.form.lots_group = this.form.item.lots_group

            this.cleanTotalItem();
        },

        focusTotalItem(change) {
            if (!change && this.form.item.calculate_quantity) {
                this.$refs.total_item.$el.getElementsByTagName('input')[0].focus()
                this.total_item = this.form.unit_price_value
            }
        },
        reloadDataItems(item_id) {
            this.$http.get(`/${this.resource}/table/items`).then((response) => {
                this.items = response.data
                this.form.item_id = item_id
                if (item_id) {
                    this.changeItem()
                }
                // this.filterItems()

            })
        },

        calculateTotal() {
            this.readonly_total = _.round((this.form.quantity * this.form.unit_price_value), 4)
        },
        calculateQuantity() {
            if (this.form.item.calculate_quantity) {
                this.form.quantity = _.round((this.total_item / this.form.unit_price), 4)
            }
        },
        cleanTotalItem() {
            this.total_item = null;
        },
        async clickAddItem() {

            this.validateQuantity()

            if (this.form.item.lots_enabled) {
                if (!this.form.IdLoteSelected)
                    return this.$message.error('Debe seleccionar un lote.');
            }

            let select_lots = await _.filter(this.form.item.lots, {'has_sale': true})

            if (this.form.item.series_enabled) {
                if (select_lots.length != this.form.quantity)
                    return this.$message.error('La cantidad de series seleccionadas son diferentes a la cantidad a vender');
            }

            if (this.validateTotalItem().total_item) return;

            // this.form.item.unit_price = this.form.unit_price;
            let unit_price = (this.form.has_igv) ? this.form.unit_price : this.form.unit_price * 1.18;

            // this.form.item.unit_price = this.form.unit_price
            this.form.unit_price = unit_price;
            this.form.item.unit_price = unit_price;

            this.form.item.presentation = this.item_unit_type;
            this.form.affectation_igv_type = _.find(this.affectation_igv_types, {'id': this.form.affectation_igv_type_id});
            let IdLoteSelected = this.form.IdLoteSelected
            this.row = calculateRowItem(this.form, this.currencyTypeIdActive, this.exchangeRateSale);
            this.row.IdLoteSelected = IdLoteSelected
            this.initForm();

            // this.initializeFields()
            this.$emit('add', this.row);
            this.setFocusSelectItem()
        },
        cleanItems() {
            this.items = []
            this.$refs.selectBarcode.$el.getElementsByTagName('input')[0].focus()
            // console.log("add cart barcode")
        },
        validateTotalItem() {

            this.errors = {}

            if (this.form.item.calculate_quantity) {
                if (this.total_item < 0.01)
                    this.$set(this.errors, 'total_item', ['total venta producto debe ser mayor a 0']);
            }

            return this.errors
        },
        changePresentation() {
            let price = 0;

            this.item_unit_type = _.find(this.form.item.item_unit_types, {'id': this.form.item_unit_type_id});

            switch (this.item_unit_type.price_default) {
                case 1:
                    price = this.item_unit_type.price1
                    break;
                case 2:
                    price = this.item_unit_type.price2
                    break;
                case 3:
                    price = this.item_unit_type.price3
                    break;
            }

            this.form.unit_price = price;
            this.form.item.unit_type_id = this.item_unit_type.unit_type_id;
        },
        selectedPrice(row) {
            if (this.isSelectedPrice(row)) {

                this.form.item_unit_type_id = null
                this.item_unit_type = {}
                this.form.unit_price = this.form.item.sale_unit_price
                this.form.unit_price_value = this.form.item.sale_unit_price
                this.form.item.unit_type_id = this.form.item.original_unit_type_id

            } else {

                let valor = 0
                switch (row.price_default) {
                    case 1:
                        valor = row.price1
                        break
                    case 2:
                        valor = row.price2
                        break
                    case 3:
                        valor = row.price3
                        break

                }
                this.form.item_unit_type_id = row.id
                this.item_unit_type = row
                this.form.unit_price = valor
                this.form.unit_price_value = valor
                this.form.item.unit_type_id = row.unit_type_id
            }

            this.calculateQuantity()
        },
        getItems() {
            this.$http.get(`/${this.resource}/item/tables`).then(response => {
                this.items = response.data.items
            })
        },
        addRowLotGroup(id) {
            this.form.IdLoteSelected = id
        },
        clickLotGroup() {
            this.showDialogLots = true
        },
        clickSelectLots() {
            this.showDialogSelectLots = true
        },
        addRowSelectLot(lots) {
            this.lots = lots
        },
        focusSelectItem() {
            this.$refs.selectSearchNormal.$el.getElementsByTagName('input')[0].focus()
        },
        setFocusSelectItem() {

            this.$refs.selectSearchNormal.$el.getElementsByTagName('input')[0].focus()

        },
        validateQuantity() {

            if (!this.form.quantity) {
                this.setMinQuantity()
            }

            if (isNaN(Number(this.form.quantity))) {
                this.setMinQuantity()
            }

            if (typeof parseFloat(this.form.quantity) !== 'number') {
                this.setMinQuantity()
            }

            if (this.form.quantity <= this.getMinQuantity()) {
                this.setMinQuantity()
            }

            this.calculateTotal()
        },
        setMinQuantity() {
            this.form.quantity = this.getMinQuantity()
        },
        getMinQuantity() {
            return 0.01
        },
        getSelectedClass(row) {
            if (this.isSelectedPrice(row)) return 'btn-success'
            return 'btn-secondary'
        },
        isSelectedPrice(item_unit_type) {
            if (!_.isEmpty(this.item_unit_type)) {
                return (this.item_unit_type.id === item_unit_type.id)
            }
            return false
        },
    }
}

</script>
