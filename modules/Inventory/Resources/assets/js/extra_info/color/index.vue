<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>
                    Colores
                </span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <!--
                <a :href="`/${resource}/create`"
                   class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
                -->


                <el-button :loading="loading_submit"
                           type="primary"
                           @click.prevent="NewColor()">Nuevo
                </el-button>
            </div>

        </div>

        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">
                    Colores de productos
                </h3>
            </div>
            <!--
            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columnsComputed" :key="index">
                            <el-checkbox
                                v-if="column.title !== undefined && column.visible !== undefined"
                                v-model="column.visible"
                            >{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>

            <el-button :loading="loading_submit"
                       type="primary"
                       @click.prevent="getRecords()">Buscar
            </el-button>
            -->
            <div class="card-body">

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in records">
                                <td>{{ index + 1 }}</td>
                                <td class="text-center">{{ row.name }}</td>
                                <td class="text-center">
                                    <button class="btn waves-effect waves-light btn-xs btn-info"
                                            type="button"
                                            @click.prevent="EditItem(row.id)">Editar
                                    </button>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                        <div>
                            <el-pagination
                                :current-page.sync="pagination.current_page"
                                :page-size="pagination.per_page"
                                :total="pagination.total"
                                layout="total, prev, pager, next"
                                @current-change="getRecords"
                            >
                            </el-pagination>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <color-form
            :resource="resource"
        >

        </color-form>
    </div>
</template>
<style scoped>
.anulate_color {
    color: red;
}
</style>
<script>

import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import ColorForm from '../form.vue';

export default {
    props: [
        'configuration',
    ],
    mixins: [],
    components: {
        ColorForm
    },
    computed: {
        ...mapState([
            'color',
            'config',
            'records',
            'showColorDialog',
            'loading_submit',
        ]),
    },
    data() {
        return {
            resource: 'colors',
            ccolor: {},
            recordId: null,
            showDialogOptions: false,
            showDialogOptionsPdf: false,
            pagination: {
                current_page: 0,
                per_page: 0,
                total: 0,
            },

        }
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
        this.getRecords();


    },
    methods: {
        ...mapActions(['loadConfiguration']),
        clickDownload(external_id) {
            window.open(`${this.resource}/download/${external_id}/a4`, '_blank');
        },
        EditItem(id) {
            this.ccolor = {id: id, name: ''};
            this.$store.commit('setColor', this.ccolor)
            this.$store.commit('canShowColorDialog', true)
        },
        NewColor() {
            this.ccolor = {name: ''};
            this.$store.commit('setColor', this.ccolor)
            this.$store.commit('canShowColorDialog', true)
        },
        getRecords() {
            this.$store.commit('setLoadingSubmit', true);
            return this.$http
                .get(`./${this.resource}/records`)
                .then(response => {
                    let data = response.data;
                    this.$store.commit('setRecords', data.data)
                    this.$store.commit('setPagination', {
                        current_page: data.current_page,
                        total: data.total,
                        per_page: parseInt(data.per_page),

                    })
                })
                .catch(error => {
                })
                .then(() => {
                    this.$store.commit('setLoadingSubmit', false);

                });
        },
    }
}
</script>
