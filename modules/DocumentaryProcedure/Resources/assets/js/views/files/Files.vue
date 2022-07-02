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
                    <button
                        class="btn btn-custom btn-sm mt-2 mr-2"
                        type="button"
                        @click="onCreate"
                    >
                        <i class="fa fa-plus-circle"></i> Nuevo
                    </button>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Todos los tramites</h3>
            </div>
            <div class="card-body">
                <form class="row" @submit.prevent="onFilter">
                    <div class="col-6 col-md-2 mb-3">
                        <el-input
                            v-model="filter.invoice"
                            placeholder="Filtrar por numero de expediente"
                            type="text"
                        />
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <el-select
                            v-model="filter.documentary_office_id"
                            clearable
                            placeholder="Etapa"
                        >
                            <el-option
                                v-for="of in offices"
                                :key="of.id"
                                :label="of.name"
                                :value="of.id"
                            ></el-option>
                        </el-select>
                    </div>
                    <div class="col-6 col-md-2 mb-3">
                        <el-select
                            v-model="filter.register_date"
                            clearable
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
                        class="col-6 col-md-2 mb-3"
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
                        class="col-6 col-md-2 mb-3"
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
                    <!--
                    <div class="col-6 col-md-2 mb-3">
                        <el-button native-type="submit">
                            <i class="fa fa-search"></i>
                            <span class="ml-2">Buscar</span>
                        </el-button>
                    </div> -->

                    <div class="col-lg-7 col-md-7 col-md-7 col-sm-12" style="margin-top:29px">

                    <el-button class="submit" type="primary"
                               native-type="submit"
                                icon="el-icon-search" >Buscar</el-button>

                    <template v-if="items.length>0">

                        <el-button class="submit" type="success"
                                   @click.prevent="clickDownload('excel')"><i class="fa fa-file-excel" ></i>  Exporta Excel</el-button>
                        <el-button class="submit" type="danger"  icon="el-icon-tickets" @click.prevent="clickDownload('pdf')" >Exportar PDF</el-button>

                    </template>
                    </div>

                </form>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Numero de expediente</th>
                            <th>Fecha/Hora registro</th>
                            <th>Remitente</th>
                            <th>Proceso</th>
                            <th>Etapa</th>
                            <th>Fecha de fin</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) in items" :key="item.id">
                            <td class="text-right">{{ index + 1 }}</td>
                            <td>{{ item.invoice }}</td>
                            <td>{{ item.date_register }} - {{ item.time_register }}</td>
                            <td>{{ item.sender.name }}</td>
                            <td>
                                <span
                                    v-if=" item.documentary_process !== undefined && item.documentary_process.name !== undefined ">
                                     {{ item.documentary_process.name }}
                                </span>
                            </td>
                            <td>
                                <div v-if="item.last_complete">
                                    <el-tooltip
                                        class="item"
                                        effect="dark" placement="top-start"

                                    >
                                        <div slot="content" >
                                            {{ item.last_complete.office_name }}<br>Inicia
                                            {{ item.last_complete.start_date }}<br>Finaliza
                                            {{ item.last_complete.end_date }}
                                        </div>
                                        <div :class="item.last_complete.class">{{
                                                item.last_complete.office_name
                                                                               }}
                                        </div>
                                    </el-tooltip>
                                </div>

                            </td>
                            <td>
                                <div v-if="item.last_complete.end_date" :class="item.last_complete.class">
                                    {{ item.last_complete.end_date }}
                                </div>
                            </td>
                            <td class="text-center td-btns">
                                <el-dropdown trigger="click">
                                    <el-button>
                                        Opciones
                                        <i class="el-icon-arrow-down el-icon--right"></i>
                                    </el-button>
                                    <el-dropdown-menu slot="dropdown">
                                        <!--
                                        <el-dropdown-item
                                            :disabled="loading"
                                            @click.native="onShowModalDerive(item)"
                                        >
                                            <i class="fa fa-file-export"></i>
                                            <span class="ml-3">Derivar</span>
                                        </el-dropdown-item>
                                        -->

                                        <el-dropdown-item
                                            :disabled="loading"
                                            @click.native="onShowModalStage(item)"
                                        >
                                            <i class="fa fa-file-export"></i>
                                            <span class="ml-3">
                                                Etapas
                                            </span>
                                        </el-dropdown-item>
                                        <el-dropdown-item
                                            v-if="haveStage(item)"
                                            :disabled="loading"
                                            @click.native="onShowHistoricalStage(item)"
                                        >
                                            <i class="fa fa-eye"></i>
                                            <span class="ml-3">Histórico de observaciones</span>
                                        </el-dropdown-item>

                                        <el-dropdown-item
                                            :disabled="loading"
                                            @click.native="showDocument(item)"
                                        >
                                            <i class="fa fa-eye"></i>
                                            <span class="ml-3">Ver</span>
                                        </el-dropdown-item>


                                        <template v-if="onShowExtraButtons(item)">
                                            <el-dropdown-item
                                                :disabled="loading"
                                                @click.native="onEdit(item)"
                                            >
                                                <i class="fa fa-edit"></i>
                                                <span class="ml-3">Editar</span>
                                            </el-dropdown-item>
                                            <el-dropdown-item
                                                :disabled="loading"
                                                @click.native="onDelete(item)"
                                            >
                                                <i class="fa fa-trash"></i>
                                                <span class="ml-3">Eliminar</span>
                                            </el-dropdown-item>
                                        </template>
                                    </el-dropdown-menu>
                                </el-dropdown>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
    </div>
</template>

<script>
import ModalAddEdit from "./ModalAddEdit";
import ModalDerive from "./ModalDerive";
import StageModalObservationStage from "./ModalHistoricalObservation";
import ModalStage from "./ModalStage";
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
        ]),
    },
    components: {
        ModalAddEdit,
        ModalDerive,
        StageModalObservationStage,
        ModalStage,
    },
    data() {
        return {
            showModalDerive: false,
            showModalStage: false,
            showStageModalObservationStage: false,
            items: [],
            openModalAddEdit: false,
            loading: false,
            filter: {
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
                    let files = result.data;
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
        onCreate() {
            this.$store.commit('setFile', null)
            this.$store.commit('setOffices', this.offices)
            this.openModalAddEdit = true;
        },
        onEdit(item) {
            this.$store.commit('setFile', item)
            this.$store.commit('setOffices', this.offices)
            this.openModalAddEdit = true;
        },
        clickDownload(type){

            let query = queryString.stringify({
                ...this.filter
            });
            if(type == 'excel') {
                window.open(`/documentary-procedure/files/export/excel?${query}`, '_blank');
            }else{
                window.open(`/documentary-procedure/files/export/pdf?${query}`, '_blank');

            }


        }
    },
};
</script>
<style>
.td-btns .el-button {
    margin-top: 3px;
    margin-bottom: 3px;
}
</style>
