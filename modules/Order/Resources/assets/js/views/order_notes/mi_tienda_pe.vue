<template>
    <el-dialog
        :title="titleDialog"
        :visible="showDialog"
        class="dialog-import"
        @close="close"
        @open="create">
        <form
            autocomplete="off"
            @submit.prevent="submit">
            <div
                class="form-body">
                <div
                    class="row">
                    <div
                        :class="{'has-danger': errors.file}"
                        class="col-12 form-group">
                        <el-upload
                            ref="upload"
                            :auto-upload="false"
                            :before-upload="onBeforeUpload"
                            :data="form"
                            :headers="headers"
                            :limit="1"
                            :multiple="false"
                            :on-error="errorUpload"
                            :on-success="successUpload"
                            :show-file-list="true"
                            action="/order-notes/import/MiTiendaPe">
                            <el-button slot="trigger"
                                       type="primary">Seleccione un archivo (xlsx)

                            </el-button>
                        </el-upload>
                        <small
                            v-if="errors.file"
                            class="form-control-feedback"
                            v-text="errors.file[0]"></small>
                    </div>
                    <div
                        class="col-12 mt-4 mb-2 text-center">
                        <a
                            class="text-dark mr-auto"
                            href="/formats/mi_tienda_pe.xlsx"
                            target="_new">
                            <span
                                class="mr-2">
                                Descargar formato de ejemplo para importar
                            </span>
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                    <div class="col-12">
                        <p>
                            Recomendaciones:
                        </p>
                        <ul>
                            <li>
                                El codigo interno debe corresponder
                            </li>
                            <li>
                                Solo se evaluará Boleta o Factura,
                            </li>
                            <li>
                                El proceso puede demorar
                            </li>
                            <li>
                                La cantidad de productos debe tomarse en cuenta, ya que la respuesta de sunat podra
                                hacer que la carga falle.
                            </li>
                        </ul>
                    </div>
                    <div v-if="loading_submit"
                         class="col-12 text-center">
                        {{ message }}
                    </div>
                    <div class="col-12">
                        <p v-if="  mi_tienda_pe.establishment_id==null">
                            Debes ajustar el establecimiento predefinido
                        </p>
                        <p v-if="  mi_tienda_pe.series_document_ft_id==null">
                            Debes ajustar la serie por defecto para factura
                        </p>
                        <p v-if="  mi_tienda_pe.series_document_bt_id==null">
                            Debes ajustar la serie por defecto para Boleta
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="form-actions text-right mt-5">
                <el-button
                    :disabled="loading_submit"
                    @click.prevent="close()">
                    Cancelar
                </el-button>
                <el-button
                    v-if="
                    mi_tienda_pe.establishment_id!==null &&
        mi_tienda_pe.series_document_ft_id!==null &&
        mi_tienda_pe.series_document_bt_id!==null
"
                    :loading="loading_submit"
                    native-type="submit"
                    type="primary">
                    Procesar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props: [
        'showDialog'
    ],
    data() {
        return {
            loading_submit: false,
            headers: headers_token,
            titleDialog: null,
            resource: 'items',
            message: '',
            errors: {},
            form: {},
            warehouses: []
        }
    },

    created() {
        this.loadConfiguration();
        this.initForm();

        // await this.onFetchTables();
    },
    computed: {
        ...mapState([
            'config',
            'mi_tienda_pe',

        ]),
    },

    methods: {

        ...mapActions([
            'loadConfiguration',
        ]),
        onBeforeUpload(file) {
        },
        async onFetchTables() {
            this.loading_submit = true;
            await this.$http.get('/items/import/tables').then(response => {
                this.warehouses = response.data.warehouses;
            }).finally(() => this.loading_submit = false);

        },
        initForm() {
            this.errors = {}
            this.form = {}
        },
        create() {
            this.titleDialog = 'Importar pedidos desde mi MiTienda.Pe'
        },
        async submit() {
            this.loading_submit = true
            this.message = 'Iniciando el envio'
            await this.$refs.upload.submit()
            this.message = 'Envio finalizado'
            this.loading_submit = false
        },
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        successUpload(response, file, fileList) {
            if (response.success) {
                this.$message.success(response.message)
                this.$eventHub.$emit('reloadData')
                this.$eventHub.$emit('reloadTables')
                this.$refs.upload.clearFiles()
                this.close()
            } else {
                this.$message({message: response.message, type: 'error'})
            }
        },
        errorUpload(error) {
            console.log(error)
        }
    }
}
</script>
