<template>
    <div>
        <el-dialog
            :title="title"
            :visible="visible"
            @close="onClose"
            @open="onCreate"
            width="30%"
            center
        >

                <div class="form-body text-center">


                    <div
                        class="form-group  ">
                        <label>
                            Carga de archivos
                        </label>
                        <el-upload
                            class="upload-demo "
                            drag
                            :headers="headers"
                            :action="urlUpload"
                            :file-list="fileList"
                            :beforeUpload="onUpdate"
                            multiple>

                            <!--
                            :data="{'index': index}"
                            :headers="headers"
                            :multiple="false"
                            :on-remove="handleRemove"
                            :action="`/finances/payment-file/upload`"
                            :show-file-list="true"
                            :file-list="fileList"
                            :on-success="onSuccess"
-->

                            <i class="el-icon-upload"></i>

                            <div class="el-upload__text">Suelta tu archivo aquí o <em>haz clic para cargar</em></div>
                            <!--
                            <div slot="tip" class="el-upload__tip">Solo archivos jpg/png con un tamaño menor de 500kb</div>
                            -->
                        </el-upload>

                        <!--
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
                        -->

                        <div v-if="errors.documentary_process_id"
                             class="invalid-feedback">
                            {{ errors.documentary_process_id[0] }}
                        </div>
                    </div>


                    <div class="row text-center col-12 p-t-20">
                        <div class="col-3">&nbsp;</div>
                        <div class="col-6">
                            <el-button class="btn-block"
                                       @click="onClose">
                                Cerrar
                            </el-button>
                        </div>
                        <!--
                    <div class="col-6">
                        <el-button
                            :disabled="!sendFile"
                            :loading="loading"
                            class="btn-block"
                            native-type="submit"
                            type="primary"
                        >Guardar
                        </el-button
                        >
                    </div>
                        -->
                    </div>

                </div>

        </el-dialog>
    </div>
</template>

<script>
import moment from "moment";
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import {mapActions, mapState} from "vuex";

export default {
    components: {
        vueDropzone: vue2Dropzone
    },
    props: {
        visible: {
            type: Boolean,
            required: true,
            default: false,
        },
        stageId: {
            type: Number,
            required: true,
        }
    },
    data() {
        return {
            urlUpload: '',
            headers: headers_token,
            fileList: [],
            tabActive: "first",
            tempAttachments: [],
            current_files: [],
            attachments: [],
            input_person: null,
            urlDropzone: null,
            form: {
                requirements_id: [],
                guides: [{}],
            },
            title: "",
            loading: false,
            basePath: "/documentary-procedure/files_simplify/upload",
            showDialogNewPerson: false,
            errors: {},
            nextId: "",
            sendFile: false,
            currentYear: 0,
            attachFile: null,
            filename: "",
            fileCount: 0,
            requirements_in_process: {
                requirements: [],
            }
        };
    },
    created() {
        this.urlUpload= `${this.basePath}/${this.stageId}/update`

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
            'statusDocumentary',

        ]),
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

        canSubmit() {
            this.loading = false
            let id = parseInt(this.stageId)
            if (isNaN(id)) id = 0;

            this.sendFile = id !== 0 && !this.loading;
            this.urlUpload = '';
            if (this.form && this.stageId) {
                // this.$refs.myVueDropzone.dropzone.options.url = `${this.basePath}/${this.form.id}/update`
                this.urlUpload= `${this.basePath}/${this.stageId}/update`
            } else {
                // this.$refs.myVueDropzone.dropzone.options.url = `${this.basePath}/store`
            }
        },
        updateFiles() {
            this.$emit("updateFiles");
        },

        success(file, response) {
            this.$emit("onUploadComplete", null);
            this.onClose();
        },
        sendingEvent(file, xhr, formData) {
            formData.append("stage_id", this.stageId);


            if (this.attachFile) {
                formData.append("attachFile", this.attachFile);
            }
            for (let files in this.$refs.myVueDropzone.dropzone.files) {
                formData.append("attachments[]", this.$refs.myVueDropzone.dropzone.files[files]);
            }
        },
        onGenerateData() {
            const data = new FormData();

            data.append("date_register", this.form.date_register);
            data.append("time_register", this.form.time_register);
            data.append("offices", this.form.offices);
            data.append("documentary_process_id", this.form.documentary_process_id);
            data.append("requirements_id", JSON.stringify(this.form.requirements_id));
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
            for (let files in this.fileList) {
                // let cor = this.$refs.myVueDropzone.dropzone.files[files]
                data.append("attachments[]", files);
            }/*
            for (let files in this.$refs.myVueDropzone.dropzone.files) {
                let cor = this.$refs.myVueDropzone.dropzone.files[files]
                data.append("attachments[]", cor);
            }*/
            return data;
        },
        onGenerateData2(File,form) {
            const data = new FormData();

            data.append("date_register", form.date_register);
            data.append("time_register", form.time_register);
            data.append("offices", form.offices);
            data.append("documentary_process_id", form.documentary_process_id);
            data.append("person_id", form.person_id);
            data.append("id", this.stageId);
            data.append("stage_id", this.stageId);
            this.urlUpload= `${this.basePath}/${this.stageId}/update`

            data.append("invoice", form.invoice);

            data.append("year", this.currentYear);

            if (form.sender) {
                data.append("person", JSON.stringify(form.sender));
            }
            if (form.offices) {
                data.append("offices", JSON.stringify(form.offices));
            }
            data.append("file", File);
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
        onUpdate(file) {
            const data = this.onGenerateData2(file, this.form);
            this.loading = true;
            this.$http
                .post(`${this.basePath}/${this.stageId}/update`, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    let data = response.data;
                    if(data.withStage !== 0) {
                        this.$message({
                            message: data.message,
                            type: "success",
                        });
                        this.$emit("onUpdateItem", data.data);
                        this.$emit("updateStage", true);
                    }
                })
                .finally(() => {
                    this.loading = false;
                    this.errors = {};
                })
                .catch((error) => {
                    if(error ==! undefined && error.response !== undefined) {
                        this.axiosError(error);
                    }
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
                    this.$emit("updateStage", true);
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
            this.fileList = [];
            this.$emit("update:visible", false);
            this.$emit("closeElement", false);
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
                disable: false
            };
            this.filename = "";
            this.attachFile = null;
        },
        onCreate() {
            this.canSubmit()
            return null;
            this.tabActive = 'first'
            if (this.file == null) {
                this.title = "Crear tramite";
                this.onInitializeForm();
                this.onGetDataForNewFile();
            } else {
                let f = this.file;
                if (f.offices == null) f.offices = [];
                if (f.documentary_process_id == null) f.documentary_process_id = null;
                if (f.tempAttachments == null) f.tempAttachments = [];
                if (f.requirements_id == null) f.requirements_id = [];
                if (f.full_stages == null) f.full_stages = [];
                if (f.disable == null) f.disable = disable;

                this.form = f


                this.title = "Editar tramite";
                this.filename = this.onGetFilenameFromPath(this.form.attached_file);
                this.nextId = this.form.id;
                this.number = this.form.number;
                this.currentYear = this.form.year;
                this.attachFile = null;
                this.form.id = this.stageId
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

            if (this.form.guides === undefined) this.form.guides = [];
            if (this.form.guides === null) this.form.guides = [];
            if (this.form.guides.length < 1) this.clickAddStep();


        },

        clickAddStep() {
            if (this.form.guides === undefined) this.form.guides = [];
            if (this.form.guides === null) this.form.guides = [];
            if (this.form.guides.length < 1) this.form.guides = [];
            this.form.guides.push({
                id: null,
                guide: null,
                created_at: moment().format('YYYY-MM-DD'),

                doc_office_id: null,
                date_take: null,
                date_end: null,
                documentary_guides_number_status_id: null,
                user_id: null,
                observation: null,


            });


        },
    },
};
</script>
