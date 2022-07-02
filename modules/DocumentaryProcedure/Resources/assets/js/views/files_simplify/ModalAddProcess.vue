<template>
    <div>
        <el-dialog
            :closeOnClickModal="false"
            :title="title"
            :visible="visible"
            @close="onClose"
            @open="onCreate"
        >

            <div class="form-body row">

                <div
                    :class="{ 'has-danger': errors.guide }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Número de seguimiento
                        <span class="text-danger">*</span></label>
                    <el-input v-model="guide.guide"
                              placeholder="Número de seguimiento"></el-input>
                    <small
                        v-if="errors.guide"
                        class="form-control-feedback"
                        v-text="errors.guide[0]"
                    ></small>
                </div>
                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Fecha/Hora de registro
                        <span class="text-danger">*</span></label>
                    <el-date-picker
                        v-model="guide.created_at "
                        format="yyyy/MM/dd HH:mm"
                        placeholder="Fecha/Hora de registro"
                        type="datetime"
                        value-format="yyyy-MM-dd HH:mm"
                    >
                    </el-date-picker>
                    <small
                        v-if="errors.created_at"
                        class="form-control-feedback"
                        v-text="errors.created_at[0]"
                    ></small>
                </div>

                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Etapa
                        <span class="text-danger">*</span></label>
                    <a v-if="form_stage.add == false"
                       class="control-label font-weight-bold text-info"
                       href="#"
                       @click="form_stage.add = true"> [ + Nuevo]</a>
                    <a v-if="form_stage.add == true"
                       class="control-label font-weight-bold text-info"
                       href="#"
                       @click="saveStage()"> [ + Guardar]</a>
                    <a v-if="form_stage.add == true"
                       class="control-label font-weight-bold text-danger"
                       href="#"
                       @click="form_stage.add = false"> [ Cancelar]</a>
                    <el-input v-if="form_stage.add == true"
                              v-model="form_stage.name"
                              dusk="item_code"
                              style="margin-bottom:1.5%;"></el-input>

                    <el-select v-if="form_stage.add == false"
                               v-model="guide.doc_office_id"
                               clearable
                               filterable

                               placeholder="Etapa"
                    >
                        <el-option
                            v-for="of in offices"
                            :disabled="!of.active"
                            :key="of.id"
                            :label="of.name"
                            :value="of.id"
                        ></el-option>
                    </el-select>
                </div>

                <div
                    :class="{ 'has-danger': errors.date_take }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Fecha cuando se toma la etapa
                    </label>
                    <el-date-picker
                        v-model="guide.date_take "
                        format="yyyy/MM/dd HH:mm"
                        placeholder="Fecha cuando se toma la etapa"
                        type="datetime"
                        value-format="yyyy-MM-dd HH:mm"
                    >
                    </el-date-picker>
                </div>


                <div
                    :class="{ 'has-danger': errors.total_day }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">

                    <label>
                        Dias que toma el etapa
                        <small>
                            {{type_calc}}
                        </small>
                    </label>
                    <el-input v-model="guide.total_day"
                              type="number"
                              @keyup="calculateDays"
                              @change="calculateDays">
                    </el-input>
                </div>


                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Fecha de finalizacion
                    </label>
                    <el-date-picker
                        v-model="guide.date_end "
                        :disabled="true"
                        format="yyyy/MM/dd HH:mm"
                        placeholder="Fecha de finalizacion"
                        type="datetime"
                        value-format="yyyy-MM-dd HH:mm"
                        @keyup="calculateDays"
                        @change="calculateDays"
                    >
                    </el-date-picker>
                </div>

                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Estado de etapa
                        <span class="text-danger">*</span></label>

                    <a v-if="form_status.add == false"
                       class="control-label font-weight-bold text-info"
                       href="#"
                       @click="form_status.add = true"> [ + Nuevo]</a>
                    <a v-if="form_status.add == true"
                       class="control-label font-weight-bold text-info"
                       href="#"
                       @click="saveStatus()"> [ + Guardar]</a>
                    <a v-if="form_status.add == true"
                       class="control-label font-weight-bold text-danger"
                       href="#"
                       @click="form_status.add = false"> [ Cancelar]</a>
                    <el-input v-if="form_status.add == true"
                              v-model="form_status.name"
                              dusk="item_code"
                              style="margin-bottom:1.5%;"></el-input>

                    <el-select v-if="form_status.add == false"
                               v-model="guide.documentary_guides_number_status_id"

                               clearable
                               filterable

                               placeholder="Estado de etapa"
                    >
                        <el-option
                            v-for="of in statusDocumentary"
                            :key="of.id"
                            :label="of.name"
                            :value="of.id"
                        ></el-option>
                    </el-select>
                </div>

                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Responsable
                        <span class="text-danger">*</span></label>
                    <el-select v-model="guide.user_id"
                               clearable
                               filterable
                               placeholder="Responsable"
                               popper-class="el-select-customers">
                        <el-option v-for="option in sellers"
                                   :key="option.id"
                                   :label="option.name"
                                   :value="option.id">
                        </el-option>
                    </el-select>
                </div>

                <div
                    :class="{ 'has-danger': errors.created_at }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Observaciones
                    </label>
                    <el-input v-model="guide.observation"
                              placeholder="Observaciones"></el-input>
                </div>

                <div
                    :class="{ 'has-danger': errors.total_day }"
                    class="form-group col-sm-12 col-md-6 col-lg-4 ">
                    <label>
                        Calculo por dias Hábiles
                    </label>
                    <el-checkbox
                        v-model="guide.by_day"
                        @change="calculateDays"
                    ></el-checkbox>

                </div>

                <div class="row text-center col-12 p-t-20">

                    <div class="col-6">
                        <el-button class="btn-block"
                                   @click="onClose">Cancelar
                        </el-button>
                    </div>
                    <div
                        class="col-6">
                        <el-button
                            v-if="editable"
                            :disabled="disableWhileData"
                            :loading="loading"
                            class="btn-block"
                            native-type="submit"
                            type="primary"
                            @click="addRow"
                        >Guardar
                        </el-button
                        >
                    </div>
                </div>


                <template v-if="guide.files !== undefined && guide.files.length > 0">
                    <div class="col-12 table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>
                                    #
                                </th>

                                <!--                                        <th>
                                                                            observation
                                                                        </th>-->
                                <th>
                                    Nombre de archivo
                                </th>
                                <th>
                                    Accion
                                </th>
                            </tr>


                            </thead>
                            <tbody>
                            <tr v-for="(archive,index) in guide.files"
                                :key="archive.id"
                            >
                                <td>
                                    {{ index + 1 }}
                                <td>
                                    {{ archive.public_name }}
                                </td>
                                <td>
                                    <el-button type="primary"
                                               @click.prevent="downloadFile(archive.public_url)">Descargar
                                    </el-button>
                                    <el-button :loading="borrando"
                                               type="danger"
                                               @click.prevent="removeFile(archive.id,index)">Borrar

                                    </el-button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </template>


            </div>

        </el-dialog>
    </div>
</template>

<script>
import moment from "moment";
import {mapActions, mapState} from "vuex";

export default {
    components: {},
    props: {
        visible: {
            type: Boolean,
            required: true,
            default: false,
        },
        editable: {
            type: Boolean,
            required: false,
            default: true,
        },
        guides: {
            type: Object,
            required: false,
            default: {},
        },
    },
    data() {
        return {
            headers: headers_token,
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
            form_stage: {
                add: false,
                name: '',
            },
            form_status: {
                add: false,
                name: '',
            },
            type_calc:' (Días hábiles)',
            tabActive: "first",
            tempAttachments: [],
            fileList: [],
            current_files: [],
            attachments: [],
            input_person: null,
            index: 0,
            urlDropzone: null,
            guide: {
                id: null,
                total_day: 1,
                guide: null,
                by_day: true,
                date_take: moment().format('YYYY-MM-DD HH:mm'),
                date_end: moment().format('YYYY-MM-DD HH:mm'),
                created_at: moment().format('YYYY-MM-DD HH:mm'),

                doc_office_id: null,
                documentary_guides_number_status_id: null,
                user_id: null,
                observation: null,
            },
            title: "",

            loading: false,
            borrando: false,
            basePath: "/documentary-procedure/files",
            showDialogNewPerson: false,
            errors: {},
            nextId: "",
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
            'sellers',

        ]),
        disableWhileData: function () {
            let guide = this.guide;
            if (
                guide.guide !== null &&
                guide.user_id !== null &&
                guide.doc_office_id !== null &&
                guide.documentary_guides_number_status_id !== null
            ) {
                return false;
            }
            return true;
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
        convertRequirementsIntoArray(val) {
            return this.guide.requirements_id;

        },
        setRequirement() {
            this.requirements_in_process = [];
            if (
                (this.guide !== undefined || this.guide !== null) &&
                (this.guide.documentary_process_id !== undefined) &&
                (this.guide.documentary_process_id !== null)
            ) {

                let temp = this.processes.find((it) => {
                    return it.id === this.guide.documentary_process_id
                });
                if (temp.requirements !== undefined) {
                    if (temp.requirements !== null) {
                        let wo = this.guide.requirements_id;

                        if (wo === undefined || wo == null) {
                            wo = [];
                        }
                        /*
                        temp.requirements.forEach(function (item, index) {
                            wo.push(item.requirement_id)
                        })*/

                        this.guide.requirements_id = wo;


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
        addRow() {
            this.$emit("addrow", this.guide);
            this.onClose()
        },
        onClose() {
            this.$emit("update:visible", false);
        },
        calculateDays() {
            if(this.guide.by_day){
                this.type_calc= '(Días hábiles)';
            }else{
                this.type_calc= '(Días calendario)';

            }
            this.$http
                .post(`${this.basePath}/calculateDays`, this.guide)
                .then((response) => {
                    let data = response.data;
                    /*
                    if(data.total_day){
                        this.guide.total_day = data.total_day
                    }
                    */
                    if (data.date_end) {
                        this.guide.date_end = data.date_end;
                    }
                })
                .catch((error) => {
                    //this.axiosError(error)
                })
                .finally(() => {
                    // this.loading = false
                });

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
            this.guide = {
                id: null,
                guide: null,
                created_at: moment().format('YYYY-MM-DD HH:mm:ss'),
                date_take: moment().format('YYYY-MM-DD HH:mm:ss'),
                doc_office_id: null,
                by_day: true,
                date_end: null,
                documentary_guides_number_status_id: null,
                user_id: null,
                total_day: 1,
                observation: null,
            };
            this.calculateDays();
        },
        onCreate() {
            this.loading = true;
            let newItem = true
            if (this.guides !== undefined) {
                if (this.guides.id !== undefined) {
                    newItem = false;
                }
            }
            this.tabActive = 'first'
            if (newItem === true) {
                this.title = "Crear Etapa";
                this.onInitializeForm();
                // this.onGetDataForNewFile();
            } else {
                this.guide = this.guides
                this.title = "Editar Etapa";
                /*
                this.filename = this.onGetFilenameFromPath(this.guide.attached_file);
                this.nextId = this.guide.id;
                this.number = this.guide.number;
                this.currentYear = this.guide.year;
                this.attachFile = null;
                this.ChangeSelect();
                */
            }
            // this.guide.by_day= true;

                this.loading = false;


        },

        clickAddStep() {
            if (this.guide.guides === undefined) this.guide.guides = [];
            if (this.guide.guides === null) this.guide.guides = [];
            if (this.guide.guides.length < 1) this.guide.guides = [];
            this.guide.guides.push({
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
        handleRemove(file, fileList) {

            this.guide.files[this.index_file].filename = null
            this.guide.files[this.index_file].temp_path = null
            this.fileList = []
            this.index_file = null

        },
        onSuccess(response, file, fileList) {

            // console.log(response, file, fileList)
            this.fileList = fileList

            if (response.success) {

                this.index_file = response.data.index
                this.guide.files[this.index_file].filename = response.data.filename
                this.guide.files[this.index_file].temp_path = response.data.temp_path

            } else {
                this.cleanFileList()
                this.$message.error(response.message)
            }

            // console.log(this.guide.files)

        },
        cleanFileList() {
            this.fileList = []
        },

        downloadFile(url) {
            window.open(url, '_blank');
        },
        removeFile(id,index) {
            this.borrando = true;
            this.$http
                .get(`/documentary-procedure/file/remove/${id}`)
                .then((result) => {
                    //reload
                    // this.updateFile(documentary_file_id)
                    this.updateFiles()
                    this.borrando = false;
                    this.guide.files.splice(index, 1)
                })
                .catch((err) => {
                    // this.updateFile(documentary_file_id)

                    this.updateFiles()
                })
                .finally(() => {
                    this.borrando = false;
                })

        },

        updateFiles() {
            this.$emit("updateFiles");
        },
        saveStage() {
            this.form_stage.add = false
            this.$http.post(`${this.basePath}/addStage`, this.form_stage)
                .then(response => {
                    if (response.data.success) {
                        let off = this.offices;
                        this.$message.success(response.data.message)
                        off.push(response.data.data)
                        this.$store.commit('setOffices', off);
                        this.form_stage.name = null
                    } else {
                        this.$message.error('No se guardaron los cambios')
                    }
                })
                .catch(error => {

                })
        },
        saveStatus() {
            this.form_status.add = false
            this.$http.post(`${this.basePath}/addStatus`, this.form_status)
                .then(response => {
                    if (response.data.success) {
                        let off = this.statusDocumentary;
                        this.$message.success(response.data.message)
                        off.push(response.data.data)
                        this.$store.commit('setStatusDocumentary', off);
                        this.form_status.name = null
                    } else {
                        this.$message.error('No se guardaron los cambios')
                    }
                })
                .catch(error => {

                })
        },
    },
};
</script>
