<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Productos</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <div class="btn-group flex-wrap">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2 dropdown-toggle"
                            data-toggle="dropdown" aria-expanded="false"><i class="fa fa-upload"></i> Importar <span
                        class="caret"></span></button>
                    <div class="dropdown-menu" role="menu" x-placement="bottom-start"
                         style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 42px, 0px);">
                        <a class="dropdown-item text-1" href="#" @click.prevent="clickImportSet()">1. Productos
                            compuestos</a>
                        <a class="dropdown-item text-1" href="#" @click.prevent="clickImportSetIndividual()">2. Detalle
                            productos compuestos</a>
                    </div>
                </div>
                <template
                >
                    <!-- <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button> -->
                    <button
                        type="button"
                        class="btn btn-custom btn-sm  mt-2 mr-2"
                        @click.prevent="clickCreate()"
                        v-if="can_add_new_product"
                    >
                        <i class="fa fa-plus-circle"></i> Nuevo
                    </button>
                </template>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Productos compuestos</h3>
            </div>
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
                            >{{ column.title }}
                            </el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading" width="100%">
                        <th>#</th>
                        <th>Cód. Interno</th>
                        <th>Unidad</th>
                        <th>Nombre</th>
                        <th v-if="columns.description.visible">Descripción</th>
                        <th v-if="columns.model.visible">Modelo</th>
                        <!-- <th v-if="columns.brand.visible">Marca</th>  -->
                        <th v-if="columns.item_code.visible">Cód. SUNAT</th>
                        <!-- <th  class="text-left">Stock</th> -->
                        <th class="text-right">P.Unitario (Venta)</th>
                        <th class="text-center">Tiene Igv</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.internal_id }}</td>
                        <td>{{ row.unit_type_id }}</td>
                        <td>{{ row.description }}</td>
                        <td v-if="columns.description.visible">{{ row.name }}</td>
                        <td v-if="columns.model.visible">{{ row.model }}</td>
                        <td v-if="columns.item_code.visible">{{ row.item_code }}</td>
                        <!-- <td>
                            <template v-if="typeUser=='seller' && row.unit_type_id !='ZZ'">{{ row.stock }}</template>
                            <template v-else-if="typeUser!='seller'&& row.unit_type_id !='ZZ'">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickWarehouseDetail(row.warehouses)"><i class="fa fa-search"></i></button>
                            </template>
                        </td> -->
                        <td class="text-right">{{ row.sale_unit_price }}</td>
                        <td class="text-center">{{ row.has_igv_description }}</td>
                        <td class="text-right">
                            <template v-if="typeUser === 'admin'">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickCreate(row.id)">Editar
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                        @click.prevent="clickDelete(row.id)">Eliminar
                                </button>
                            </template>
                        </td>
                    </tr>
                </data-table>
            </div>

            <items-form :showDialog.sync="showDialog"
                        :recordId="recordId"></items-form>

            <items-import :showDialog.sync="showImportSetDialog"></items-import>

            <items-import-set-individual :showDialog.sync="showImportSetIndividualDialog"></items-import-set-individual>

            <warehouses-detail
                :showDialog.sync="showWarehousesDetail"
                :warehouses="warehousesDetail">
            </warehouses-detail>

        </div>
    </div>
</template>
<script>

import ItemsForm from './form.vue'
import WarehousesDetail from './partials/warehouses.vue'
import ItemsImport from './import.vue'
import DataTable from '../../../components/DataTable.vue'
import {deletable} from '../../../mixins/deletable'
import ItemsImportSetIndividual from './partials/import_set_individual.vue'
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props: [
        'configuration',
        'typeUser',
    ],
    mixins: [deletable],
    components: {
        ItemsForm,
        ItemsImport,
        DataTable,
        WarehousesDetail,
        ItemsImportSetIndividual
    },
    computed: {
        ...mapState([
            'config',
        ]),
        columnsComputed: function () {
            return this.columns;
        }
    },
    data() {
        return {
            can_add_new_product: false,
            showDialog: false,
            showImportSetDialog: false,
            showImportSetIndividualDialog: false,
            showWarehousesDetail: false,
            resource: ' item-production',
            recordId: null,
            warehousesDetail: [],
            // config: {},
            columns: {
                description: {
                    title: 'Descripción',
                    visible: true
                },
                item_code: {
                    title: 'Cód. SUNAT',
                    visible: false
                },
                /*
                purchase_unit_price: {
                    title: 'P.Unitario (Compra)',
                    visible: false
                },
                purchase_has_igv_description: {
                    title: 'Tiene Igv (Compra)',
                    visible: false
                },*/
                model: {
                    title: 'Modelo',
                    visible: false
                },
                /*
                brand: {
                    title: 'Marca',
                    visible: false
                },
                sanitary: {
                    title: 'N° Sanitario',
                    visible: false
                },
                cod_digemid: {
                    title: 'DIGEMID',
                    visible: false
                },

                 */
            },
        }
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
        if (this.config.is_pharmacy !== true) {
            // delete this.columns.sanitary;
            // delete this.columns.cod_digemid;
        }
        this.canCreateProduct();

    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        canCreateProduct() {
            if (this.typeUser === 'admin') {
                this.can_add_new_product = true
            } else if (this.typeUser === 'seller') {
                if (this.config !== undefined && this.config.seller_can_create_product !== undefined) {
                    this.can_add_new_product = this.config.seller_can_create_product;
                }
            }
            return this.can_add_new_product;
        },
        clickImportSetIndividual() {
            this.showImportSetIndividualDialog = true
        },
        clickWarehouseDetail(warehouses) {
            this.warehousesDetail = warehouses
            this.showWarehousesDetail = true
        },
        clickCreate(recordId = null) {
            this.recordId = recordId
            this.showDialog = true
        },
        clickImportSet() {
            this.showImportSetDialog = true
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        }
    }
}
</script>
