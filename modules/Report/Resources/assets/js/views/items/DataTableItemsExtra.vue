<template>
    <div>
        <div class="row">

            <div class="col-md-12 col-lg-12 col-xl-12 ">

                <div class="row mt-2">

                    <!-- Producto -->
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                            <label class="control-label">Productos
                            </label>

                            <el-select v-model="form.item_id"
                                       :loading="loading_search"
                                       :remote-method="searchRemotePersons"
                                       clearable
                                       filterable
                                       placeholder="Código interno o nombre"
                                       popper-class="el-select-customers"
                                       remote
                                       @change="changePersons">
                                <el-option v-for="option in items"
                                           :key="option.id"
                                           :label="option.description"
                                           :value="option.id"></el-option>
                            </el-select>

                        </div>
                    </div>
                    <!-- Periodo -->
                    <div class="col-md-3">
                        <label class="control-label">Periodo</label>
                        <el-select v-model="form.period"
                                   @change="changePeriod">
                            <el-option key="month"
                                       label="Por mes"
                                       value="month"></el-option>
                            <el-option key="between_months"
                                       label="Entre meses"
                                       value="between_months"></el-option>
                            <el-option key="date"
                                       label="Por fecha"
                                       value="date"></el-option>
                            <el-option key="between_dates"
                                       label="Entre fechas"
                                       value="between_dates"></el-option>
                        </el-select>
                    </div>
                    <!-- Periodo Mes -->
                    <template v-if="form.period === 'month' || form.period === 'between_months'">
                        <div class="col-md-3">
                            <label class="control-label">Mes de</label>
                            <el-date-picker v-model="form.month_start"
                                            :clearable="false"
                                            format="MM/yyyy"
                                            type="month"
                                            value-format="yyyy-MM"
                                            @change="changeDisabledMonths"></el-date-picker>
                        </div>
                    </template>
                    <!-- Periodo Mes -->

                    <template v-if="form.period === 'between_months'">
                        <div class="col-md-3">
                            <label class="control-label">Mes al</label>
                            <el-date-picker v-model="form.month_end"
                                            :clearable="false"
                                            :picker-options="pickerOptionsMonths"
                                            format="MM/yyyy"
                                            type="month"
                                            value-format="yyyy-MM"></el-date-picker>
                        </div>
                    </template>
                    <!-- Periodo Fecha -->
                    <template v-if="form.period === 'date' || form.period === 'between_dates'">
                        <div class="col-md-3">
                            <label class="control-label">Fecha del</label>
                            <el-date-picker v-model="form.date_start"
                                            :clearable="false"
                                            format="dd/MM/yyyy"
                                            type="date"
                                            value-format="yyyy-MM-dd"
                                            @change="changeDisabledDates"></el-date-picker>
                        </div>
                    </template>
                    <!-- Periodo Fecha -->
                    <template v-if="form.period === 'between_dates'">
                        <div class="col-md-3">
                            <label class="control-label">Fecha al</label>
                            <el-date-picker v-model="form.date_end"
                                            :clearable="false"
                                            :picker-options="pickerOptionsDates"
                                            format="dd/MM/yyyy"
                                            type="date"
                                            value-format="yyyy-MM-dd"></el-date-picker>
                        </div>
                    </template>
                    <!-- Compra -->

                    <div v-if="defaultType !== 'purchase'"
                         class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Plataforma</label>
                            <el-select v-model="form.web_platform_id"
                                       clearable>
                                <el-option v-for="option in web_platforms"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>


                    <!-- Tamaño -->
                    <div v-if="hasCatItemSize"
                         class="col-md-4"
                    >
                        <div
                            class="form-group">
                            <label class="control-label">
                                Tamaño
                            </label>
                            <el-select v-model="form.extra.CatItemSize"
                                       :clearable="clearable"
                                       :multiple="multiple"

                            >
                                <el-option v-for="option in extra_CatItemSize"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>
                    <!-- colors -->
                    <div v-if="hasColors"
                         class="col-md-4"
                    >
                        <div
                            class="form-group">
                            <label class="control-label">
                                Colores
                            </label>
                            <el-select v-model="form.extra.colors"
                                       :clearable="clearable"
                                       :multiple="multiple"

                            >
                                <el-option v-for="option in extra_colors"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>
                    <!-- CatItemUnitsPerPackage -->
                    <div v-if="hasCatItemUnitsPerPackage"
                         class="col-md-4"
                    >
                        <div
                            class="form-group">
                            <label class="control-label">
                                Cantidad de unidades por empaque
                            </label>
                            <el-select v-model="form.extra.CatItemUnitsPerPackage"
                                       :clearable="clearable"
                                       :multiple="multiple"

                            >
                                <el-option v-for="option in extra_CatItemUnitsPerPackage"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>
                    <!-- CatItemMoldProperty -->
                    <div v-if="hasCatItemMoldProperty"
                         class="col-md-4"
                    >
                        <div
                            class="form-group">
                            <label class="control-label">
                                Propiedades del molde
                            </label>
                            <el-select v-model="form.extra.CatItemMoldProperty"
                                       :clearable="clearable"
                                       :multiple="multiple"

                            >
                                <el-option v-for="option in extra_CatItemMoldProperty"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>

                    <!-- CatItemUnitBusiness -->
                    <div v-if="hasCatItemUnitBusiness"
                         class="col-md-4"
                    >
                        <div
                            class="form-group">
                            <label class="control-label">
                                Unidades de negocio
                            </label>
                            <el-select v-model="form.extra.CatItemUnitBusiness"
                                       :clearable="clearable"
                                       :multiple="multiple"

                            >
                                <el-option v-for="option in extra_CatItemUnitBusiness"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>

                    <!-- CatItemStatus -->
                    <div v-if="hasCatItemStatus"
                         class="col-md-4"
                    >
                        <div
                            class="form-group">
                            <label class="control-label">
                                Status del item
                            </label>
                            <el-select v-model="form.extra.CatItemStatus"
                                       :clearable="clearable"
                                       :multiple="multiple"

                            >
                                <el-option v-for="option in extra_CatItemStatus"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>


                    <!-- CatItemPackageMeasurement -->
                    <div v-if="hasCatItemPackageMeasurement"
                         class="col-md-4"
                    >
                        <div
                            class="form-group">
                            <label class="control-label">
                                Unidades de medida
                            </label>
                            <el-select v-model="form.extra.CatItemPackageMeasurement"
                                       :clearable="clearable"
                                       :multiple="multiple"

                            >
                                <el-option v-for="option in extra_CatItemPackageMeasurement"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>


                    <!-- CatItemMoldCavity -->
                    <div v-if="hasCatItemMoldCavity"
                         class="col-md-4"
                    >
                        <div
                            class="form-group">
                            <label class="control-label">
                                Cavidades del molde
                            </label>
                            <el-select v-model="form.extra.CatItemMoldCavity"
                                       :clearable="clearable"
                                       :multiple="multiple"

                            >
                                <el-option v-for="option in extra_CatItemMoldCavity"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>

                    <!-- CatItemProductFamily -->
                    <div v-if="hasCatItemProductFamily"
                         class="col-md-4"
                    >
                        <div
                            class="form-group">
                            <label class="control-label">
                                Familia de productos
                            </label>
                            <el-select v-model="form.extra.CatItemProductFamily"
                                       :clearable="clearable"
                                       :multiple="multiple"

                            >
                                <el-option v-for="option in extra_CatItemProductFamily"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>

                    <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Establecimiento</label>
                            <el-select v-model="form.establishment_id" clearable>
                                <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-md-3" >
                        <div class="form-group">
                            <label class="control-label">Tipo de documento</label>
                            <el-select v-model="form.document_type_id" clearable>
                                <el-option v-for="option in document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                        </div>
                    </div> -->

                    <div class="col-md-12">&nbsp;</div>
                    <div class="col-lg-7 col-md-7 col-md-7 col-sm-12"
                         style="margin-top:29px">
                        <el-button :loading="loading_submit"
                                   class="submit"
                                   icon="el-icon-search"
                                   type="primary"
                                   @click.prevent="getRecordsByFilter">Buscar
                        </el-button>

                        <template v-if="records.length>0">

                            <el-button class="submit"
                                       type="success"
                                       @click.prevent="clickDownload('excel')"><i class="fa fa-file-excel"></i> Exportal
                                                                                                                Excel
                            </el-button>

                        </template>

                    </div>

                </div>
                <div class="row mt-1 mb-4">

                </div>
            </div>


            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <slot name="heading"></slot>
                        </thead>
                        <tbody>
                        <slot v-for="(row, index) in records"
                              :index="customIndex(index)"
                              :row="row"></slot>
                        </tbody>
                    </table>
                    <div>
                        <el-pagination
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page"
                            :total="pagination.total"
                            layout="total, prev, pager, next"
                            @current-change="getRecords">
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<style>
.font-custom {
    font-size: 15px !important
}
</style>
<script>

import moment from 'moment'
import queryString from 'query-string'
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props: {
        resource: String,
        defaultType: String,
    },
    data() {
        return {
            multiple: false,
            clearable: true,
            loading_submit: false,
            showExtraData: {},
            extra: {},
            items: [],
            all_items_temp: [],
            all_items: [],
            loading_search: false,
            columns: [],
            records: [],
            headers: headers_token,
            document_types: [],
            pagination: {},
            search: {},
            totals: {},
            establishment: null,
            establishments: [],
            web_platforms: [],
            item: {},
            form: {
                extra: {
                    colors: null,
                    CatItemUnitsPerPackage: null,
                    CatItemMoldProperty: null,
                    CatItemUnitBusiness: null,
                    CatItemStatus: null,
                    CatItemPackageMeasurement: null,
                    CatItemMoldCavity: null,
                    CatItemProductFamily: null,
                    CatItemSize: null,
                },
            },
            pickerOptionsDates: {
                disabledDate: (time) => {
                    time = moment(time).format('YYYY-MM-DD')
                    return this.form.date_start > time
                }
            },
            pickerOptionsMonths: {
                disabledDate: (time) => {
                    time = moment(time).format('YYYY-MM')
                    return this.form.month_start > time
                }
            },
        }
    },
    computed: {
        ...mapState([
            'config',
            'hasGlobalIgv',
            'colors',
            'CatItemUnitsPerPackage',
            'CatItemMoldProperty',
            'CatItemUnitBusiness',
            'CatItemStatus',
            'CatItemPackageMeasurement',
            'CatItemMoldCavity',
            'CatItemProductFamily',
            'CatItemSize',
            'extra_colors',
            'extra_CatItemUnitsPerPackage',
            'extra_CatItemMoldProperty',
            'extra_CatItemSize',
            'extra_CatItemUnitBusiness',
            'extra_CatItemStatus',
            'extra_CatItemPackageMeasurement',
            'extra_CatItemMoldCavity',
            'extra_CatItemProductFamily',
        ]),
        canShowExtraData: function () {
            if (this.config && this.config.show_extra_info_to_item !== undefined) {
                return this.config.show_extra_info_to_item;
            }
            return false;
        },

        hasColors: function () {
            if (this.canShowExtraData == false) return false;
            return this.extra_colors.length > 0 && this.extra !== undefined && this.extra.colors !== undefined;
        },
        hasCatItemUnitsPerPackage: function () {
            if (this.canShowExtraData == false) return false;
            return this.extra_CatItemUnitsPerPackage.length > 0 && this.extra !== undefined && this.extra.CatItemUnitsPerPackage !== undefined;
        },
        hasCatItemMoldProperty: function () {
            if (this.canShowExtraData == false) return false;
            return this.extra_CatItemMoldProperty.length > 0 && this.extra !== undefined && this.extra.CatItemMoldProperty !== undefined;
        },
        hasCatItemUnitBusiness: function () {
            if (this.canShowExtraData == false) return false;
            return this.extra_CatItemUnitBusiness.length > 0 && this.extra !== undefined && this.extra.CatItemUnitBusiness !== undefined;
        },
        hasCatItemStatus: function () {
            if (this.canShowExtraData == false) return false;
            return this.extra_CatItemStatus.length > 0 && this.extra !== undefined && this.extra.CatItemStatus !== undefined;
        },
        hasCatItemPackageMeasurement: function () {
            if (this.canShowExtraData == false) return false;
            return this.extra_CatItemPackageMeasurement.length > 0 && this.extra !== undefined && this.extra.CatItemPackageMeasurement !== undefined;
        },
        hasCatItemMoldCavity: function () {
            return this.extra_CatItemMoldCavity.length > 0 && this.extra !== undefined && this.extra.CatItemMoldCavity !== undefined;
        },
        hasCatItemProductFamily: function () {
            if (this.canShowExtraData == false) return false;
            return this.extra_CatItemProductFamily.length > 0 && this.extra !== undefined && this.extra.CatItemProductFamily !== undefined;
        },
        hasCatItemSize: function () {
            if (this.canShowExtraData == false) return false;
            return this.extra_CatItemSize.length > 0 && this.extra !== undefined && this.extra.CatItemSize !== undefined;
        },


    },
    created() {
        this.loadConfiguration();
        this.initForm()
        this.initTotals()
        this.$eventHub.$on('reloadData', () => {
            this.getRecords()
        })
        if (this.defaultType !== undefined && this.defaultType !== null) {
            this.form.type = this.defaultType;
        }
        this.getExtraInfoOfItems();

    },
    async mounted() {

        await this.$http.get(`/${this.resource}/filter`)
            .then(response => {
                this.establishments = response.data.establishments;
                this.all_items = response.data.items
                this.document_types = response.data.document_types;
                this.web_platforms = response.data.web_platforms
            });


        await this.filterItems()

    },
    methods: {
        ...mapActions([
            'loadConfiguration',
            'clearExtraInfoItem',
        ]),

        setExtraElements() {
            this.clearExtraInfoItem()
            if (this.canShowExtraData) {
                let temp = [];
                this.colors.find(obj => {
                    for (var i = 0, iLen = this.extra.colors.length; i < iLen; i++) {
                        if (this.extra.colors[i].cat_colors_item_id === obj.id) {
                            temp.push(obj)
                        }
                    }
                });
                this.$store.commit('setExtraColors', temp)
                temp = [];
                this.CatItemUnitsPerPackage.find(obj => {
                    for (var i = 0, iLen = this.extra.CatItemUnitsPerPackage.length; i < iLen; i++) {
                        if (this.extra.CatItemUnitsPerPackage[i].cat_item_units_per_package_id === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemUnitsPerPackage', temp)
                temp = [];
                this.CatItemMoldProperty.find(obj => {
                    for (var i = 0, iLen = this.extra.CatItemMoldProperty.length; i < iLen; i++) {
                        if (this.extra.CatItemMoldProperty[i].cat_item_mold_properties_id === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemMoldProperty', temp)
                temp = [];
                this.CatItemUnitBusiness.find(obj => {
                    for (var i = 0, iLen = this.extra.CatItemUnitBusiness.length; i < iLen; i++) {
                        if (this.extra.CatItemUnitBusiness[i].cat_item_unit_business_id === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemUnitBusiness', temp)
                temp = [];
                this.CatItemStatus.find(obj => {
                    for (var i = 0, iLen = this.extra.CatItemStatus.length; i < iLen; i++) {
                        if (this.extra.CatItemStatus[i].cat_item_status_id === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemStatus', temp)
                temp = [];
                this.CatItemPackageMeasurement.find(obj => {
                    for (var i = 0, iLen = this.extra.CatItemPackageMeasurement.length; i < iLen; i++) {
                        if (this.extra.CatItemPackageMeasurement[i].cat_item_package_measurements_id === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemPackageMeasurement', temp)
                temp = [];
                this.CatItemMoldCavity.find(obj => {
                    for (var i = 0, iLen = this.extra.CatItemMoldCavity.length; i < iLen; i++) {
                        if (this.extra.CatItemMoldCavity[i].cat_item_mold_cavities_id === obj.id) {
                            temp.push(obj)
                        }
                    }
                })

                this.$store.commit('setExtraCatItemMoldCavity', temp)
                temp = [];
                this.CatItemProductFamily.find(obj => {
                    for (var i = 0, iLen = this.extra.CatItemProductFamily.length; i < iLen; i++) {
                        if (this.extra.CatItemProductFamily[i].cat_item_product_family_id === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemProductFamily', temp)
                temp = [];
                this.CatItemSize.find(obj => {
                    for (var i = 0, iLen = this.extra.CatItemSize.length; i < iLen; i++) {
                        if (this.extra.CatItemSize[i].cat_item_size_id === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemSize', temp)
                temp = [];

            }
        },
        getExtraInfoOfItems() {
            let url = '/extra_info/items';
            this.$http.post(url, {})
                .then((response) => {
                    let data = response.data
                    if (this.canShowExtraData) {
                        if (data.colors !== undefined)
                            this.$store.commit('setColors', data.colors);
                        if (data.CatItemUnitsPerPackage !== undefined)
                            this.$store.commit('setCatItemUnitsPerPackage', data.CatItemUnitsPerPackage);
                        if (data.CatItemStatus !== undefined)
                            this.$store.commit('setCatItemStatus', data.CatItemStatus);
                        if (data.CatItemMoldCavity !== undefined)
                            this.$store.commit('setCatItemMoldCavity', data.CatItemMoldCavity);
                        if (data.CatItemMoldProperty !== undefined)
                            this.$store.commit('setCatItemMoldProperty', data.CatItemMoldProperty);
                        if (data.CatItemUnitBusiness !== undefined)
                            this.$store.commit('setCatItemUnitBusiness', data.CatItemUnitBusiness);
                        if (data.CatItemPackageMeasurement !== undefined)
                            this.$store.commit('setCatItemPackageMeasurement', data.CatItemPackageMeasurement);
                        if (data.CatItemPackageMeasurement !== undefined)
                            this.$store.commit('setCatItemProductFamily', data.CatItemPackageMeasurement);
                        if (data.CatItemSize !== undefined)
                            this.$store.commit('setCatItemSize', data.CatItemSize);
                    }
                    this.$store.commit('setConfiguration', data.configuration);
                })
                .finally(() => {
                })
        },
        changePersons() {
            let item_id = this.form.item_id;
            let extra = {};
            console.log('Antes filtrado changePersons')

            this.all_items_temp.filter(item => {
                if (item.id == item_id) {
                    extra = item.extra;
                }

            })
            this.extra = extra;
            console.log('Luego filtrado changePersons')
            console.error(extra)

            this.setExtraElements()

            console.log('eto filtrado changePersons')
        },
        searchRemotePersons(input) {

            if (input.length > 0) {

                this.loading_search = true
                let parameters = `input=${input}`
                this.all_items_temp = []

                this.$http.get(`/reports/data-table/items/?${parameters}`)
                    .then(response => {
                        this.items = response.data.items
                        this.all_items_temp = response.data.items;
                        this.loading_search = false
                        if (this.items.length == 0) {
                            this.filterItems()
                        }
                    })
            } else {
                this.filterItems()
            }

        },
        filterItems() {
            this.items = this.all_items
        },
        clickDownload(type) {

            let form = this.form;
            delete(form.extra);
            let query = queryString.stringify({
                form
            });
            query += `&extra[colors]=${this.form.extra.colors}`
            query += `&extra[CatItemUnitsPerPackage]=${this.form.extra.CatItemUnitsPerPackage}`
            query += `&extra[CatItemMoldProperty]=${this.form.extra.CatItemMoldProperty}`
            query += `&extra[CatItemUnitBusiness]=${this.form.extra.CatItemUnitBusiness}`
            query += `&extra[CatItemStatus]=${this.form.extra.CatItemStatus}`
            query += `&extra[CatItemPackageMeasurement]=${this.form.extra.CatItemPackageMeasurement}`
            query += `&extra[CatItemMoldCavity]=${this.form.extra.CatItemMoldCavity}`
            query += `&extra[CatItemProductFamily]=${this.form.extra.CatItemProductFamily}`

            // let query = encodeURIComponent(JSON.stringify(...this.form))

            window.open(`/${this.resource}/${type}/?${query}`, '_blank');

        },
        initForm() {

            this.form = {
                establishment_id: null,
                item_id: null,
                document_type_id: null,
                period: 'month',
                web_platform_id: null,
                date_start: moment().format('YYYY-MM-DD'),
                date_end: moment().format('YYYY-MM-DD'),
                month_start: moment().format('YYYY-MM'),
                month_end: moment().format('YYYY-MM'),
                extra: {
                    colors: null,
                    CatItemUnitsPerPackage: null,
                    CatItemMoldProperty: null,
                    CatItemUnitBusiness: null,
                    CatItemStatus: null,
                    CatItemPackageMeasurement: null,
                    CatItemMoldCavity: null,
                    CatItemProductFamily: null,
                    CatItemSize: null,
                },
            }

        },
        initTotals() {

            this.totals = {
                acum_total_taxed: 0,
                acum_total_igv: 0,
                acum_total: 0,
                acum_total_exonerated: 0,
                acum_total_unaffected: 0,
                acum_total_free: 0,

                acum_total_taxed_usd: 0,
                acum_total_igv_usd: 0,
                acum_total_usd: 0,
            }
        },
        customIndex(index) {
            return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
        },
        async getRecordsByFilter() {

            if (!this.form.item_id) {
                return this.$message.error('Debe seleccionar un producto')
            }

            this.loading_submit = await true
            await this.getRecords()
            this.loading_submit = await false

        },
        getRecords() {
            // return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`)
            let form = this.form;
            return this.$http.post(`/${this.resource}/records`,form)
                .then((response) => {
                this.records = response.data.data
                this.pagination = response.data.meta
                this.pagination.per_page = parseInt(response.data.meta.per_page)
                this.loading_submit = false
                // this.initTotals()
                if (this.resource == 'reports/sales') this.getTotals(response.data.data)
            });


        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                ...this.form
            })
        },

        changeDisabledDates() {
            if (this.form.date_end < this.form.date_start) {
                this.form.date_end = this.form.date_start
            }
            // this.loadAll();
        },
        changeDisabledMonths() {
            if (this.form.month_end < this.form.month_start) {
                this.form.month_end = this.form.month_start
            }
            // this.loadAll();
        },
        changePeriod() {
            if (this.form.period === 'month') {
                this.form.month_start = moment().format('YYYY-MM');
                this.form.month_end = moment().format('YYYY-MM');
            }
            if (this.form.period === 'between_months') {
                this.form.month_start = moment().startOf('year').format('YYYY-MM'); //'2019-01';
                this.form.month_end = moment().endOf('year').format('YYYY-MM');

            }
            if (this.form.period === 'date') {
                this.form.date_start = moment().format('YYYY-MM-DD');
                this.form.date_end = moment().format('YYYY-MM-DD');
            }
            if (this.form.period === 'between_dates') {
                this.form.date_start = moment().startOf('month').format('YYYY-MM-DD');
                this.form.date_end = moment().endOf('month').format('YYYY-MM-DD');
            }
            // this.loadAll();
        },
    }
}
</script>
