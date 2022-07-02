<template>
    <el-dialog :visible="showDialog"
               class="dialog-import"
               title="Atributos Extra"
               @close="close">
        <form autocomplete="off"
              @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-12">
                        <div
                             :class="{'has-danger': errors.fields}"
                             class="form-group">
                            <label class="control-label">Seleccione el Atributo a exportar </label>
                            <el-select v-model="form.fields">
                                <el-option v-if="colors.length > 0"
                                           key="colors"
                                           label="Colores"
                                           value="colors"></el-option>
                                <el-option v-if="CatItemMoldProperty.length > 0"
                                           key="CatItemMoldProperty"
                                           label="Propiedades del molde"
                                           value="CatItemMoldProperty"></el-option>
                                <el-option v-if="CatItemUnitBusiness.length > 0"
                                           key="CatItemUnitBusiness"
                                           label="Unidad de negocio"
                                           value="CatItemUnitBusiness"></el-option>
                                <el-option v-if="CatItemStatus.length > 0"
                                           key="CatItemStatus"
                                           label="Status de item"
                                           value="CatItemStatus"></el-option>
                                <el-option v-if="CatItemPackageMeasurement.length > 0"
                                           key="CatItemPackageMeasurement"
                                           label="Unidades de medida"
                                           value="CatItemPackageMeasurement"></el-option>
                                <el-option v-if="CatItemProductFamily.length > 0"
                                           key="CatItemProductFamily"
                                           label="Familia de productos"
                                           value="CatItemProductFamily"></el-option>
                                <el-option v-if="CatItemSize.length > 0"
                                           key="CatItemSize"
                                           label="Tamaño/Talla"
                                           value="CatItemSize"></el-option>
                                <el-option v-if="CatItemUnitsPerPackage.length > 0"
                                           key="CatItemUnitsPerPackage"
                                           label="Unidades por paquete"
                                           value="CatItemUnitsPerPackage"></el-option>
                                <el-option v-if="CatItemMoldCavity.length > 0"
                                           key="CatItemMoldCavity"
                                           label="Cavidades de molde"
                                           value="CatItemMoldCavity"></el-option>
                            </el-select>
                            <small v-if="errors.fields"
                                   class="form-control-feedback"
                                   v-text="errors.fields[0]"></small>
                        </div>

                    </div>
                    <!--
                    <div class="col-12 text-center mt-5">


                        <el-button class="submit" type="danger"  icon="el-icon-tickets" @click.prevent="clickDownload('PDF')" >Exportar PDF</el-button>
                        <el-button class="submit" type="success" @click.prevent="clickDownload('XLSX')"><i class="fa fa-file-excel" ></i>  Exportal Excel</el-button>
                    </div>
                    -->


                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button :loading="loading_submit"
                               :disabled="form.fields === null"
                               native-type="submit"
                               type="primary">Procesar
                    </el-button>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import queryString from 'query-string'
import {mapActions, mapState} from "vuex";

export default {
    props: [
        'showDialog',
        'pharmacy',
    ],
    data() {
        return {
            loading_submit: false,
            headers: headers_token,
            resource: 'items',
            errors: {},
            form: {
                fields:null
            },
            max_item: 1,
            type: 0,
            fromPharmacy: false,
            types: [
                {'id': 0, 'description': 'Normal'},
                {'id': 1, 'description': 'Impresión 5cm x 2.5cm'},
            ],
        }
    },
    created() {
        if (this.pharmacy !== undefined && this.pharmacy === true) {
            this.fromPharmacy = true;
        }
        this.initForm()
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        initForm() {
            this.fields = null;
            this.errors = {}
        },
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        submit() {
            this.clickDownload('XLSX');
            /*


            this.loading_submit = true

            let query = queryString.stringify({
                isPharmacy: this.fromPharmacy,
                ...this.form
            });
                window.open(`/${this.resource}/export/extra_atrributes/?${query}`, '_blank');
            this.loading_submit = false
            this.$emit('update:showDialog', false)
            this.initForm()
            */
        },
        clickDownload(type) {
            this.loading_submit = true;
            let query = queryString.stringify({
                ...this.form
            });
            let url = `/${this.resource}/export/extra_atrributes/PDF?${query}`;
            if(type === 'XLSX'){
                url = `/${this.resource}/export/extra_atrributes/XLSX?${query}`;
            }
            window.open(url, '_blank');
            this.loading_submit = false;
            this.$emit('update:showDialog', false)
            this.initForm()
        }
    },

    computed: {
        ...mapState([
            'config',
            'colors',
            'CatItemMoldProperty',
            'CatItemUnitBusiness',
            'CatItemStatus',
            'CatItemPackageMeasurement',
            'CatItemProductFamily',
            'CatItemSize',
            'CatItemUnitsPerPackage',
            'CatItemMoldCavity',
        ]),
    },

}
</script>
