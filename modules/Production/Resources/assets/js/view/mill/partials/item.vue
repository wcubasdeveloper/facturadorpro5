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
                    <div class="col-md-12">
                        <div id="custom-select"
                             :class="{'has-danger': errors.item_id}"
                             class="form-group">
                            <label class="control-label">
                                Producto
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
                                        filterable
                                        placeholder="Buscar"
                                        popper-class="el-select-items"
                                        @change="changeItem"
                                    >
                                        <!--@focus="focusSelectItem"-->
                                        <el-option v-for="option in items"
                                                   :key="option.id"
                                                   :label="option.description"
                                                   :value="option.id"></el-option>
                                    </el-select>
                                    <!--
                                    <el-tooltip slot="append"
                                                class="item"
                                                content="Ver Stock del Producto"
                                                effect="dark"
                                                placement="bottom">
                                        <el-button @click.prevent="clickWarehouseDetail()"><i class="fa fa-search"></i>
                                        </el-button>
                                    </el-tooltip>
                                    -->
                                </el-input>
                            </template>

                            <small v-if="errors.item_id"
                                   class="form-control-feedback"
                                   v-text="errors.item_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.height_to_mill}"
                             class="form-group">
                            <label class="control-label">
                                Cantidad a moler
                            </label>
                            <el-input-number v-model="form.height_to_mill"
                                             :min="1"></el-input-number>
                            <small v-if="errors.height_to_mill"
                                   class="form-control-feedback"
                                   v-text="errors.height_to_mill[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.total_height}"
                             class="form-group">
                            <label class="control-label">
                                Cantidad obtenida
                            </label>
                            <el-input-number v-model="form.total_height"
                                             :min="0.01"></el-input-number>
                            <small v-if="errors.total_height"
                                   class="form-control-feedback"
                                   v-text="errors.total_height[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.unit_types}"
                             class="form-group">
                            <label class="control-label">
                                Unidades de medida
                                <el-tooltip
                                    class="item"
                                    content="Unidad de medida principal y multiplicacion de unidades de medida de presentacion de producto."
                                    effect="dark"
                                    placement="top-start">
                                    <i class="fa fa-info-circle"></i>
                                </el-tooltip>
                            </label>
                            <el-select
                                v-model="form.unit_type_id"
                                filterable
                                @change="selectUnit"

                            >
                                <el-option v-for="option in unit_types_item"
                                           :key="option.id"
                                           :disabled="!option.active"
                                           :label="option.description"
                                           :value="option.id"></el-option>
                            </el-select>

                            <small v-if="errors.unit_type_id"
                                   class="form-control-feedback"
                                   v-text="errors.unit_type_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.item_extra_data}"
                             class="form-group">
                            <label class="control-label">
                                Color
                            </label>
                            <el-select v-model="form.item_extra_data.color"
                                       :disable="item === undefined || item.colors === undefined ||item.colors.length < 1"
                                       filterable>
                                <el-option
                                    v-for="option in item.colors"

                                    :key="option.id"
                                    :label="option.color_name"
                                    :value="option.id"
                                ></el-option>
                            </el-select>
                            <small v-if="errors.item_extra_data"
                                   class="form-control-feedback"
                                   v-text="errors.item_extra_data[0]"></small>
                        </div>
                    </div>


                    <!-- item_unit_types -->
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button v-if="form.item_id"
                           native-type="submit"
                           type="primary">
                    Agregar
                </el-button>
            </div>
        </form>
        <item-form :external="true"
                   :showDialog.sync="showDialogNewItem"></item-form>

        <!--
                <warehouses-detail
                    :showDialog.sync="showWarehousesDetail"
                    :warehouses="warehousesDetail">
                </warehouses-detail>-->
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
// import WarehousesDetail from './warehouses.vue'
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
    components: {
        itemForm,
        // WarehousesDetail,
        'vue-ckeditor':
        VueCkeditor.component
    },

    data() {
        return {
            can_add_new_product: false,
            loading_search: false,
            titleAction: '',
            is_client: false,
            titleDialog: 'Agregar Producto',
            resource: 'mill-production',
            showDialogNewItem: false,
            has_list_prices: false,
            errors: {},
            form: {
                height_to_mill: 0,
                total_height: 0,
                unit_type_id: null,
                unit_id: 0,
                item_extra_data: {
                    color:null
                },
            },
            all_items: [],
            items: [],
            item: {},
            operation_types: [],
            all_affectation_igv_types: [],
            aux_items: [],
            affectation_igv_types: [],
            system_isc_types: [],
            discount_types: [],
            charge_types: [],
            unit_types: [],
            unit_types_item: [],
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
            this.unit_types = response.data.unit_types
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
        //filterItems() {
        // this.items = this.items.filter(item => item.warehouses.length >0)
        // },
        initForm() {
            this.errors = {};

            this.form = {
                item_id: null,
                item: {},
                height_to_mill: 0,
                total_height: 0,
                item_extra_data: {
                    color:null
                },
            };
            this.form.unit_type_id = null;
            this.form.unit_id = 0;
        },
        clickRemoveAttribute(index) {
            this.form.attributes.splice(index, 1)
        },
        close() {
            this.initForm()
            this.$emit('update:showDialog', false)
        },
        changeItem() {
            this.getItems();
            let item = _.find(this.items, {'id': this.form.item_id});

            this.form.item = item;

            /*
            this.form.item_extra_data= {}
            this.form.item_extra_data.color = null
            this.item = item
            */
            this.item = item


            if (item !== undefined) {
                this.form.unit_type_id= this.item.unit_type_id;
                this.updateUnitTypes()
            }

        },
        clickAddItem() {

            this.form.quantity = this.form.total_height
            this.form.full_description = (this.item && this.item.description) ? this.item.description : '';
            this.$emit('add', this.form);
            this.initForm();
            this.setFocusSelectItem()
        },
        focusSelectItem() {
            this.$refs.selectSearchNormal.$el.getElementsByTagName('input')[0].focus()
        },
        setFocusSelectItem() {

            this.$refs.selectSearchNormal.$el.getElementsByTagName('input')[0].focus()

        },
        getItems() {
            this.$http.get(`/${this.resource}/item/tables`).then(response => {
                this.unit_types = response.data.unit_types
                this.items = response.data.items
            })
        },
        create() {
            //     this.initializeFields()
        },
        updateUnitTypes() {

            let unitTypeItem = undefined, unitTypesItem = undefined, unit_types_item_temp = [];

            this.unit_types_item = unit_types_item_temp;
            if (this.item !== undefined) {
                if (this.item.unit_type_id !== undefined) {
                    unitTypeItem = this.item.unit_type_id
                }
                if (this.item.item_unit_types !== undefined) {
                    unitTypesItem = this.item.item_unit_types
                }
                this.unit_types.forEach(function (a, b) {
                    if (a.id === unitTypeItem) {
                        a.unit_id = 0
                        unit_types_item_temp.push(a)
                    } else if (unitTypesItem !== undefined ){
                        let t =  _.find(unitTypesItem, {unit_type_id: a.id});
                        if(t !== undefined) {
                            a.unit_id = t.id
                            unit_types_item_temp.push(a)
                        }
                    }
                })
            }
            this.unit_types_item = unit_types_item_temp;
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
        selectUnit(){
            let t = _.find(this.unit_types_item,{id:this.form.unit_type_id})
            if(t !== undefined){
                this.form.unit_id = t.unit_id
            }else{
                this.form.unit_id = 0
            }
        }
    }
}

</script>
