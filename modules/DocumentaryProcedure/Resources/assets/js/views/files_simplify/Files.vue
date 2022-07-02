<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>TRAMITES</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <div class="btn-group flex-wrap">
                    <a
                        :href="`/documentary-procedure/files_simplify/new`"
                        class="btn btn-custom btn-sm mt-2 mr-2"
                        type="button"

                    >
                        <!--// @click="onCreate"-->
                        <i class="fa fa-plus-circle"></i> Nuevo
                    </a>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Todos los tramites</h3>
            </div>
            <div class="card-body">
                <form class="row"
                      @submit.prevent="onFilter">

                    <div class="col-6 col-md-4 mb-3">
                        <el-select
                            v-model="filter.person_id"
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

                    </div>


                    <div class="col-6 col-md-4 mb-3">
                        <el-input
                            v-model="filter.invoice"
                            placeholder="Número de expediente"
                            type="text"
                        />
                    </div>
                    <div class="col-6 col-md-4 mb-3">
                        <el-input
                            v-model="filter.guide"
                            placeholder="Número de seguimiento"
                            type="text"
                        />
                    </div>

                    <div class="col-6 col-md-4 mb-3">
                        <el-select
                            v-model="filter.documentary_guides_number_status_id"
                            clearable
                            filterable
                            placeholder="Estado de tramite"
                        >
                            <el-option
                                v-for="of in statusDocumentary"
                                :key="of.id"
                                :label="of.name"
                                :value="of.id"
                            ></el-option>
                        </el-select>
                    </div>
                    <div class="col-6 col-md-4 mb-3">
                        <el-select
                            v-model="filter.documentary_office_id"
                            clearable
                            filterable
                            placeholder="Etapa"
                        >
                            <el-option
                                v-for="of in offices"
                                :key="of.id"
                                :disabed="!of.active"
                                :label="of.name"
                                :value="of.id"
                            ></el-option>
                        </el-select>
                    </div>
                    <div class="col-6 col-md-4 mb-3">
                        <el-select
                            v-model="filter.register_date"
                            clearable
                            filterable
                            placeholder="Fecha de registro"
                            @change="onPrepareFilterDate"
                        >
                            <el-option
                                v-for="(date, i) in datesFilter"
                                :key="i"
                                :label="date"
                                :value="date"
                            ></el-option>
                        </el-select>
                    </div>
                    <div
                        v-if="filter.register_date === 'Personalizado'"
                        class="col-6 col-md-4 mb-3"
                    >
                        <el-date-picker
                            v-model="filter.date_start"
                            format="yyyy/MM/dd"
                            placeholder="Fecha inicial"
                            type="date"
                            value-format="yyyy-MM-dd"
                        >
                        </el-date-picker>
                    </div>
                    <div
                        v-if="filter.register_date === 'Personalizado'"
                        class="col-6 col-md-4 mb-3"
                    >
                        <el-date-picker
                            v-model="filter.date_end"
                            format="yyyy/MM/dd"
                            placeholder="Fecha final"
                            type="date"
                            value-format="yyyy-MM-dd"
                        >
                        </el-date-picker>
                    </div>
                    <div
                        class="col-6 col-md-4 mb-3">
                        <label class="control-label">
                            Archivados
                        </label>
                        <div
                            class="form-group">
                            <el-switch v-model="filter.archived"
                                       active-text="Si"
                                       inactive-text="No"
                                       @change="onFilter"
                            ></el-switch>
                        </div>
                    </div>
                    <div
                        class="col-6 col-md-4 mb-3">
                        <label class="control-label">
                            Vencidos
                        </label>
                        <div
                            class="form-group">
                            <el-switch v-model="filter.expired"
                                       active-text="Si"
                                       inactive-text="No"
                                       @change="onFilter"
                            ></el-switch>
                        </div>
                    </div>
                    <!--
                    <div class="col-6 col-md-4 mb-3">
                        <el-button native-type="submit">
                            <i class="fa fa-search"></i>
                            <span class="ml-2">Buscar</span>
                        </el-button>
                    </div> -->
                    <div class="col-12 col-md-12">&nbsp;</div>
                    <div class="col-lg-7 col-md-7 col-md-7 col-sm-12"
                         style="margin-top:29px">

                        <el-button class="submit"
                                   icon="el-icon-search"
                                   native-type="submit"
                                   type="primary">Buscar
                        </el-button>

                        <template v-if="items.length>0">

                            <el-button class="submit"
                                       type="success"
                                       @click.prevent="clickDownload('excel')"><i class="fa fa-file-excel"></i> Exporta
                                                                                                                Excel
                            </el-button>
                            <el-button class="submit"
                                       icon="el-icon-tickets"
                                       type="danger"
                                       @click.prevent="clickDownload('pdf')">Exportar PDF
                            </el-button>

                        </template>
                    </div>

                </form>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Numero de expediente</th>
                            <th>Trámite</th>
                            <th>Cliente</th>
                            <th>Fecha/Hora registro</th>
                            <!--                            <th>Datos del cliente</th>-->
                            <th>Ultimo número de seguimiento</th>
                            <th>Etapa</th>
                            <th>Status de Etapa</th>
                            <th>Fecha de fin</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) in items"
                            :key="item.id">
                            <td class="text-right">{{ index + 1 }}</td>
                            <td>{{ item.invoice }}
                                <template v-if="item.is_archive && item.observation ">
                                    <br>
                                    Motivo: {{item.observation}}
                                </template>


                            </td>
                            <td>


                                <!-- requisitos -->
                                <el-tooltip v-if="item && item.documentary_process && item.documentary_process.requirements && item.documentary_process.requirements.length > 0"
                                            placement="right-start"
                                >
                                    <div slot="content">
                                        Requisitos:
                                        <ul v-for="(requirement) in item.documentary_process.requirements">
                                            <li v-if="requirement.requirement_name.length > 0">
                                                {{ requirement.requirement_name }}
                                            </li>
                                        </ul>

                                    </div>
                                    <i class="fa fa-info-circle"></i>
                                </el-tooltip>

                                <span
                                    v-if=" item.documentary_process !== undefined && item.documentary_process.name !== undefined ">
                                    {{ item.documentary_process.name }}
                                </span>


                                <!-- terminos y condiciones -->
                                <el-tooltip v-if="item && item.documentary_process && item.documentary_process.documentary_terms && item.documentary_process.documentary_terms.length > 0"
                                            placement="right-start">
                                    <div slot="content">
                                        Términos y condiciones:
                                        <ul v-for="(requirement) in item.documentary_process.documentary_terms">
                                            <li>
                                                {{ requirement.term_name }}
                                            </li>
                                        </ul>

                                    </div>
                                    <i class="fa fa-text-width "></i>
                                </el-tooltip>

                            </td>
                            <td>
                                <span
                                    v-if=" item.person !== undefined && item.person.description !== undefined ">
                                     {{ item.person.description }}
                                </span>
                            </td>
                            <td>
                                {{ item.datetime_register }}
                            </td>
                            <!--                            <td>{{ item.sender.name }}</td>-->
                            <td>{{ (item.last_guide && item.last_guide.guide) ? item.last_guide.guide : '' }}</td>
                            <td>
                                <div v-if="item.last_guide && item.last_guide.doc_office && item.last_guide.doc_office.name"
                                     :style="'background-color:'+ item.last_guide.doc_office.color+
                                             ';font-size: 12px;'"
                                     class="badge"
                                >
                                    {{ item.last_guide.doc_office.name }}
                                </div>
                            </td>
                            <td>


                                <div v-if="item.last_guide_status && item.last_guide_status.name"
                                     :style="'background-color:'+ item.last_guide_status.color+
                                             ';font-size: 12px;'"
                                     class="badge"
                                >
                                    {{ item.last_guide_status.name }}
                                </div>
                            </td>
                            <td>
                                <div v-if="item &&
                                item.last_guide &&
                                item.last_guide.date_end"
                                     :class="item.last_guide.class">

                                    {{ item.last_guide.date_end }}
                                    <br>
                                    <strong>
                                            {{ getDiffDay(  item.last_guide.date_end) }}
                                    </strong>
                                </div>

                            </td>
                            <td class="text-center td-btns">

                                <div class="dropdown">
                                    <button id="dropdownMenuButton"
                                            aria-expanded="false"
                                            aria-haspopup="true"
                                            class="btn btn-default btn-sm"
                                            data-toggle="dropdown"
                                            type="button">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div aria-labelledby="dropdownMenuButton"
                                         class="dropdown-menu">

                                        <template v-if="!item.is_archive">
                                                <button
                                                    class="dropdown-item"
                                                    type="button"
                                                    @click.prevent="editItem(item.id)">
                                                    Editar/Ver

                                                </button>
                                                <button

                                                    class="dropdown-item"
                                                    type="button"
                                                    @click.prevent="printFile(item.id)">
                                                    Imprimir
                                                </button>
                                                <button
                                                    v-if="!item.is_completed"
                                                    class="dropdown-item"
                                                    type="button"
                                                    @click.prevent="removeItem(item.id)">
                                                    Eliminar
                                                </button>
                                                <button
                                                    v-if="!item.is_completed"
                                                    class="dropdown-item"
                                                    type="button"
                                                    @click.prevent="completeItem(item.id)">
                                                    Finalizar
                                                </button>
                                            <button
                                                class="dropdown-item"
                                                type="button"
                                                @click.prevent="archiveFile(item.id)">
                                                Archivar
                                            </button>

                                        </template>
                                        <template v-else>
                                            <button
                                                class="dropdown-item"
                                                type="button"
                                                @click.prevent="reactiveFile(item.id)">
                                                Retomar Tramite
                                            </button>


                                        </template>
                                    </div>

                                </div>

                                <!--
                                AGREGAR SECUENCIA DE TRAMITE <br>
                                IMPRIMIR ESTADO DE TRAMITE<br>
                                -->
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <ModalAddReason
            :visible.sync="openModalReason"
            :id="temp_id"
            @addArchive="onArchive"
        ></ModalAddReason>
        <!--

        <ModalAddEdit
            :visible.sync="openModalAddEdit"
            @onAddItem="onAddItem"
            @onUpdateItem="onUpdateItem"
            @onUploadComplete="onUploadComplete"
        ></ModalAddEdit>
        <ModalDerive
            :file="file"
            :visible.sync="showModalDerive"
            @onAddOffice="onAddOffice"
        ></ModalDerive>
        <ModalStage
            :visible.sync="showModalStage"
            @onBackStep="onBackStep"
            @onNextStep="onNextStep"
            @updateFiles="updateFiles"
        ></ModalStage>
        <StageModalObservationStage
            :visible.sync="showStageModalObservationStage"
        ></StageModalObservationStage>
        -->
    </div>
</template>

<script>
import ModalAddReason from "./ModalAddReason";

/*
import ModalAddEdit from "./ModalAddEdit";
import ModalDerive from "./ModalDerive";
import StageModalObservationStage from "./ModalHistoricalObservation";
import ModalStage from "./ModalStage";
*/
import moment from "moment";
import queryString from 'query-string'

import {mapActions, mapState} from "vuex";

export default {
    props: {
        local_files: {
            type: Array,
            required: true,
        },
        local_offices: {
            type: Array,
            required: true,
        },
        local_processes: {
            type: Array,
            required: false,
        },
        local_actions: {
            type: Array,
            required: false,
        },
        local_customers: {
            type: Array,
            required: false,
        },
        local_documentTypes: {
            type: Array,
            required: false,
        },
        local_statusDocumentary: {
            type: Array,
            required: false,
        },
    },
    computed: {
        ...mapState([
            'offices',
            'file',
            'files',
            'processes',
            'actions',
            'customers',
            'documentTypes',
            'statusDocumentary',
        ]),
    },
    components: {
        ModalAddReason
        /*
        ModalAddEdit,
        ModalDerive,
        StageModalObservationStage,
        ModalStage,
        */
    },
    data() {
        return {
            showModalDerive: false,
            showModalStage: false,
            showStageModalObservationStage: false,
            items: [],
            openModalAddEdit: false,
            temp_id: 0,
            openModalReason: false,
            loading: false,
            filter: {
                archived: 0,
                expired: 0,
                name: "",
                register_date: "Hoy",
            },
            basePath: "/documentary-procedure/files",
            datesFilter: [
                "Hoy",
                "Ayer",
                "Anteriores a 7 días",
                "Anteriores a 30 días",
                "Este mes",
                "Mes anterior",
                "Este año",
                "Personalizado",
            ],
        };
    },
    created() {
        this.loadOffices()
        this.loadActions()
        this.loadCustomers()
        this.loadProcesses()
        this.loadFiles()
        this.loadDocumentTypes()

    },
    mounted() {
        this.$store.commit('setOffices', this.local_offices)
        this.$store.commit('setFiles', this.local_files)
        this.$store.commit('setProcesses', this.local_processes)
        this.$store.commit('setActions', this.local_actions)
        this.$store.commit('setCustomers', this.local_customers)
        this.$store.commit('setDocumentTypes', this.local_documentTypes)
        this.$store.commit('setStatusDocumentary', this.local_statusDocumentary)

        this.items = this.files;

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
        haveStage(item) {
            if (item === null) return false;
            if (item === undefined) return false;
            if (item.observations === undefined) return false;
            if (item.observations == null) return false;
            if (item.observations.length == null) return false;
            if (item.observations.length < 1) return false;
            return true
        },
        showDocument(item) {
            item.disable = true;
            this.$store.commit('setFile', item)
            this.$store.commit('setOffices', this.offices)
            this.openModalAddEdit = true;

        },
        onShowExtraButtons(file) {
            if (file.offices) {
                if (file.offices.length > 0) {
                    return false;
                }
            }
            return true;
        },
        onNextStep(office) {
            this.updateFiles();
        },
        onBackStep(office) {
            this.updateFiles();
        },
        updateFiles() {
            this.loading = true;
            this.$http
                .post(`/documentary-procedure/file/reload`, this.filter)
                .then((result) => {
                    let files = result.data.data;
                    this.$store.commit('setFiles', files)
                    this.items = this.files
                    this.loading = false;
                }).catch((err) => {
                this.loading = false;
            })
        },
        onAddOffice(office) {
            this.items = this.items.map((i) => {
                if (i.id === this.file.id) {
                    i.offices.push(office);
                }
                return i;
            });
        },
        onShowModalDerive(file) {
            this.file = file;
            this.showModalDerive = true;
        },
        onShowModalStage(file) {
            //this.file = file;
            this.$store.commit('setFile', file)
            this.showModalStage = true;
        },
        onPrepareFilterDate() {
            const date = moment();
            this.filter.simplify = 1;

            if (this.filter.register_date === "Hoy") {
                this.filter.date_start = date.format("YYYY-MM-DD");
                this.filter.date_end = null;
            }
            if (this.filter.register_date === "Ayer") {
                this.filter.date_start = date.subtract(1, "days").format("YYYY-MM-DD");
                this.filter.date_end = null;
            }
            if (this.filter.register_date === "Anteriores a 7 días") {
                this.filter.date_start = date.subtract(7, "days").format("YYYY-MM-DD");
                this.filter.date_end = moment().format("YYYY-MM-DD");
            }
            if (this.filter.register_date === "Anteriores a 30 días") {
                this.filter.date_start = date.subtract(30, "days").format("YYYY-MM-DD");
                this.filter.date_end = moment().format("YYYY-MM-DD");
            }
            if (this.filter.register_date === "Este mes") {
                this.filter.date_start = date.startOf("month").format("YYYY-MM-DD");
                this.filter.date_end = date.endOf("month").format("YYYY-MM-DD");
            }
            if (this.filter.register_date === "Mes anterior") {
                const prevMonth = date.subtract(1, "month");
                this.filter.date_start = prevMonth
                    .startOf("month")
                    .format("YYYY-MM-DD");
                this.filter.date_end = prevMonth.endOf("month").format("YYYY-MM-DD");
            }
            if (this.filter.register_date === "Este año") {
                this.filter.date_start = date.startOf("year").format("YYYY-MM-DD");
                this.filter.date_end = date.endOf("year").format("YYYY-MM-DD");
            }
            if (this.filter.register_date === "Personalizado") {
                this.showCalendars = true;
            }
        },
        onFilter() {
            this.updateFiles()
        },
        onDelete(item) {
            this.loading = true;
            this.$confirm(
                `¿estás seguro de eliminar al elemento ${item.invoice}?`,
                "Atención",
                {
                    confirmButtonText: "Si, continuar",
                    cancelButtonText: "No, cerrar",
                    type: "warning",
                }
            )
                .then(() => {
                    this.updateFiles()
                    /*
                    this.$http
                        .delete(`${this.basePath}/${item.id}/delete`)
                        .then((response) => {
                            this.$message({
                                type: "success",
                                message: response.data.message,
                            });

                            this.items = this.items.filter((i) => i.id !== item.id);


                            this.loading = false;
                        })
                        .catch((error) => {
                            this.loading = false;
                            this.axiosError(error);
                        });*/
                })
                .catch();
        },
        onShowHistoricalStage(item) {
            this.$store.commit('setFile', item)
            this.showStageModalObservationStage = true;
        },
        onUpdateItem(data) {
            this.items = this.items.map((i) => {
                if (i.id === data.id) {
                    return data;
                }
                return i;
            });
        },
        onUploadComplete() {
            this.updateFiles()

        },
        onAddItem(data) {
            this.updateFiles()
        },
        editItem(id) {
            window.location = `/documentary-procedure/files_simplify/edit/` + id;

        },
        printFile(id) {
            window.open(`/documentary-procedure/files_simplify/export_current/` + id, '_blank');
        },
        completeItem(id) {
            this.$http
                .post(`/documentary-procedure/files_simplify/complete/` + id)
                .then((response) => {
                    this.$message({
                        type: "success",
                        message: response.data.message,
                    });

                })
                .catch((error) => {
                    this.axiosError(error)
                })
                .finally(() => {
                    this.updateFiles();
                });
        },
        archiveFile(id) {

            this.temp_id = id;
            this.openModalReason = true;

            return null;

        },

        onArchive() {
            this.temp_id=0
            this.openModalReason = false;
            this.updateFiles()
        },
        reactiveFile(id) {
            this.$http
                .post(`/documentary-procedure/files_simplify/reactive/` + id)
                .then((response) => {
                    this.$message({
                        type: "success",
                        message: response.data.message,
                    });

                })
                .catch((error) => {
                    this.axiosError(error)
                })
                .finally(() => {
                    this.updateFiles();
                });
        },
        removeItem(id) {
            this.$http
                .post(`/documentary-procedure/files_simplify/destroy/` + id)
                .then((response) => {

                    this.$message({
                        type: "success",
                        message: response.data.message,
                    });

                })
                .catch((error) => {
                    this.axiosError(error)
                })
                .finally(() => {
                    this.updateFiles();
                });

        },
        onCreate() {
            window.location = `/documentary-procedure/files_simplify/create`;
            return null
        },
        onEdit(item) {
            this.$store.commit('setFile', item)
            this.$store.commit('setOffices', this.offices)
            this.openModalAddEdit = true;
        },
        clickDownload(type) {

            let query = queryString.stringify({
                ...this.filter
            });
            if (type == 'excel') {
                window.open(`/documentary-procedure/files_simplify/export/excel?${query}`, '_blank');
                return null;
            }
            window.open(`/documentary-procedure/files_simplify/export/pdf?${query}`, '_blank');


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

        changeCustomer() {
            const customer = this.customers
                .filter((c) => this.filter.person_id == c.id)
                .reduce((c) => c);
            this.form.sender = {
                name: customer.name,
                address: customer.address,
                number: customer.number,
                identity_document_type_id: customer.identity_document_type_id,
            };
        },
        getDiffDay(dateEnd) {
            let str = '';
            if (dateEnd === undefined) return str;
            if (dateEnd === null) return str;

            let now = moment().startOf('day');
            dateEnd = moment(dateEnd, "YYYY-MM-DD HH:mm:ss").startOf('day');
            let total = (dateEnd.diff(now, 'days')+1);
            if (total > 0) {
                str = 'Falta(n) ' + total + ' día(s)';
            } else if (total < 0) {
                // str = 'Finalizó hace ' + total + ' día(s)';
            } else {
                str = 'Hoy finaliza'
            }
            return str
        },
    },
};
</script>
<style>
.td-btns .el-button {
    margin-bottom: 3px;
    margin-top: 3px;
}
</style>
