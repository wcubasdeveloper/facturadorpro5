<template>
    <el-dialog :title="titleDialog" :visible="showDialog" class="dialog-import" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div :class="{'has-danger': errors.catalog_id}" class="col-12 form-group">

                        <p>
                            La selección de productos para exportar, se realiza desde el catalogo de DIGEMID
                        <br>
                        <!--
                        <el-select v-model="form.catalog_id">
                            <el-option v-for="w in catalogs" :key="w.id" :label="w.description"
                                       :value="w.id"></el-option>
                        </el-select>
                        -->
                            Para mayor información, consulte el manual  <a :href="'/docs/4.X/modulo-farmacia#section-5'" class="label label-light-danger" target="_blank">aqui</a>
                        </p>
                        <small v-if="errors.catalog_id" class="form-control-feedback"
                               v-text="errors.catalog_id[0]"></small>
                    </div>
                    <div :class="{'has-danger': errors.file}" class="col-12 form-group">
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
                            action="/items/catalog">
                            <el-button slot="trigger" type="primary">Seleccione un archivo (xlsx)</el-button>
                        </el-upload>
                        <small v-if="errors.file" class="form-control-feedback" v-text="errors.file[0]"></small>
                    </div>
                    <div class="col-12 mt-4 mb-2">
                        <div v-for="w in catalogs"
                                  :key="w.id"
                                  :value="w.id">
                        <a class="text-dark mr-auto" :href="w.url" target="_new">
                            <span class="mr-2">Obtener un ejemplo de catalogo para {{w.description}}</span>
                            <i class="fa fa-download"></i>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-5">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button :loading="loading_submit" native-type="submit" type="primary">Procesar</el-button>
            </div>
            <input v-model="fromPharmacy" type="hidden"/>
        </form>
    </el-dialog>
</template>

<script>

export default {
    props: [
        'showDialog',
        'pharmacy',
    ],
    data() {
        return {
            loading_submit: false,
            headers: headers_token,
            titleDialog: null,
            resource: 'items',
            errors: {},
            form: {},
            catalogs: [
                {
                    'id':1,
                    'description':'DIGEMID',
                    'url':'http://opm.digemid.minsa.gob.pe/#/consulta-producto',
                },
            ],
            fromPharmacy: false,
        }
    },
    async created() {
        if (this.pharmacy !== undefined && this.pharmacy == true) {
            this.fromPharmacy = true;
        }
        this.initForm();
        // await this.onFetchTables();
    },
    methods: {
        onBeforeUpload(file) {
        },
        async onFetchTables() {
            this.loading_submit = true;
            await this.$http.get('/items/import/tables').then(response => {
                this.catalogs = response.data.catalogs;
            }).finally(() => this.loading_submit = false);
        },
        initForm() {
            this.errors = {}
            this.form = {
                catalog_id: 1
            }
        },
        create() {
            this.titleDialog = 'Selección de productos DIGEMID'
        },
        async submit() {
            if (!this.form.catalog_id) {
                this.$message.warning('Seleccione un catálogo para poder continuar');
                return;
            }
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
        errorUpload(error) {
            console.log(error)
        }
    }
}
</script>
