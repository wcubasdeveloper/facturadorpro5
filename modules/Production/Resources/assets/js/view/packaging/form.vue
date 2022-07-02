<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">
                Zona de embalaje
            </h3>
        </div>
        <div class="tab-content">
            <form autocomplete="off"
                  @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">
                        <!--
                        user_id: null,
                        item:{},
                        -->
                        <!--item_id-->
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.item_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Embalaje
                                </label>
                                <el-select
                                    v-model="form.item_id"
                                    :loading="loading_search"
                                    :remote-method="searchRemoteItems"
                                    filterable
                                    remote
                                    @change="changeItem"
                                    id="select-width"
                                    ref="selectSearchNormal"
                                    slot="prepend"
                                    placeholder="Buscar"
                                    popper-class="el-select-items"
                                    :tabindex="'1'"
                                >

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
                                <small
                                    v-if="errors.item_id"
                                    class="form-control-feedback"
                                    v-text="errors.item_id[0]"
                                ></small>
                            </div>
                        </div>

                        <!--name-->
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.name}"
                                 class="form-group">
                                <label class="control-label">Número de Ficha</label>
                                <el-input v-model="form.name"></el-input>
                                <small v-if="errors.name"
                                       class="form-control-feedback"
                                       v-text="errors.name[0]"></small>
                            </div>
                        </div>

                        <!--                        quantity-->
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.quantity}"
                                 class="form-group">
                                <label class="control-label">Cantidad</label>
                                <el-input-number
                                    v-model="form.quantity"
                                    :controls="false"
                                    :min="0"
                                    :precision="precision"
                                ></el-input-number>
                                <small
                                    v-if="errors.quantity"
                                    class="form-control-feedback"
                                    v-text="errors.quantity[0]"
                                ></small>
                            </div>
                        </div>
                        <!--                        number_packages-->
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.number_packages}"
                                 class="form-group">
                                <label class="control-label">Cantidad de paquetes</label>
                                <el-input-number
                                    v-model="form.number_packages"
                                    :controls="false"
                                    :min="0"
                                    :precision="precision"
                                ></el-input-number>
                                <small
                                    v-if="errors.number_packages"
                                    class="form-control-feedback"
                                    v-text="errors.number_packages[0]"
                                ></small>
                            </div>
                        </div>
                        <!--                        lot_code-->
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.lot_code}"
                                 class="form-group">
                                <label class="control-label">
                                    Lote
                                </label>
                                <input
                                    v-model="form.lot_code"
                                    class="form-control"
                                    placeholder="Lote"
                                    type="text"
                                />

                                <small v-if="errors.lot_code"
                                       class="form-control-feedback"
                                       v-text="errors.lot_code[0]"></small>
                            </div>
                        </div>

                        <!--                        establishment_id-->
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div
                                :class="{'has-danger': errors.establishment_id}"
                                class="form-group"
                            >
                                <label class="control-label">Almacén</label>
                                <el-select v-model="form.establishment_id"
                                           filterable>
                                    <el-option
                                        v-for="option in establishments"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.establishment_id"
                                    class="form-control-feedback"
                                    v-text="errors.establishment_id[0]"
                                ></small>
                            </div>
                        </div>


                        <!--item_extra_data-->
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.item_extra_data}"
                                 class="form-group">
                                <label class="control-label">
                                    Color
                                </label>
                                <el-select v-model="form.item_extra_data.color"
                                           filterable>
                                    <el-option
                                        v-for="option in color"

                                        :key="option.id"
                                        :label="option.name"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small v-if="errors.item_extra_data"
                                       class="form-control-feedback"
                                       v-text="errors.item_extra_data[0]"></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">

                        <div
                                :class="{'has-danger': errors.packaging_collaborator}"
                                class="form-group"
                            >
                                <label class="control-label">Colaborador</label>
                                <input
                                    class="form-control"
                                    v-model="form.packaging_collaborator"
                                    type="text"
                                    value="Colaborador"
                                />
                            </div>
                        </div>

                        <!-- observation -->
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div :class="{'has-danger': errors.observation}"
                                 class="form-group">
                                <label class="control-label">Comentario</label>
                                <el-input v-model="form.observation"></el-input>
                                <small v-if="errors.observation"
                                       class="form-control-feedback"
                                       v-text="errors.observation[0]"></small>
                            </div>
                        </div>

                        <!--                        date_start
                                                time_start-->
                        <div class="col-sm-12 col-md-6 ">
                            <div class="row">


                                <div class="col-6">
                                    <div :class="{'has-danger': errors.date_start}"
                                         class="form-group">
                                        <label class="control-label">
                                            Fecha de inicio
                                        </label>
                                        <el-date-picker v-model="form.date_start"
                                                        :clearable="false"
                                                        format="dd/MM/yyyy"
                                                        type="date"
                                                        value-format="yyyy-MM-dd"></el-date-picker>
                                        <small v-if="errors.date_start"
                                               class="form-control-feedback"
                                               v-text="errors.date_start[0]"></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div :class="{'has-danger': errors.time_start}"
                                         class="form-group">
                                        <label class="control-label">Hora de Inicio</label>
                                        <el-time-picker v-model="form.time_start"
                                                        dusk="time_start"
                                                        placeholder="Seleccionar"
                                                        value-format="HH:mm:ss"></el-time-picker>
                                        <small v-if="errors.time_start"
                                               class="form-control-feedback"
                                               v-text="errors.time_start[0]"></small>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!--                        date_end
                                                time_end-->
                        <div class="col-sm-12 col-md-6 ">
                            <div class="row">
                                <div class="col-6">
                                    <div :class="{'has-danger': errors.date_end}"
                                         class="form-group">
                                        <label class="control-label">
                                            Fecha de Finalización
                                        </label>
                                        <el-date-picker v-model="form.date_end"
                                                        :clearable="false"
                                                        format="dd/MM/yyyy"
                                                        type="date"
                                                        value-format="yyyy-MM-dd"></el-date-picker>
                                        <small v-if="errors.date_end"
                                               class="form-control-feedback"
                                               v-text="errors.date_end[0]"></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div :class="{'has-danger': errors.time_end}"
                                         class="form-group">
                                        <label class="control-label">Hora de finalización</label>
                                        <el-time-picker v-model="form.time_end"
                                                        dusk="time_end"
                                                        placeholder="Seleccionar"
                                                        value-format="HH:mm:ss"></el-time-picker>
                                        <small v-if="errors.time_end"
                                               class="form-control-feedback"
                                               v-text="errors.time_end[0]"></small>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>

                </div>

                <div class="form-actions text-right mt-4">
                    <el-button
                        :loading="loading_submit"
                        native-type="submit"
                        type="primary"
                    >Guardar
                    </el-button>
                </div>


            </form>
        </div>
    </div>
</template>

<script>


import {ItemOptionDescription, ItemSlotTooltip} from "../../../../../../../resources/js/helpers/modal_item";
import {mapState} from "vuex/dist/vuex.mjs";

export default {

    prop: ['id'],
    computed: {
        ...mapState([
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
            'deb',
            'config',
        ]),
    },
    data() {
        return {
            resource: 'packaging',
            loading_submit: false,
            errors: {},
            item: {},
            color:[],
            supplies: {},
            form: {

                establishment_id: null,
                user_id: null,
                item_id: null,
                quantity: 0,
                number_packages: 0,
                observation: null,
                lot_code: null,
                item_extra_data: {
                    color: null
                },

                item: {},
                date_start: null,
                time_start: null,
                date_end: null,
                time_end: null,

            },
            loading_search: false,
            warehouses: [],
            precision: 2,
            establishments: [],
            items: [],
            machines: [],
        }
    },
    created() {

        this.getTable();
        this.initForm()
    },
    methods: {
        initForm() {
            this.form = {
                id: this.id,
                establishment_id: null,
                user_id: null,
                item_id: null,
                quantity: 0,
                number_packages: 0,
                observation: null,
                lot_code: null,
                item_extra_data: {
                    color: null
                },

                item: {},
                date_start: null,
                time_start: null,
                date_end: null,
                time_end: null,

            }
            this.supplies = {};

        },
        getTable() {
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    let data = response.data
                    this.items = data.items
                    this.$store.commit('setColors', data.colors);
                    this.establishments = data.establishments

                })


        },
        async searchRemoteItems(search) {
            this.loading_search = true;
            this.items = [];
            await this.$http.post(`/${this.resource}/search_items`, {'input': search})
                .then(response => {
                    this.items = response.data.items
                })
            this.loading_search = false;
        },
        async submit() {

            if (this.form.quantity < 1) {
                return this.$message.error('La cantidad debe ser mayor a 0');
            }

            this.loading_submit = true

            this.form.supplies = this.supplies
            await this.$http.post(`/${this.resource}/create`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.initForm()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        console.log(error)
                    }
                })
                .finally(() => {
                    this.loading_submit = false
                })
        },
        changeItem() {
            let item = _.find(this.items, {'id': this.form.item_id})
            this.form.item_extra_data = {}
            this.form.item_extra_data.color = null
            this.item = item
            this.color = item.color
        },



        ItemSlotTooltipView(item) {
            return ItemSlotTooltip(item);
        },
        ItemOptionDescriptionView(item) {
            return ItemOptionDescription(item)
        },

    }
}
</script>
