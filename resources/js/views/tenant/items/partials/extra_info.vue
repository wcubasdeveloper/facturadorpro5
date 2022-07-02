<template>
    <div class="row" v-if="canShowExtraData">

        <!-- Tamaño -->

        <div class="col-md-4">
            <div v-if="CatItemSize.length > 0"
                 :class="{'has-danger': errors.name}"
                 class="form-group">
                <label class="control-label">Tamaños</label>
                <el-select v-model="form.CatItemSize"
                           :multiple="true"
                >
                    <el-option v-for="option in CatItemSize"
                               :key="option.id"
                               :label="option.name"
                               :value="option.id"></el-option>
                </el-select>
                <small v-if="errors.name"
                       class="form-control-feedback"
                       v-text="errors.name[0]"></small>
            </div>
            <div v-else
                 class="row col-12">
                No hay Tamaños, puedes gestionarlos en
                <a :href="`./extra_info_items/item-size`">Pagina de propiedades</a>

            </div>
        </div>
        <!-- Colores -->
        <div class="col-md-4">
            <div v-if="colors.length > 0"
                 :class="{'has-danger': errors.name}"
                 class="form-group">
                <label class="control-label">Colores</label>

                <el-select v-model="form.colors"
                           :multiple="true"

                >
                    <el-option v-for="option in colors"
                               :key="option.id"
                               :label="option.name"
                               :value="option.id"></el-option>
                </el-select>
                <small v-if="errors.name"
                       class="form-control-feedback"
                       v-text="errors.name[0]"></small>
            </div>
            <div v-else
                 class="row col-12">
                No hay colores, puedes gestionarlos en <a :href="`./extra_info_items/colors`">Pagina de propiedades</a>
            </div>
        </div>
        <!-- CatItemUnitsPerPackage -->
        <div class="col-md-4">
            <div v-if="CatItemUnitsPerPackage.length > 0"
                 :class="{'has-danger': errors.name}"
                 class="form-group">
                <label class="control-label">Cantidad de unidades por empaque</label>
                <el-select v-model="form.CatItemUnitsPerPackage"
                           :multiple="true"
                >
                    <el-option v-for="option in CatItemUnitsPerPackage"
                               :key="option.id"
                               :label="option.name"
                               :value="option.id"></el-option>
                </el-select>
                <small v-if="errors.name"
                       class="form-control-feedback"
                       v-text="errors.name[0]"></small>
            </div>
            <div v-else
                 class="row col-12">
                No hay Unidades por empaque, puedes gestionarlos en
                <a :href="`./extra_info_items/item-units-per-package`">Pagina de propiedades</a>

            </div>
        </div>
        <!-- CatItemMoldProperty -->
        <div class="col-md-4">
            <div v-if="CatItemMoldProperty.length > 0"
                 :class="{'has-danger': errors.name}"
                 class="form-group">
                <label class="control-label">Propiedades del molde</label>


                <el-select v-model="form.CatItemMoldProperty"
                           :multiple="true"
                >
                    <el-option v-for="option in CatItemMoldProperty"
                               :key="option.id"
                               :label="option.name"
                               :value="option.id"></el-option>
                </el-select>
                <small v-if="errors.name"
                       class="form-control-feedback"
                       v-text="errors.name[0]"></small>

            </div>
            <div v-else
                 class="row col-12">
                No hay propiedades de molde, puedes gestionarlos en
                <a :href="`./extra_info_items/item-mold-property`">Pagina de propiedades</a>
            </div>
        </div>
        <!-- CatItemUnitBusiness -->
        <div class="col-md-4">
            <div v-if="CatItemUnitBusiness.length > 0"
                 :class="{'has-danger': errors.name}"
                 class="form-group"
            >
                <label class="control-label">Unidades de negocio</label>

                <el-select v-model="form.CatItemUnitBusiness"
                           :multiple="true"
                >
                    <el-option v-for="option in CatItemUnitBusiness"
                               :key="option.id"
                               :label="option.name"
                               :value="option.id"></el-option>
                </el-select>
                <small v-if="errors.name"
                       class="form-control-feedback"
                       v-text="errors.name[0]"></small>
            </div>

            <div v-else
                 class="row col-12">
                No hay Unidades de negocio, puedes gestionarlos en
                <a :href="`./extra_info_items/item-units-business`">Pagina de propiedades</a>
            </div>
        </div>
        <!-- CatItemStatus -->
        <div class="col-md-4">
            <div v-if="CatItemStatus.length > 0"
                 :class="{'has-danger': errors.name}"
                 class="form-group"
            >
                <label class="control-label">Status del item</label>

                <el-select v-model="form.CatItemStatus"
                           :multiple="true"
                >
                    <el-option v-for="option in CatItemStatus"
                               :key="option.id"
                               :label="option.name"
                               :value="option.id"></el-option>
                </el-select>
                <small v-if="errors.name"
                       class="form-control-feedback"
                       v-text="errors.name[0]"></small>
            </div>

            <div v-else
                 class="row col-12">
                No hay Status, puedes gestionarlos en
                <a :href="`./extra_info_items/item-status`">Pagina de propiedades</a>
            </div>
        </div>
        <!-- CatItemPackageMeasurement -->
        <div class="col-md-4">
            <div v-if="CatItemPackageMeasurement.length > 0"
                 :class="{'has-danger': errors.name}"
                 class="form-group">
                <label class="control-label">Unidades de medida</label>
                <el-select v-model="form.CatItemPackageMeasurement"
                           :multiple="true"
                >
                    <el-option v-for="option in CatItemPackageMeasurement"
                               :key="option.id"
                               :label="option.name"
                               :value="option.id"></el-option>
                </el-select>
                <small v-if="errors.name"
                       class="form-control-feedback"
                       v-text="errors.name[0]"></small>
            </div>

            <div v-else
                 class="row col-12">
                No hay Unidades de medida, puedes gestionarlos en
                <a :href="`./extra_info_items/item-package-measurement`">Pagina de propiedades</a>
            </div>
        </div>
        <!-- CatItemMoldCavity -->
        <div class="col-md-4">
            <div v-if="CatItemMoldCavity.length > 0"
                 :class="{'has-danger': errors.name}"
                 class="form-group">
                <label class="control-label">Cavidades del molde</label>

                <el-select v-model="form.CatItemMoldCavity"
                           :multiple="true"
                >
                    <el-option v-for="option in CatItemMoldCavity"
                               :key="option.id"
                               :label="option.name"
                               :value="option.id"></el-option>
                </el-select>
                <small v-if="errors.name"
                       class="form-control-feedback"
                       v-text="errors.name[0]"></small>
            </div>

            <div v-else
                 class="row col-12">
                No hay Cavidades del molde, puedes gestionarlos en
                <a :href="`./extra_info_items/item-mold-cavities`">Pagina de propiedades</a>
            </div>
        </div>
        <!-- CatItemProductFamily -->
        <div class="col-md-4">
            <div v-if="CatItemProductFamily.length > 0"
                 :class="{'has-danger': errors.name}"
                 class="form-group">
                <label class="control-label">Familia de productos</label>

                <el-select v-model="form.CatItemProductFamily"
                           :multiple="true"
                >
                    <el-option v-for="option in CatItemProductFamily"
                               :key="option.id"
                               :label="option.name"
                               :value="option.id"></el-option>
                </el-select>
                <small v-if="errors.name"
                       class="form-control-feedback"
                       v-text="errors.name[0]"></small>


            </div>
            <div v-else
                 class="row col-12">
                No hay Familia de productos, puedes gestionarlos en
                <a :href="`./extra_info_items/item-prodcut-family`">Pagina de propiedades</a>
            </div>

        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";


export default {
    props: [
        'form'
    ],
    computed: {
        ...mapState([
            'colors',


            'CatItemSize',
            'CatItemUnitsPerPackage',
            'CatItemMoldProperty',
            'CatItemUnitBusiness',
            'CatItemStatus',
            'CatItemPackageMeasurement',
            'CatItemMoldCavity',
            'CatItemProductFamily',
            'config',
        ]),
        canShowExtraData: function () {
            if (this.config && this.config.show_extra_info_to_item !== undefined) {
                return this.config.show_extra_info_to_item;
            }
            return false;
        },
    },
    data() {
        return {
            errors: {}
        }
    },
    created() {
        this.loadConfiguration();
    },
    mounted() {
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        close() {
            this.$emit('update:form', this.form)
        },
    }
}
</script>
