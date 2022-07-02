<template>
    <el-dialog :title="titleDialog"
               :visible="showDialog"
               class="dialog-import"
               @close="close"
               @open="create">
        <form autocomplete="off"
              @submit.prevent="submit">
            <div class="form-body">
                <div class="row">

                    <div class="col-md-12 text-justify">
                        <ul>
                            <li>
                                Se debe tener en cuenta que el separador de atributos es una pleca o barra vertical "|". No hay limite en la cantidad de atributos que pueda tener.
                            </li>
                            <li>
                                Es necesario tambien que los atributos existan en el sistema para la categoria
                                correspondiente.
                            </li>
                            <li>
                                El Código Interno del producto es requerido.
                            </li>
                            <!-- @todo Cambiarlo a documentacion -->
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <a href="/formats/item_with_extra_data.xlsx"
                           target="_new">Descargar formato</a>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div :class="{'has-danger': errors.file}"
                             class="form-group text-center">
                            <el-upload
                                ref="upload"
                                :auto-upload="false"
                                :headers="headers"
                                :limit="1"
                                :multiple="false"
                                :on-error="errorUpload"
                                :on-success="successUpload"
                                :show-file-list="true"
                                action="/items/import/item-with-extra-data">
                                <el-button slot="trigger"
                                           type="primary">
                                    Seleccione un archivo (xlsx)
                                </el-button>
                            </el-upload>
                            <small v-if="errors.file"
                                   class="form-control-feedback"
                                   v-text="errors.file[0]"></small>
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button :loading="loading_submit"
                           native-type="submit"
                           type="primary">Procesar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

import {mapActions, mapState} from "vuex";

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
            errors: {},
            form: {},
        }
    },
    created() {
        this.initForm()
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        initForm() {
            this.errors = {}
            this.form = {
                file: null,
            }
        },
        create() {
            this.titleDialog = 'Importar listado de atributos para producto'
        },
        async submit() {
            this.loading_submit = true
            await this.$refs.upload.submit()
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
        errorUpload(response) {
            console.log(response)
        }
    },
    computed: {
        ...mapState([
            'config',
        ]),
    }
}
</script>
