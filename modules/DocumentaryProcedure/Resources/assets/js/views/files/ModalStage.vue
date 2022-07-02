<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="title"
        :visible="visible"
        @close="onClose"
        @open="onOpened"
    >
        <form autocomplete="off" @submit.prevent="onSubmit">
            <div class="form-body">
                <el-tabs v-model="tabActive">
                    <el-tab-pane class name="first">
                        <span slot="label">Datos del Tramite</span>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Etapa</label>
                                <el-select
                                    slot="prepend"
                                    v-model="form.documentary_office_id"
                                >
                                    <el-tooltip
                                        v-for="item in form.documentary_file_offices"
                                        :key="item.documentary_office_id"
                                        placement="top"
                                    >
                                        <div slot="content">
                                            Dias: {{ item.days }} <br>
                                            Fecha de inicio: {{ item.start_date }} <br>
                                            Fecha de finalizacion: {{ item.end_date }}
                                            <br>
                                        </div>

                                        <el-option
                                            :label="item.office_name"
                                            :value="item.documentary_office_id"
                                        ></el-option>

                                    </el-tooltip>

                                </el-select>

                            </div>
                            <div class="col-6 form-group">
                                <label>&nbsp;</label>
                                <el-checkbox
                                    v-model="hadObservation"
                                    :false-label="'false'"
                                    :true-label="'true'"
                                    @change="toggleObserved"
                                >
                                    ¿Ha sido observado?
                                </el-checkbox>
                            </div>
                            <!-- <div class="form-group col-6">
                                <label>Acción</label>
                                <el-select v-model="form.documentary_action_id">
                                    <el-option
                                        v-for="item in actions"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id"
                                    ></el-option>
                                </el-select>
                            </div> -->
                            <div class="form-group col-12" v-if="hadObservation=='true'">
                                <label>Observación</label>
                                <el-input v-model="form.observation"
                                          :disabled="form.hadObservation"
                                          :readonly="form.hadObservation"
                                          type="textarea"></el-input>
                                <div
                                    v-if="errors!== undefined && errors.observation !== undefined && errors.observation.length > 0"
                                    class="invalid-feedback">
                                    {{ errors.observation }}
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label>Numeros de seguimiento </label>
                                <a class="" href="#" @click.prevent="addObservation">
                                    <i class="fa fa-plus font-weight-bold text-info"></i> <span
                                    style="color: #777777"></span></a>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>
                                                Institucion
                                            </th>
                                            <th>
                                                Guia
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.guides" :key="index">
                                            <td>
                                                <el-input v-model="row.origin"></el-input>
                                            </td>
                                            <td>
                                                <el-input v-model="row.guide"></el-input>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane v-if="showArchives" class name="second">
                        <span slot="label">Archivos</span>
                        <table-archives
                            @updateFiles="updateFiles"
                        ></table-archives>
                    </el-tab-pane>
                    <el-tab-pane class name="thirdhob">
                        <span slot="label">Complemento de archivos</span>
                        <vue-dropzone
                            id="dropzone"
                            ref="myVueDropObservaction"
                            :options="dropzoneOptions"

                            @vdropzone-upload-progress="uploadProgress"
                            @vdropzone-file-added="fileAdded"
                            @vdropzone-sending-multiple="sendingFiles"
                            @vdropzone-error="error"
                            @vdropzone-error-multiple="errormultiple"
                            @vdropzone-success-multiple="success"
                            @vdropzone-sending="sendingEvent"
                            @vdropzone-removed-file="getFileCount"
                            @vdropzone-file-added-manually="getFileCount">
                        </vue-dropzone>
                    </el-tab-pane>
                    <el-tab-pane v-if="haveObservation(file)" class name="four">
                        <span slot="label">Observaciones</span>

                        <table-observation></table-observation>

                    </el-tab-pane>

                    <div class="col-12 text-center p-t-20">

                        <el-button type="danger" @click.prevent="BackStep">Devolver</el-button>
                        <el-button type="primary" @click.prevent="NextStep">Avanzar</el-button>
                        <!-- <el-button native-type="submit" type="primary">Guardar</el-button> -->
                        <!-- <el-button @click="onClose">Cerrar</el-button> -->
                    </div>
                </el-tabs>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import {mapState} from "vuex";
import TableArchives from "./TableArchives";
import TableObservation from "./TableObservation";

export default {
    components: {
        TableArchives,
        TableObservation,
        vueDropzone: vue2Dropzone
    },
    props: {
        visible: {
            type: Boolean,
            required: true,
        },
        hasError: function () {
            if (this.form === undefined) false;
            if (this.form.observation === undefined) false;
            if (this.form.observation === undefined) false;
            if (his.form.observation.length > 0) true;

            return false;
        },
        errors: function () {
            let observacion = '';
            let documentary_office_id = '';
            if (this.form !== undefined) {
                if (this.form.observation !== undefined && this.form.observation.length === 0) observacion = 'Debes colocar una observacion';
                // if (this.form.documentary_office_id === 0) observacion = 'Debe seleccionar una etapa';
            }
            return {
                observation: observacion,
                // documentary_office_id:documentary_office_id,
            }

        },/*
        file: {
            type: Object,
            required: false,
        },*/
    },
    data() {
        return {
            // form: {},
            hadObservation: false,
            tabActive: 'first',
            dropzoneOptions: {
                url: 'https://httpbin.org/post',
                // thumbnailWidth: 50,
                // thumbnailHeight: 50,
                //maxFilesize: 0.5,
                headers: {
                    "X-Requested-With": "X-Requested-With",
                    "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content,
                },

                autoProcessQueue: false,

                // The way you want to receive the files in the server
                paramName: function (n) {
                    return "file[]";
                },
                dictDefaultMessage: "Arrastra y suelta los archivos aqui.",
                // includeStyling: false,
                // previewsContainer: false,
                parallelUploads: 10,
                maxFiles: 10,
                uploadMultiple: true,
            },
            loading: false,
            title: "",
        };
    },
    computed: {
        ...mapState([
            'file',
            'actions',
            'offices',
        ]),
        form: function () {
            if (this.file === null) return {documentary_office_id: '',observation:null,};

            let temp  = this.file;
            temp.observation = '';
            this.hadObservation = false;
            this.observation = null;
            return temp;
        },
        showArchives: function () {

            if (this.file === undefined || this.file === null) return false;
            if (this.file.documentary_file_archives === undefined || this.file.documentary_file_archives === null) return false;
            if (
                this.file.documentary_file_archives.length > 0
            )
                return true;
            return false;
        }

    },
    created() {
    },
    mounted() {


        this.loading = true;
        this.$http
            .get("/documentary-procedure/files/tables")
            .then((response) => {
                const data = response.data.data;
                this.$store.commit('setActions', data.actions)
                this.$store.commit('setOffices', data.offices)

            })
            .catch((error) => this.axiosError(error))
            .finally(() => (this.loading = false));
    },
    methods: {
        toggleObserved() {
            !this.form.hadObservation
        },
        haveObservation(file) {
            if (file === null) return false;
            if (file === undefined) return false;
            if (file.observations === undefined) return false;
            if (file.observations == null) return false;
            if (file.observations.length == null) return false;
            if (file.observations.length < 1) return false;
            return true
        },
        fileAdded(file) {
            console.log("File Dropped => ", file);
            // Construct your file object to render in the UI
            this.filesa = [...this.filesa, file]
            let attachment = {};


            attachment._id = file.upload.uuid;
            attachment.title = file.name;
            attachment.type = "file";
            attachment.extension = "." + file.type.split("/")[1];
            attachment.user = JSON.parse(localStorage.getItem("user"));
            attachment.content = "File Upload by Select or Drop";
            attachment.thumb = file.dataURL;
            attachment.thumb_list = file.dataURL;
            attachment.isLoading = true;
            attachment.progress = null;
            attachment.size = file.size;
            this.tempAttachments = [...this.tempAttachments, attachment];
        },
        // a middle layer function where you can change the XHR request properties
        sendingFiles(files, xhr, formData) {
            this.current_files = files;
        },
        // function where we get the upload progress
        uploadProgress(file, progress, bytesSent) {
            this.tempAttachments.map(attachment => {
                if (attachment.title === file.name) {
                    attachment.progress = `${Math.floor(progress)}`;
                }
            });
        },
        // called on successful upload of a file
        error(file, response) {
            if (response !== undefined && response.status !== undefined) {
                this.axiosError(response)
            }
            if (response !== undefined && response.message !== undefined) {
                this.errors = response.message
            }
            let dropzone = this.$refs.myVueDropObservaction.dropzone;
            file.previewTemplate.classList.toggle('dz-error');
            // file.previewTemplate.classList.toggle('dz-complete');

            if (file.status === 'error') file.status = 'added';
            // if(file.status === 'error') file.status = 'queued';
            this.addOnError(dropzone, file)

        },
        errormultiple(file, response, other) {
            console.log('errormultiple')
            if (response.message !== undefined) {
                response.status = 422
                if (response.data === undefined) {
                    response.data = {
                        message: response.message,
                    }


                }
            }
            if (response !== undefined && response.status !== undefined) {
                this.axiosError(response)
            }
        },
        addOnError(dropzone, file) {
            dropzone.enqueueFile(file)
            // this.$refs.myVueDropObservaction.dropzone.enqueueFile(file)
        },
        // called on successful upload of a file
        success(file, response) {

            this.$emit("onUploadComplete", null);
            this.onClose();
            this.tabActive = 'first';
            this.updateFiles()
        },
        sendingEvent(file, xhr, formData) {
            formData.append("documentary_office_id", this.form.documentary_office_id);
            formData.append("documentary_action_id", this.form.documentary_action_id);
            formData.append("observation", this.form.observation);
            formData.append("hadObservation", this.hadObservation);
            formData.append("guides", JSON.stringify(this.form.guides));

            formData.append("id", this.form.id);
        },
        getFileCount() {
            if ('undefined' !== typeof this.$refs.myVueDropObservaction.dropzone) {
                this.fileCount = this.$refs.myVueDropObservaction.dropzone.files.length
            } else {
                this.fileCount = 0;
            }
        },
        addFile() {
            var file = {size: 123, name: "XYZ.pdf"};
            var url = "xyz.pdf";
            this.$refs.myVueDropObservaction.manuallyAddFile(file, url);
        },
        onGenerateData() {
            const data = new FormData();
            data.append("id", this.form.id);
            data.append("hadObservation", this.hadObservation);
            data.append("documentary_office_id", this.form.documentary_office_id);
            data.append("guides", JSON.stringify(this.form.guides));
            data.append("documentary_action_id", this.form.documentary_action_id);
            data.append("observation", this.form.observation);

            return data;
        },
        onUpdate(data) {
            this.loading = true;
            this.$http
                .post(`documentary-procedure/files/next`, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    this.$message({
                        message: response.data.message,
                        type: "success",
                    });
                    console.error(response)
                    this.$emit("onUpdateItem", response.data.data);
                    //this.onClose();
                })
                .finally(() => {
                    this.loading = false;
                    this.errors = {};
                })
                .catch((error) => {
                    this.axiosError(error);
                });
        },
        onStore(data) {
            this.loading = true;
            this.$http
                .post(`${this.basePath}/store`, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    this.$message({
                        message: response.data.message,
                        type: "success",
                    });
                    this.$emit("onAddItem", response.data.data);
                    this.onClose();
                })
                .finally(() => {
                    this.loading = false;
                    this.errors = {};
                })
                .catch((error) => {
                    this.axiosError(error);
                });
        },
        updateFiles() {
            this.$emit("updateFiles");
        },
        NextStep() {


            if (this.hasError) {

                return null;
            } else {
                this.getFileCount()
                if (this.fileCount > 0) {
                    this.$refs.myVueDropObservaction.dropzone.options.url = `/documentary-procedure/files/next`
                    this.$refs.myVueDropObservaction.dropzone.processQueue()

                } else {
                    this.loading = true;
                    this.$http
                        .post(
                            `/documentary-procedure/files/next`,
                            this.onGenerateData()
                        )
                        .then((response) => {
                            this.$message({
                                message: response.data.message,
                                type: "success",
                            });
                            this.$store.commit('setFiles', response.data.files)
                            this.$emit("onNextStep", response.data.data);
                            this.onClose();
                        });
                }
            }


        },
        BackStep() {
            if (this.hasError) {
                return null;
            } else {

                this.getFileCount()
                if (this.fileCount > 0) {
                    this.$refs.myVueDropObservaction.dropzone.options.url = `/documentary-procedure/files/back`
                    this.$refs.myVueDropObservaction.dropzone.processQueue()
                } else {

                    this.loading = true;
                    this.$http
                        .post(
                            `/documentary-procedure/files/back`,
                            this.onGenerateData()
                        )
                        .then((response) => {
                            this.$message({
                                message: response.data.message,
                                type: "success",
                            });
                            this.$store.commit('setFiles', response.data.files)
                            this.$emit("onBackStep", response.data.data);
                            this.onClose();
                        });
                }
            }

        },
        onSubmit() {
            this.loading = true;
            this.$http
                .post(
                    `/documentary-procedure/files/${this.file.id}/add-office`,
                    this.form
                )
                .then((response) => {
                    this.$message({
                        message: response.data.message,
                        type: "success",
                    });
                    this.$store.commit('setFiles', response.data.files)
                    this.$emit("onNextStep", response.data.data);
                    this.onClose();
                });
        },
        onOpened() {
            this.tabActive = 'first'
            this.hadObservation = false;
            this.title = `Etapas para el tramite: ${this.file.invoice}`;
        },
        onClose() {
            this.$refs.myVueDropObservaction.dropzone.removeAllFiles(true);
            this.$emit("update:visible", false);
        },

        addObservation(row) {
            this.form.guides.push({
                guide: null,
            });

        },
    },
};
</script>
