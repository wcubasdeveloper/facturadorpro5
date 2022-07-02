<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>REGISTRO DE REQUERIMIENTOS</span></li>
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
                <h3 class="my-0">Listado de requisitos</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-2 mb-3">
                        <form class="form-group" @submit.prevent="onFilter">
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
                                        style="border-color: #ced4da"
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
                            <th>Requerimiento</th>
                            <!--<th>Requiere la carga de archivo</th>-->
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="(item,index) in items"
                            :key="item.id"
                        >
                            <td class="text-right">{{ index + 1 }}</td>
                            <td>{{ item.name }}</td>
                            <!--
                            <td class="text-center">
                                <span v-if="item.file">Si</span>
                                <span v-else>No</span>
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
                                <el-button
                                    :disabled="loading"
                                    type="danger"
                                    @click="onDelete(item)"
                                >
                                    <i class="fa fa-trash"></i>
                                </el-button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <ModalAddEdit
            :requirement="requirement"
            :visible.sync="openModalAddEdit"
            @onAddItem="onAddItem"
            @onUpdateItem="onUpdateItem"
        ></ModalAddEdit>
    </div>
</template>

<script>
import ModalAddEdit from "./ModalAddEdit";

export default {
    props: ['requirements'],
    components: {
        ModalAddEdit,
    },
    data() {
        return {
            items: [],
            requirement: null,
            openModalAddEdit: false,
            loading: false,
            filter: {
                name: "",
            },
            basePath: '/documentary-procedure/requirements'
        };
    },
    mounted() {
        this.items = this.requirements;
    },
    methods: {
        onFilter() {
            this.loading = true;
            const params = this.filter;
            this.$http
                .post(this.basePath, {params})
                .then((response) => {
                    this.items = response.data.data;
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
                            this.items = this.items.filter((i) => i.id !== item.id);
                        })
                        .catch((error) => {
                            this.axiosError(error);
                        });
                })
                .catch();
        },
        onEdit(item) {
            this.requirement = {...item};
            this.openModalAddEdit = true;
        },
        onUpdateItem(data) {
            this.items = this.items.map((i) => {
                if (i.id === data.id) {
                    return data;
                }
                return i;
            });
        },
        onAddItem(data) {
            this.items.unshift(data);
        },
        onCreate() {
            this.requirement = null;
            this.openModalAddEdit = true;
        },
    },
};
</script>
