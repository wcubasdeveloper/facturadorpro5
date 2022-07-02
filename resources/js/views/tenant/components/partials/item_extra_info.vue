<template>
    <div
        v-if="
    canShowExtraData && (
        hasCatItemSize ||
        hasColors ||
        hasCatItemUnitsPerPackage ||
        hasCatItemMoldProperty ||
        hasCatItemUnitBusiness ||
        hasCatItemStatus ||
        hasCatItemPackageMeasurement ||
        hasCatItemMoldCavity ||
        hasCatItemProductFamily)
    " class="col-md-12 mt-2">
        <el-collapse v-model="activePanel">
            <el-collapse-item name="1">
                <template slot="title">
                    + Datos informativos extra
                    <el-tooltip
                        :content="infoPop"
                        class="item"
                        effect="dark"
                        placement="top-start">
                        <i class="fa fa-info-circle"></i>
                    </el-tooltip>

                </template>
                <div class="col-12 row">

                    <div v-if="hasCatItemSize"
                         class="col-md-4"
                    >
                        <div
                            :class="{'has-danger': errors.CatItemSize}"
                            class="form-group">
                            <label class="control-label">
                                Tamaño

                            </label>
                            <el-select v-model="form.item.extra.CatItemSize"
                                       :clearable="clearable"
                                       :multiple="multiple"
                                       :placeholder="textplaceholder"
                            >
                                <el-option v-for="option in extra_CatItemSize"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.CatItemSize"
                                   class="form-control-feedback"
                                   v-text="errors.CatItemSize[0]"></small>
                        </div>
                    </div>

                    <!-- Colores -->
                    <div v-if="hasColors"
                         class="col-md-4">
                        <div
                            :class="{'has-danger': errors.colors}"
                            class="form-group">
                            <label class="control-label">Colores

                            </label>

                            <el-select v-model="form.item.extra.colors"
                                       :clearable="clearable"
                                       :multiple="multiple"
                                       :placeholder="textplaceholder"

                            >
                                <el-option v-for="option in extra_colors"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.colors"
                                   class="form-control-feedback"
                                   v-text="errors.colors[0]"></small>
                        </div>
                    </div>
                    <!-- CatItemUnitsPerPackage -->
                    <div v-if="hasCatItemUnitsPerPackage"
                         class="col-md-4"
                    >
                        <div
                            :class="{'has-danger': errors.CatItemUnitsPerPackage}"
                            class="form-group">
                            <label class="control-label">Cantidad de unidades por empaque

                            </label>
                            <el-select v-model="form.item.extra.CatItemUnitsPerPackage"
                                       :clearable="clearable"
                                       :multiple="multiple"
                                       :placeholder="textplaceholder"
                            >
                                <el-option v-for="option in extra_CatItemUnitsPerPackage"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.CatItemUnitsPerPackage"
                                   class="form-control-feedback"
                                   v-text="errors.CatItemUnitsPerPackage[0]"></small>
                        </div>
                    </div>
                    <!-- CatItemMoldProperty -->
                    <div v-if="hasCatItemMoldProperty"
                         class="col-md-4"
                    >
                        <div
                            :class="{'has-danger': errors.CatItemMoldProperty}"
                            class="form-group">
                            <label class="control-label">Propiedades del molde

                            </label>


                            <el-select v-model="form.item.extra.CatItemMoldProperty"
                                       :clearable="clearable"
                                       :multiple="multiple"
                                       :placeholder="textplaceholder"
                            >
                                <el-option v-for="option in extra_CatItemMoldProperty"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.CatItemMoldProperty"
                                   class="form-control-feedback"
                                   v-text="errors.CatItemMoldProperty[0]"></small>

                        </div>
                    </div>
                    <!-- CatItemUnitBusiness -->
                    <div v-if="hasCatItemUnitBusiness"
                         class="col-md-4">
                        <div
                            :class="{'has-danger': errors.CatItemUnitBusiness}"
                            class="form-group"
                        >
                            <label class="control-label">Unidades de negocio

                            </label>

                            <el-select v-model="form.item.extra.CatItemUnitBusiness"
                                       :clearable="clearable"
                                       :multiple="multiple"
                                       :placeholder="textplaceholder"
                            >
                                <el-option v-for="option in extra_CatItemUnitBusiness"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.CatItemUnitBusiness"
                                   class="form-control-feedback"
                                   v-text="errors.CatItemUnitBusiness[0]"></small>
                        </div>
                    </div>
                    <!-- CatItemStatus -->
                    <div v-if="hasCatItemStatus"
                         class="col-md-4"
                    >
                        <div
                            :class="{'has-danger': errors.CatItemStatus}"
                            class="form-group"
                        >
                            <label class="control-label">Status del item

                            </label>

                            <el-select v-model="form.item.extra.CatItemStatus"
                                       :clearable="clearable"
                                       :multiple="multiple"
                                       :placeholder="textplaceholder"
                            >
                                <el-option v-for="option in extra_CatItemStatus"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.CatItemStatus"
                                   class="form-control-feedback"
                                   v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <!-- CatItemPackageMeasurement -->
                    <div v-if="hasCatItemPackageMeasurement"
                         class="col-md-4"
                    >
                        <div
                            :class="{'has-danger': errors.CatItemPackageMeasurement}"
                            class="form-group">
                            <label class="control-label">Unidades de medida

                            </label>
                            <el-select v-model="form.item.extra.CatItemPackageMeasurement"
                                       :clearable="clearable"
                                       :multiple="multiple"
                                       :placeholder="textplaceholder"
                            >
                                <el-option v-for="option in extra_CatItemPackageMeasurement"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.CatItemPackageMeasurement"
                                   class="form-control-feedback"
                                   v-text="errors.CatItemPackageMeasurement[0]"></small>
                        </div>
                    </div>
                    <!-- CatItemMoldCavity -->
                    <div v-if="hasCatItemMoldCavity"
                         class="col-md-4"
                    >
                        <div
                            :class="{'has-danger': errors.CatItemMoldCavity}"
                            class="form-group">
                            <label class="control-label">Cavidades del molde

                            </label>

                            <el-select v-model="form.item.extra.CatItemMoldCavity"
                                       :clearable="clearable"
                                       :multiple="multiple"
                                       :placeholder="textplaceholder"
                            >
                                <el-option v-for="option in extra_CatItemMoldCavity"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.CatItemMoldCavity"
                                   class="form-control-feedback"
                                   v-text="errors.CatItemMoldCavity[0]"></small>
                        </div>
                    </div>
                    <!-- CatItemProductFamily -->
                    <div v-if="hasCatItemProductFamily"
                         class="col-md-4"
                    >
                        <div
                            :class="{'has-danger': errors.CatItemProductFamily}"
                            class="form-group">
                            <label class="control-label">Familia de productos

                            </label>

                            <el-select v-model="form.item.extra.CatItemProductFamily"
                                       :clearable="clearable"
                                       :multiple="multiple"
                                       :placeholder="textplaceholder"
                            >
                                <el-option v-for="option in extra_CatItemProductFamily"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.CatItemProductFamily"
                                   class="form-control-feedback"
                                   v-text="errors.CatItemProductFamily[0]"></small>


                        </div>

                    </div>

                    <!--
                    <el-button icon="el-icon-edit-outline"
                               size="small"
                               type="primary"
                               @click.prevent="setExtraElements">
                        Testing
                    </el-button>
                    -->
                </div>
            </el-collapse-item>
        </el-collapse>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
    props: [
        'form',
        'errors',

    ],
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

            'extra_colors',
            'extra_CatItemUnitsPerPackage',
            'extra_CatItemMoldProperty',
            'extra_CatItemUnitBusiness',
            'extra_CatItemStatus',
            'extra_CatItemPackageMeasurement',
            'extra_CatItemMoldCavity',
            'extra_CatItemSize',
            'extra_CatItemProductFamily',
            'config',
        ]),
        canShowExtraData: function () {
            if (this.config && this.config.show_extra_info_to_item !== undefined) {
                return this.config.show_extra_info_to_item;
            }
            return false;
        },
        hasColors: function () {
            return this.extra_colors.length > 0 && this.form.item.extra !== undefined && this.form.item.extra.colors !== undefined;
        },
        hasCatItemUnitsPerPackage: function () {
            return this.extra_CatItemUnitsPerPackage.length > 0 && this.form.item.extra !== undefined && this.form.item.extra.CatItemUnitsPerPackage !== undefined;
        },
        hasCatItemMoldProperty: function () {
            return this.extra_CatItemMoldProperty.length > 0 && this.form.item.extra !== undefined && this.form.item.extra.CatItemMoldProperty !== undefined;
        },
        hasCatItemUnitBusiness: function () {
            return this.extra_CatItemUnitBusiness.length > 0 && this.form.item.extra !== undefined && this.form.item.extra.CatItemUnitBusiness !== undefined;
        },
        hasCatItemStatus: function () {
            return this.extra_CatItemStatus.length > 0 && this.form.item.extra !== undefined && this.form.item.extra.CatItemStatus !== undefined;
        },
        hasCatItemPackageMeasurement: function () {
            return this.extra_CatItemPackageMeasurement.length > 0 && this.form.item.extra !== undefined && this.form.item.extra.CatItemPackageMeasurement !== undefined;
        },
        hasCatItemMoldCavity: function () {
            return this.extra_CatItemMoldCavity.length > 0 && this.form.item.extra !== undefined && this.form.item.extra.CatItemMoldCavity !== undefined;
        },
        hasCatItemProductFamily: function () {
            return this.extra_CatItemProductFamily.length > 0 && this.form.item.extra !== undefined && this.form.item.extra.CatItemProductFamily !== undefined;
        },
        hasCatItemSize: function () {
            return this.extra_CatItemSize.length > 0 && this.form.item.extra !== undefined && this.form.item.extra.CatItemSize !== undefined;
        },
    },
    data() {
        return {
            item: {},
            multiple: false,
            clearable: true,
            textplaceholder: 'Ninguno seleccionado',
            activePanel: 0,
            infoPop: 'No será enviado a SUNAT',

        }
    },
    created() {
        this.item = {};
        this.loadConfiguration()

    },
    mounted() {
        this.item = {};
        if (
            this.form !== undefined &&
            this.form.item !== undefined
        ) {
            this.item = this.form.item;
        }
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        /*
        setExtraElements() {
            let opo = this.form.item;
            let temp = undefined;
            temp = this.colors.find(obj => {
                for (var i = 0, iLen = opo.colors.length; i < iLen; i++) {
                    if (opo.colors[i] === obj.id) {
                        return obj;
                    }
                }
            })
            this.$store.commit('setExtraColors',temp)
            temp = this.CatItemUnitsPerPackage.find(obj => {
                for (var i = 0, iLen = opo.CatItemUnitsPerPackage.length; i < iLen; i++) {
                    if (opo.CatItemUnitsPerPackage[i] === obj.id) {
                        return obj
                    }
                }
            })
            this.$store.commit('setExtraCatItemUnitsPerPackage',temp)
            temp =  this.CatItemMoldProperty.find(obj => {
                for (var i = 0, iLen = opo.CatItemMoldProperty.length; i < iLen; i++) {
                    if (opo.CatItemMoldProperty[i] === obj.id) {
                        return obj
                    }
                }
            })
            this.$store.commit('setExtraCatItemMoldProperty',temp)
            temp =  this.CatItemUnitBusiness.find(obj => {
                for (var i = 0, iLen = opo.CatItemUnitBusiness.length; i < iLen; i++) {
                    if (opo.CatItemUnitBusiness[i] === obj.id) {
                        return obj
                    }
                }
            })
            this.$store.commit('setExtraCatItemUnitBusiness',temp)
            temp =   this.CatItemStatus.find(obj => {
                for (var i = 0, iLen = opo.CatItemStatus.length; i < iLen; i++) {
                    if (opo.CatItemStatus[i] === obj.id) {
                        return obj
                    }
                }
            })
            this.$store.commit('setExtraCatItemStatus',temp)
            temp =  this.CatItemPackageMeasurement.find(obj => {
                for (var i = 0, iLen = opo.CatItemPackageMeasurement.length; i < iLen; i++) {
                    if (opo.CatItemPackageMeasurement[i] === obj.id) {
                        return obj
                    }
                }
            })
            this.$store.commit('setExtraCatItemPackageMeasurement',temp)
            temp =  this.CatItemMoldCavity.find(obj => {
                for (var i = 0, iLen = opo.CatItemMoldCavity.length; i < iLen; i++) {
                    if (opo.CatItemMoldCavity[i] === obj.id) {
                        return obj
                    }
                }
            })

            this.$store.commit('setExtraCatItemMoldCavity',temp)

            temp =  this.CatItemProductFamily.find(obj => {
                for (var i = 0, iLen = opo.CatItemProductFamily.length; i < iLen; i++) {
                    if (opo.CatItemProductFamily[i] === obj.id) {
                        return obj
                    }
                }
            })


            this.$store.commit('setExtraCatItemProductFamily',temp)




            temp =  this.CatItemMoldProperty.find(obj => {
                for (var i = 0, iLen = opo.CatItemMoldProperty.length; i < iLen; i++) {
                    if (opo.CatItemMoldProperty[i] === obj.id) {
                        return obj
                    }
                }
            })
            this.$store.commit('setExtraCatItemMoldProperty',temp)

        }
        */

    }
}
</script>
