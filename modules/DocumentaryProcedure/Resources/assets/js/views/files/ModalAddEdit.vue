<template>
    <div>
        <el-dialog
            :title="title"
            :visible="visible"
            @close="onClose"
            @open="onCreate"
        >
            <form autocomplete="off" @submit.prevent="onSubmit">
                <div class="form-body">
                    <el-tabs v-model="tabActive">
                        <el-tab-pane class name="first">
                            <span slot="label">Datos del Tramite</span>
                            <div class="row">
                                <!-- Tipo de tramite -->
                                <div
                                    :class="{ 'has-danger': errors.documentary_process_id }"
                                    class="form-group col-12">
                                    <label>Tipo de tramite <span class="text-danger">*</span></label>
                                    <el-select
                                        v-model="form.documentary_process_id"
                                        @change="ChangeSelect"
                                    >
                                        <el-option
                                            @change="ChangeSelect"
                                            v-for="item in processes"
                                            :key="item.id"
                                            :label="item.name_price"
                                            :value="item.id"
                                        ></el-option>
                                    </el-select>
                                    <div v-if="errors.documentary_process_id" class="invalid-feedback">
                                        {{ errors.documentary_process_id[0] }}
                                    </div>
                                </div>

                                <div v-if="hasRequiements" class="col-12 form-group">
                                    <label>Requisitos del tramite <span class="text-danger">*</span></label>

                                    <!-- v-if="form.requirements_id !== undefined" -->
                                    <div class="row " >


                                        <el-checkbox
                                            class="col-6"
                                            v-for="(item,index) in  requirements_in_process.requirements"
                                            v-model="form.requirements_id[item.requirement_id]"
                                            @change="convertRequirementsIntoArray"
                                            :label="item.requirement_id"
                                            :true-label="item.requirement_id"
                                            :false-label="null"
                                            :key="item.requirement_id">
                                            {{item.requirement_name}}
                                        </el-checkbox>
                                    </div>
                                </div>
                                <!-- Fecha de registro -->
                                <div
                                    :class="{ 'has-danger': errors.date_register }"
                                    class="form-group col-6"
                                >
                                    <label>Fecha de registro <span class="text-danger">*</span></label>
                                    <el-date-picker
                                        v-model="form.date_register"
                                        placeholder="Selecciona una fecha"
                                        type="date"
                                    >
                                    </el-date-picker>
                                    <small
                                        v-if="errors.date_register"
                                        class="form-control-feedback"
                                        v-text="errors.date_register[0]"
                                    ></small>
                                </div>
                                <!-- >Hora de registro -->
                                <div
                                    :class="{ 'has-danger': errors.time_register }"
                                    class="form-group col-6"
                                >
                                    <label>Hora de registro <span class="text-danger">*</span></label>
                                    <el-input v-model="form.time_register" placeholder="13:30:00">
                                    </el-input>
                                    <small
                                        v-if="errors.time_register"
                                        class="form-control-feedback"
                                        v-text="errors.time_register[0]"
                                    ></small>
                                </div>
                                <!-- cliente -->
                                <div :class="{ 'has-danger': errors.person_id }" class="form-group col-12">
                                    <label class="control-label font-weight-bold text-info">
                                        Cliente <span class="text-danger">*</span>
                                        <a href="#" @click.prevent="showDialogNewPerson = true"
                                        >[+ Nuevo]</a
                                        >
                                    </label>
                                    <el-select
                                        v-model="form.person_id"
                                        :loading="loading"
                                        :remote-method="searchRemoteCustomers"
                                        class="border-left rounded-left border-info"
                                        filterable
                                        placeholder="Escriba el nombre o número de documento del cliente"
                                        popper-class="el-select-customers"
                                        remote
                                        @change="changeCustomer"
                                    >
                                        <el-option
                                            v-for="option in customers"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.person_id"
                                        class="form-control-feedback"
                                        v-text="errors.person_id[0]"
                                    ></small>
                                </div>


                                <!-- Folio -->
                                <div
                                    :class="{ 'has-danger': errors.number }"
                                    class="form-group col-6"
                                >
                                    <label>Código de expediente (interno) </label>
                                    <el-input v-model="form.invoice"></el-input>
                                    <small
                                        v-if="errors.invoice"
                                        class="form-control-feedback"
                                        v-text="errors.invoice[0]"
                                    ></small>
                                </div>


                                <!-- Código -->

                                <!--                                <div class="col-6">
                                                                    <label>Código <span class="text-danger">*</span></label>
                                                                    <el-input v-model="nextId" readonly type="text"></el-input>
                                                                </div>-->
                                <!-- Tipo de documento  -->
                                <!---
                                <div
                                    :class="{ 'has-danger': errors.documentary_document_id }"
                                    class="form-group col-6"
                                >
                                    <label>Tipo de documento <span class="text-danger">*</span></label>
                                    <el-select
                                        v-model="form.documentary_document_id"
                                        @change="onGetDocumentNumber"
                                    >
                                        <el-option
                                            v-for="item in documentTypes"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.documentary_document_id"
                                        class="form-control-feedback"
                                        v-text="errors.documentary_document_id[0]"
                                    ></small>
                                </div>
                                -->
                                <!-- Número de documento -->
                                <div class="col-12">
                                    <label>Numeros de referencia </label>
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
                                                    {{ row.origin }}
                                                </td>
                                                <td>
                                                    {{ row.guide}}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

<!--                                <div
                                    :class="{ 'has-danger': errors.number }"
                                    class="form-group col-6"
                                >
                                    <label>Número de documento <span class="text-danger">*</span></label>
                                    <el-input v-model="form.number">

                                    </el-input>
                                    &lt;!&ndash; <template slot="append">-{{ currentYear }}</template> &ndash;&gt;
                                    <small
                                        v-if="errors.number"
                                        class="form-control-feedback"
                                        v-text="errors.number[0]"
                                    ></small>
                                </div>-->
                                <!-- asunto -->
                                <!--
                                                                <div :class="{ 'has-danger': errors.invoice }" class="form-group col-12">
                                                                    <label>Asunto <span class="text-danger">*</span></label>
                                                                    <el-input v-model="form.invoice" type="text"></el-input>
                                                                    <div v-if="errors.invoice" class="invalid-feedback">
                                                                        {{ errors.invoice[0] }}
                                                                    </div>
                                                                </div>-->
                                <!-- archivo adjunto -->

                                <!--                                <div :class="{ 'has-danger': errors.attachFile }" class="form-group col-12">
                                                                    <label>Archivo adjunto <span class="text-danger">*</span></label>
                                                                    <el-button @click="onSearchFile">Buscar archivo</el-button>
                                                                    <input
                                                                        ref="inputFile"
                                                                        class="hidden"
                                                                        type="file"
                                                                        @change="onSelectFile"
                                                                    />
                                                                    <span v-if="filename">{{ filename }}</span>
                                                                    <div v-if="errors.attachFile" class="invalid-feedback">
                                                                        {{ errors.attachFile[0] }}
                                                                    </div>
                                                                </div>-->


                                <!--                                <div
                                                                    :class="{ 'has-danger': errors.documentary_office_id }"
                                                                    class="form-group col-12"
                                                                >
                                                                    <label>Etapa <span class="text-danger">*</span></label>
                                                                    <div v-for="(item,index) in form.full_stage" :key="item.id" class="col-12">
                                                                        <span :class="form.documentary_office_id === item.id ? 'text-danger':''" v-if="item !== undefined">
                                                                            {{ item.print_name }} {{ item.string_days }}
                                                                        </span>
                                                                    </div>
                                                                    <div v-if="errors.documentary_office_id" class="invalid-feedback">
                                                                        {{ errors.documentary_office_id[0] }}
                                                                    </div>
                                                                </div>
                                                                -->
                            </div>

                        </el-tab-pane>

                        <el-tab-pane v-if="showArchives" class name="second">
                            <span slot="label">Archivos</span>
                            <table-archives
                                @updateFiles="updateFiles"
                            ></table-archives>
                        </el-tab-pane>

                        <el-tab-pane class name="thirdh">
                            <span slot="label">Datos Complementarios</span>


                            <vue-dropzone
                                id="dropzone"
                                ref="myVueDropzone"
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
                    </el-tabs>
                    <div class="row text-center col-12 p-t-20">
                        <div class="col-6" v-if="!onlyShow">
                            <el-button
                                :disabled="canSubmit"
                                :loading="loading"
                                class="btn-block"
                                native-type="submit"
                                type="primary"
                            >Guardar
                            </el-button
                            >
                        </div>
                        <div class="col-6">
                            <el-button class="btn-block" @click="onClose">Cancelar</el-button>
                        </div>
                    </div>
                </div>
            </form>
        </el-dialog>
        <person-form
            :document_type_id="form.document_type_id"
            :external="true"
            :input_person="input_person"
            :showDialog.sync="showDialogNewPerson"
            type="customers"
        ></person-form>
    </div>
</template>

<script>
import moment from "moment";
import TableArchives from "./TableArchives";
import PersonForm from "../../../../../../../resources/js/views/tenant/persons/form.vue";
import OfficesRows from './Offices.vue';
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import {mapActions, mapState} from "vuex";
import TableObservation from "./TableObservation";

export default {
    components: {
        TableObservation,
        TableArchives,
        PersonForm,
        OfficesRows,
        vueDropzone: vue2Dropzone
    },
    props: {
        visible: {
            type: Boolean,
            required: true,
            default: false,
        },
    },
    data() {
        return {
            dropzoneOptions: {
                url: 'https://httpbin.org/post',
                headers: {
                    "X-Requested-With": "X-Requested-With",
                    "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content,
                },
                autoProcessQueue: false,
                paramName: function (n) {
                    return "file[]";
                },
                dictDefaultMessage: "Arrastra y suelta los archivos aqui.",
                parallelUploads: 10,
                maxFiles: 10,
                uploadMultiple: true,
            },
            tabActive: "first",
            tempAttachments: [],
            current_files: [],
            attachments: [],
            input_person: null,
            urlDropzone: null,
            form: {
                requirements_id : [],
            },
            title: "",
            loading: false,
            basePath: "/documentary-procedure/files",
            showDialogNewPerson: false,
            errors: {},
            nextId: "",
            currentYear: 0,
            attachFile: null,
            filename: "",
            fileCount: 0,
            requirements_in_process:{
                requirements:[],
            }
        };
    },
    created() {
    },
    mounted() {
        this.onInitializeForm();
    },
    computed: {
        ...mapState([
            'offices',
            'files',
            'file',
            'actions',
            'documentTypes',
            'processes',
            'customers',

        ]),
        onlyShow(){
           if(this.form!== undefined && this.form.disable !== undefined) return this.form.disable
            return false;
        },
        canSubmit(){
            // if(this.loading)
            if(
                this.form.documentary_process_id !== null &&
                this.form.person_id !== null  &&
                this.loading === false
            ) {
                return false
            }
            return true;
        },
        hasRequiements: function () {

            if (
                this.requirements_in_process !== undefined &&
                this.requirements_in_process !== null &&
                this.requirements_in_process.requirements !== undefined &&
                this.requirements_in_process.requirements !== null &&
                this.requirements_in_process.requirements.length > 0
            ) {
                return true;
            }
            return false;
        },
        showArchives: function () {
            if (this.form === undefined) return false
            if (this.form === null) return false
            if (this.form.documentary_file_archives === undefined) return false
            if (this.form.documentary_file_archives === null) return false

            if (this.form.documentary_file_archives.length > 0)
                return true;
            return false;
        }

    },
    methods: {
        ...mapActions([
            'loadOffices',
            'loadActions',
            'loadCustomers',
            'loadProcesses',
            'loadDocumentTypes',
            'loadFiles',
        ]),
        convertRequirementsIntoArray(val){
          return  this.form.requirements_id;

        },
        setRequirement() {
            this.requirements_in_process = [];
            if (
                (this.form !== undefined || this.form !== null) &&
                (this.form.documentary_process_id !== undefined) &&
                (this.form.documentary_process_id !== null)
            ) {

                let temp = this.processes.find((it) => {
                    return it.id === this.form.documentary_process_id
                });
                if (temp.requirements !== undefined) {
                    if (temp.requirements !== null) {
                        let wo = this.form.requirements_id;

                        if(wo === undefined || wo == null) {
                            wo = [];
                        }
                        /*
                        temp.requirements.forEach(function (item, index) {
                            wo.push(item.requirement_id)
                        })*/

                        this.form.requirements_id = wo;


                    }
                }

                this.requirements_in_process = temp;

            }
            this.convertRequirementsIntoArray()
        },
        ChangeSelect() {
            this.setRequirement()
            this.convertRequirementsIntoArray()
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
        updateFiles() {
            this.$emit("updateFiles");
        },
        fileAdded(file) {
            console.log("File Dropped => ", file);
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
        sendingFiles(files, xhr, formData) {
            this.current_files = files;
        },
        uploadProgress(file, progress, bytesSent) {
            this.tempAttachments.map(attachment => {
                if (attachment.title === file.name) {
                    attachment.progress = `${Math.floor(progress)}`;
                }
            });
        },
        error(file, response) {
            if (response !== undefined && response.status !== undefined) {
                this.axiosError(response)
            }
            if (response !== undefined && response.message !== undefined) {
                this.errors = response.message
            }
            let dropzone = this.$refs.myVueDropzone.dropzone;
            file.previewTemplate.classList.toggle('dz-error');
            if (file.status === 'error') file.status = 'added';
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
        },
        // called on successful upload of a file
        success(file, response) {

            this.$emit("onUploadComplete", null);
            this.onClose();
        },
        sendingEvent(file, xhr, formData) {
            formData.append("date_register", this.form.date_register);
            formData.append("time_register", this.form.time_register);
            formData.append("offices", this.form.offices);
            formData.append("documentary_process_id", this.form.documentary_process_id);
            formData.append("requirements_id", JSON.stringify(this.form.requirements_id));
            formData.append("person_id", this.form.person_id);
            formData.append("invoice", this.form.invoice);

            formData.append("year", this.currentYear);

            if (this.form.sender) {
                formData.append("person", JSON.stringify(this.form.sender));
            }
            if (this.form.offices) {
                formData.append("offices", JSON.stringify(this.form.offices));
            }
            formData.append("invoice", this.form.invoice || "");
            formData.append("observation", this.form.observation || "");
            if (this.attachFile) {
                formData.append("attachFile", this.attachFile);
            }
            for (let files in this.$refs.myVueDropzone.dropzone.files) {
                formData.append("attachments[]", this.$refs.myVueDropzone.dropzone.files[files]);
            }
        },
        getFileCount() {
            if ('undefined' !== typeof this.$refs.myVueDropzone.dropzone) {
                this.fileCount = this.$refs.myVueDropzone.dropzone.files.length
            } else {
                this.fileCount = 0;
            }
        },
        addFile() {
            var file = {size: 123, name: "XYZ.pdf"};
            var url = "xyz.pdf";
            this.$refs.myVueDropzone.manuallyAddFile(file, url);
        },
        onSearchFile() {
            this.$refs.inputFile.click();
        },
        onSelectFile(event) {
            const files = event.target.files;
            if (files.length > 0) {
                this.attachFile = files[0];
                this.filename = this.attachFile.name;
            } else {
                this.attachFile = null;
                this.filename = "";
            }
        },
        onGenerateData() {
            const data = new FormData();

            data.append("date_register", this.form.date_register);
            data.append("time_register", this.form.time_register);
            data.append("offices", this.form.offices);
            data.append("documentary_process_id", this.form.documentary_process_id);
            data.append("requirements_id",  JSON.stringify(this.form.requirements_id));
            data.append("person_id", this.form.person_id);
            data.append("invoice", this.form.invoice);

            data.append("year", this.currentYear);

            if (this.form.sender) {
                data.append("person", JSON.stringify(this.form.sender));
            }
            if (this.form.offices) {
                data.append("offices", JSON.stringify(this.form.offices));
            }
            if (this.attachFile) {
                data.append("attachFile", this.attachFile);
            }
            for (let files in this.$refs.myVueDropzone.dropzone.files) {
                let cor = this.$refs.myVueDropzone.dropzone.files[files]
                data.append("attachments[]", cor);
            }
            return data;
        },
        changeCustomer() {
            const customer = this.customers
                .filter((c) => this.form.person_id == c.id)
                .reduce((c) => c);
            this.form.sender = {
                name: customer.name,
                address: customer.address,
                number: customer.number,
                identity_document_type_id: customer.identity_document_type_id,
            };
        },
        searchRemoteCustomers(input) {
            if (input.length > 0) {
                this.loading = true;
                let parameters = `input=${input}&document_type_id=&operation_type_id=`;

                this.$http
                    .get(`/documents/search/customers?${parameters}`)
                    .then((response) => {
                        this.$store.commit('setCustomers', response.data.customers)
                    })
                    .catch((error) => this.axiosError(error))
                    .finally(() => (this.loading = false));
            }
        },
        onUpdate(data) {
            this.loading = true;
            this.$http
                .post(`${this.basePath}/${this.form.id}/update`, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    this.$message({
                        message: response.data.message,
                        type: "success",
                    });
                    this.$emit("onUpdateItem", response.data.data);
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
        onSubmit() {
            const data = this.onGenerateData();
            let dropfile = this.$refs.myVueDropzone.dropzone.files.length;
            if (dropfile > 0) {

                if (this.form && this.form.id) {
                    this.$refs.myVueDropzone.dropzone.options.url = `${this.basePath}/${this.form.id}/update`
                } else {
                    this.$refs.myVueDropzone.dropzone.options.url = `${this.basePath}/store`
                }
                this.$refs.myVueDropzone.dropzone.processQueue()
            } else {
                if (this.form && this.form.id) {
                    this.onUpdate(data)
                } else {
                    this.onStore(data)
                }
            }

        },
        onClose() {
            this.tabActive = 'first';
            this.$refs.myVueDropzone.dropzone.removeAllFiles(true);
            this.$emit("update:visible", false);
        },
        async onGetDocumentNumber() {
            const params = {
                document_id: this.form.documentary_document_id,
            };
            this.loading = true;
            await this.$http
                .get(`${this.basePath}/document-number`, {params})
                .then((response) => {
                    const data = response.data.data;
                    this.form.number = data.number;
                })
                .catch((error) => this.axiosError(error))
                .finally(() => (this.loading = false));
        },
        async onGetDataForNewFile() {
            this.loading = true;
            await this.$http
                .get(`${this.basePath}/create`)
                .then((response) => {
                    const data = response.data.data;
                    this.nextId = data.next_id;
                    this.currentYear = data.current_year;
                })
                .catch((error) => this.axiosError(error))
                .finally(() => (this.loading = false));
        },
        onGetFilenameFromPath(path) {
            if (path) {
                const parts = path.split("/");
                if (parts.length === 4) {
                    return parts[3];
                }
            }
            return "";
        },
        onInitializeForm() {
            const date = moment();
            this.form = {
                date_register: date.format("YYYY-MM-DD"),
                time_register: date.format("H:mm:ss"),
                offices: [],
                documentary_process_id: null,
                tempAttachments: [],
                requirements_id: [],
                full_stages: [],
                disable : false
            };
            this.filename = "";
            this.attachFile = null;
            this.setRequirement()
        },
        onCreate() {
            this.tabActive = 'first'
            console.dir(this.file)
            if(this.file == null){
                this.title = "Crear tramite";
                this.onInitializeForm();
                this.onGetDataForNewFile();
            }else{
                let f =  this.file;
                if(f.offices == null ) f.offices = [];
                if(f.documentary_process_id == null ) f.documentary_process_id = null;
                if(f.tempAttachments == null ) f.tempAttachments = [];
                if(f.requirements_id == null ) f.requirements_id = [];
                if(f.full_stages == null ) f.full_stages = [];
                if(f.disable == null ) f.disable = disable;

                this.form =f



                this.title = "Editar tramite";
                this.filename = this.onGetFilenameFromPath(this.form.attached_file);
                this.nextId = this.form.id;
                this.number = this.form.number;
                this.currentYear = this.form.year;
                this.attachFile = null;
                this.ChangeSelect();
            }
            this.loading = true;
            this.$http
                .get(`${this.basePath}/tables`)
                .then((response) => {
                    const data = response.data.data;
                    this.$store.commit('setCustomers', data.customers)
                    this.$store.commit('setDocumentTypes', data.document_types)
                    this.$store.commit('setActions', data.actions)
                    // this.$store.commit('setProcesses', data.processes)
                    // this.$store.commit('setOffices', data.offices)

                })
                .catch((error) => this.axiosError(error))
                .finally(() => (this.loading = false));
        },
    },
};
</script>
