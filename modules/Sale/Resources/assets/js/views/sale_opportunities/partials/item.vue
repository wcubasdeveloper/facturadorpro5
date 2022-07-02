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

                            <!-- <el-select v-model="form.item_id" @change="changeItem" filterable  ref="selectSearchNormal" @focus="focusSelectItem">
                                <el-option v-for="option in items" :key="option.id" :value="option.id" :label="option.full_description"></el-option>
                            </el-select> -->


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
                            <el-checkbox v-model="change_affectation_igv_type_id">
                                Editar
                            </el-checkbox>
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
                    <div v-if="item_unit_types.length > 0"
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


        <warehouses-detail
            :showDialog.sync="showWarehousesDetail"
            :warehouses="warehousesDetail">
        </warehouses-detail>
    </el-dialog>
</template>
<style>
.el-select-dropdown {
    margin-right: 5% !important;
    max-width: 80% !important;
}
</style>
<script>

import itemForm from '@views/items/form.vue'
import {calculateRowItem} from '@helpers/functions'
import WarehousesDetail from './warehouses.vue'

import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import VueCkeditor from "vue-ckeditor5";
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import {ItemOptionDescription, ItemSlotTooltip} from "../../../../../../../../resources/js/helpers/modal_item";

export default {
    props: [
        'showDialog',
        'currencyTypeIdActive',
        'exchangeRateSale'
    ],
    components: {itemForm, WarehousesDetail, 'vue-ckeditor': VueCkeditor.component},

    data() {
        return {
            can_add_new_product: false,
            loading_search: false,
            titleAction: '',
            is_client: false,
            titleDialog: 'Agregar Producto o Servicio',
            resource: 'sale-opportunities',
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
        // this.$store.commit('setConfiguration', this.configuration)
        this.initForm()
        this.$http.get(`/${this.resource}/item/tables`).then(response => {
            this.items = response.data.items
            this.affectation_igv_types = response.data.affectation_igv_types
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
                charges: [],
                discounts: [],
                attributes: [],
                has_igv: null,
                item_unit_type_id: null,
                unit_type_id: null,
                is_set: false,
            };

            this.total_item = 0;
            this.item_unit_type = {};
            this.has_list_prices = false;
        },
        close() {
            this.initForm()
            this.$emit('update:showDialog', false)
        },
        changeItem() {
            this.getItems();
            this.form.item = _.find(this.items, {'id': this.form.item_id});
            this.form.unit_price = this.form.item.sale_unit_price;

            this.form.has_igv = this.form.item.has_igv;

            this.form.affectation_igv_type_id = this.form.item.sale_affectation_igv_type_id;
            this.form.quantity = 1;
            this.item_unit_types = this.form.item.item_unit_types;

            (this.item_unit_types.length > 0) ? this.has_list_prices = true : this.has_list_prices = false;

            this.cleanTotalItem();
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


            this.item_unit_type = row
            this.form.unit_price = valor
            this.form.item.unit_type_id = row.unit_type_id
            this.form.item_unit_type_id = row.id
        },
        clickAddItem() {

            if (this.validateTotalItem().total_item) return;

            // this.form.item.unit_price = this.form.unit_price;
            let unit_price = (this.form.has_igv) ? this.form.unit_price : this.form.unit_price * 1.18;

            // this.form.item.unit_price = this.form.unit_price
            this.form.unit_price = unit_price;
            this.form.item.unit_price = unit_price;

            this.form.item.presentation = this.item_unit_type;
            this.form.affectation_igv_type = _.find(this.affectation_igv_types, {'id': this.form.affectation_igv_type_id});
            this.row = calculateRowItem(this.form, this.currencyTypeIdActive, this.exchangeRateSale);

            this.initForm();

            // this.initializeFields()
            this.$emit('add', this.row);
            this.setFocusSelectItem()
        },
        focusSelectItem() {
            this.$refs.selectSearchNormal.$el.getElementsByTagName('input')[0].focus()
        },
        setFocusSelectItem() {

            this.$refs.selectSearchNormal.$el.getElementsByTagName('input')[0].focus()

        },
        cleanTotalItem() {
            this.total_item = null;
        },
        calculateQuantity() {
            if (this.form.item.calculate_quantity) {
                this.form.quantity = _.round((this.total_item / this.form.unit_price), 4)
            }
        },
        getItems() {
            this.$http.get(`/${this.resource}/item/tables`).then(response => {
                this.items = response.data.items
            })
        },
        validateTotalItem() {

            this.errors = {}

            if (this.form.item.calculate_quantity) {
                if (this.total_item < 0.01)
                    this.$set(this.errors, 'total_item', ['total venta producto debe ser mayor a 0']);
            }

            return this.errors
        },
        create() {

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
    }
}

</script>
