<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>REGISTRO DE ETAPAS</span></li>
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
                <h3 class="my-0">Listado de etapas</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4 mb-3">
                        <form class="form-group"
                              @submit.prevent="onFilter">
                            <div class="input-group mb-3">
                                <input
                                    v-model="filter.name"
                                    class="form-control"
                                    placeholder="Filtrar por nombre"
                                    type="text"
                                />
                                <div class="input-group-append">
                                    <button
                                        class="btn btn-outline-secondary"
                                        style="border-color: #CED4DA"
                                        type="submit"
                                    >
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Etapa</th>
                            <th>Descripción</th>
                            <th>Activo</th>
                            <!--
                            <th>Responsable</th>
                            <th>Dias</th>
                            -->
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="(item,index) in items"
                            :key="item.id"
                            :style="'background-color:'+ item.color"
                        >
                            <td class="text-right">{{ index + 1 }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.description }}</td>
                            <td class="text-center">
                                <span v-if="item.active">Si</span>
                                <span v-else>No</span>
                            </td>
                            <!--
                            <td>
                                <span
                                    v-for="users in item.users_name" :key="users.id"

                                >
                                {{ users.name }} <br>
                                </span>
                            </td>
                            <td> {{ item.string_days }}
                            </td>
                            -->
                            <td class="text-center">
                                <el-button
                                    :disabled="loading"
                                    type="success"
                                    @click="onEdit(item)"
                                >
                                    <i class="fa fa-edit"></i>
                                </el-button>
                                <!--
                                <el-button
                                    :disabled="loading"
                                    type="danger"
                                    @click="onDelete(item)"
                                >
                                    <i class="fa fa-trash"></i>
                                </el-button>
                                -->
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <ModalAddEdit
            :visible.sync="openModalAddEdit"
            @onAddItem="onFilter"
            @onUpdateItem="onUpdateItem"
        ></ModalAddEdit>
    </div>
</template>

<script>
import ModalAddEdit from "./ModalAddEdit";
import {mapActions, mapState} from "vuex";

export default {
    props: {
        etapas: {
            type: Array,
            required: true,
        },
        configuration: {},
        users: {
            type: Array,
            required: false,
        },
    },
    components: {
        ModalAddEdit,
    },
    computed: {
        ...mapState([
            'workers',
            'offices',
            'config'
        ])
    },
    data() {
        return {
            items: [],
            office: null,
            openModalAddEdit: false,
            loading: false,
            filter: {
                name: "",
            },
            basePath: '/documentary-procedure/offices'
        };
    },
    created() {
        this.$store.commit('setOffices', this.etapas);
        this.$store.commit('setConfiguration', this.configuration);
        this.$store.commit('setWorkers', this.users);

        this.loadConfiguration()
        this.loadWorkers()
        this.loadOffices()
        this.items = this.offices
    },
    mounted() {
    },
    methods: {
        ...mapActions([
            'loadWorkers',
            'loadConfiguration',
            'loadOffices'
        ]),
        WorkAssociated(item) {
            if (item === undefined || item === null) return '';
            if (item.user === undefined || item.user === null) return '';
            return item.user.name;
        }, onFilter() {
            this.loading = true;
            const params = this.filter;
            this.$http
                .get(this.basePath, {params})
                .then((response) => {
                    let item = response.data.data;
                    console.error(item)
                    this.$store.commit('setOffices', item)
                    this.items = this.offices
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        onDelete(item) {
            this.$confirm(
                `¿estás seguro de eliminar al elemento ${item.name}?`,
                "Atención",
                {
                    confirmButtonText: "Si, continuar",
                    cancelButtonText: "No, cerrar",
                    type: "warning",
                }
            )
                .then(() => {
                    this.$http
                        .delete(`${this.basePath}/${item.id}/delete`)
                        .then((response) => {
                            this.$message({
                                type: "success",
                                message: response.data.message,
                            });
                            this.onFilter()
                        })
                        .catch((error) => {
                            this.axiosError(error);
                        });
                })
                .catch();
        },

        onCreate() {
            this.$store.commit('setOffice', {})
            this.openModalAddEdit = true;
        },
        onEdit(item) {
            this.$store.commit('setOffice', item)
            this.openModalAddEdit = true;
        },
        onUpdateItem(data) {
            this.onFilter()

        },
        onAddItem(data) {
            this.onFilter()
        },
    },
};
</script>
