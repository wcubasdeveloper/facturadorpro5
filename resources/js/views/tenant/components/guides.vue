<template>
    <div>
        <el-dialog
            :close-on-click-modal="false"
            :close-on-press-escape="false"
            :title="title"
            :visible="show"
            @close="onClose"
            @open="getRecordGuide"
        >
            <!--
            <template v-if="!is_client">
                        </template>

            -->
            <div class="form-group">
                <label class="control-label">
                    Guías
                </label>
                <table style="width: 100%">
                    <tr v-for="(guide,index) in form.guides">
                        <td>
                            <el-select v-model="guide.document_type_id">
                                <el-option v-for="option in document_types_guide"
                                           :key="option.id"
                                           :label="option.description"
                                           :value="option.id"></el-option>
                            </el-select>
                        </td>
                        <td>
                            <el-input v-model="guide.number"></el-input>
                        </td>
                        <td>
                            <template v-if="guide.filename">
                                <button
                                    type="button"
                                    v-if="guide.filename && guide.live === undefined"
                                    class="btn waves-effect waves-light btn-xs btn-primary"
                                    @click.prevent="clickDownloadFile(guide.filename)">
                                    <i class="fas fa-file-download"></i>
                                </button>
                                <div
                                    v-if="guide.filename && guide.live == 1"
                                >
                                <span>
                                    {{guide.filename}}
                                </span>
                                </div>
                            </template>
                            <template v-else>
                                <el-upload

                                    :action="`/${type}/guide-file/upload`"
                                    :data="{'index': index}"
                                    :file-list="fileList"
                                    :headers="headers"
                                    :limit="form.guides.length"
                                    :multiple="false"
                                    :on-remove="handleRemove"
                                    :on-success="onSuccess"
                                    :show-file-list="false"
                                >
                                    <el-button
                                        slot="trigger"
                                        type="primary"
                                        @click="changeIndexFile(index)"
                                    >
                                        Seleccione un archivo
                                    </el-button>

                                </el-upload>
                            </template>
                        </td>
                        <td align="right">
                            <button class="btn waves-effect waves-light btn-xs btn-danger"
                                    type="button"
                                    @click.prevent="clickRemoveGuide(index)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <label v-if="!loading"
                                   class="control-label">
                                <a class=""
                                   href="#"
                                   @click.prevent="clickAddGuide"><i class="fa fa-plus font-weight-bold text-info"></i>
                                    <span style="color: #777777">
                                        Agregar guía
                                    </span>
                                </a>

                            </label>
                        </td>
                    </tr>
                </table>
            </div>


            <div class="text-center">
                <el-button
                    v-if="form.guides.length > 0"
                    :disabled="loading"
                    type="primary"
                    @click="saveGuides"
                >Guardar Guia
                </el-button
                >
                <el-button
                    :disabled="loading"
                    @click="onClose"
                >Cerrar
                </el-button>
            </div>

        </el-dialog>
    </div>
</template>
<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props: {
        'establishment': {
            required: false,
            default: ''
        },
        id: {
            required: false,
            type: Number,
            default: 0

        },
        show: {
            required: false,
            type: Boolean,
            default: false

        },
        type: {
            required: false,
            type: String,
            default: ''

        },

    },
    computed: {
        ...mapState([
            'config',
            'document_types_guide',
        ]),
    },
    data() {
        return {
            // document_types_guide: [],
            headers: headers_token,
            index_file: null,
            loading: true,
            title: '',
            fileList: [],
            form: {
                guides: [{}],
                id: null,
                number: '',
                document_type_description: '',
            }

        }
    },
    created() {
        this.loadConfiguration()

    },
    methods: {

        ...mapActions([
            'loadConfiguration',
            'loadDocumentTypesGuide',
        ]),
        getRecordGuide() {
            this.fileList = [];
            this.form.guides = [];
            this.loading = true;
            this.$http.post(`/${this.type}/guide/${this.id}`)
                .then((result) => {
                    this.form = result.data
                    this.title = 'Guia para Documento: ' + this.form.number + "";
                    if (this.form.guides === undefined) {
                        this.form.guides = [];
                    }

                })
                .finally(() => {
                    this.loading = false;
                })
        },
        clickAddInitGuides() {
            this.form.guides.push({
                document_type_id: '09',
                number: null
            }, {
                document_type_id: '31',
                number: null
            })
        },
        clickRemoveGuide(index) {
            this.form.guides.splice(index, 1)
            this.fileList.splice(index, 1)
        },
        cleanFileList() {
            this.fileList = []
        },
        saveGuides() {
            this.loading = true;
             this.form.updateGuide = 1;


            this.$http.post(`/${this.type}/guide/${this.id}`,  this.form)
                .then((result) => {
                    this.onClose()

                })
                .finally(() => {
                    this.loading = false;
                })

        },
        clickAddGuide() {
            this.form.guides.push({
                document_type_id: null,
                number: null
            })
        },
        onClose() {
            this.title = 'Guias';
            this.loading = false;
            this.form = {};
            this.form.guides = [];
            this.fileList = [];
            this.$emit("update:show", false);

        },
        handleRemove(file, fileList) {

            this.form.guides[this.index_file].filename = null
            this.form.guides[this.index_file].temp_path = null
            this.fileList = []
            this.index_file = null

        },
        hasFile(index) {
            if (this.fileList[index] !== undefined) {
                if (this.fileList[index] !== null) {
                    return true
                }
            }
            return false
        },
        changeIndexFile(index) {
            this.index_file = index
        },
        onSuccess(response, file, fileList) {

            // console.log(response, file, fileList)
            // this.fileList = fileList

            if (this.index_file == null) this.index_file = this.fileList.length;
            if (response.success) {
                this.index_file = response.data.index
                for (let i = 0; i < this.index_file; i++) {
                    if (this.fileList[i] === undefined) this.fileList[i] = null;
                }
                this.fileList[this.index_file] = file;
                let t = this.form.guides[this.index_file];
                t.filename = response.data.filename
                t.temp_path = response.data.temp_path
                t.live = 1

                this.form.guides[this.index_file] = t;
                let guides = this.form.guides;
                this.form.guides = [];
                this.form.guides = guides;
                this.hasFile(this.index_file)
            } else {
                this.cleanFileList()
                this.$message.error(response.message)
            }

            // console.log(this.form.guides)

        },
        clickDownloadFile(filename) {
            window.open(
                `/${this.type}/guides-file/download-file/${this.id}/${filename}`,
                "_blank"
            );
        },
        onSubmit() {
            console.error('onSubmit')

        },
        handleExceed() {
            console.error('handleExceed')

        }
    }
}
</script>
