<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ titleTopBar }}</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <template v-if="typeUser === 'admin'">
                    <div class="btn-group flex-wrap">
                        <button
                            aria-expanded="false"
                            class="btn btn-custom btn-sm mt-2 mr-2 dropdown-toggle"
                            data-toggle="dropdown"
                            type="button"
                        >
                            <i class="fa fa-download"></i> Exportar
                            <span class="caret"></span>
                        </button>
                        <div
                            class="dropdown-menu"
                            role="menu"
                            style="
                                position: absolute;
                                will-change: transform;
                                top: 0px;
                                left: 0px;
                                transform: translate3d(0px, 42px, 0px);
                            "
                            x-placement="bottom-start"
                        >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickExport()"
                            >
                                Listado
                            </a>
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickExportWp()"
                            >
                                Woocommerce
                            </a>
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickExportBarcode()"
                            >
                                Etiquetas
                            </a>
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickExportDigemid()"
                            >
                                DIGEMID
                            </a>
                        </div>
                    </div>
                    <div class="btn-group flex-wrap">
                        <button
                            aria-expanded="false"
                            class="btn btn-custom btn-sm mt-2 mr-2 dropdown-toggle"
                            data-toggle="dropdown"
                            type="button"
                        >
                            <i class="fa fa-upload"></i> Importar
                            <span class="caret"></span>
                        </button>
                        <div
                            class="dropdown-menu"
                            role="menu"
                            style="
                                position: absolute;
                                will-change: transform;
                                top: 0px;
                                left: 0px;
                                transform: translate3d(0px, 42px, 0px);
                            "
                            x-placement="bottom-start"
                        >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickImport()"
                            >Productos</a
                            >

                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickDigemidImport()"
                            >DIGEMID</a
                            >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickImportListPrice()"
                            >L. Precios</a
                            >
                        </div>
                    </div>
                </template>
                <button
                    v-if="can_add_new_product"
                    class="btn btn-custom btn-sm mt-2 mr-2"
                    type="button"
                    @click.prevent="clickCreate()"
                >
                    <i class="fa fa-plus-circle"></i> Nuevo
                </button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">{{ title }}</h3>
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
                <data-table
                    :productType="type"
                    :resource="resource"
                    :pharmacy="pharmacy"
                >
                    <tr slot="heading" width="100%">
                        <th>#</th>
                        <th>Cód. Interno</th>
                        <th>Unidad</th>
                        <th>Nombre</th>
                        <th v-if="columns.description.visible">Descripción</th>
                        <th v-if="columns.model.visible">Modelo</th>
                        <th >Marca</th>
                        <th v-if="columns.item_code.visible">Cód. SUNAT</th>
                        <th >R.S.</th>
                        <th >DIGEMID </th>
                        <th >Nom. DIGEMID </th>
                        <th >Laboratorio </th>
                        <th >Exportable </th>
                        <th class="text-left">Stock</th>
                        <th class="text-right">P.Unitario (Venta)</th>
                        <th v-if="typeUser != 'seller' && columns.purchase_unit_price.visible" class="text-right">
                            P.Unitario (Compra)
                        </th>
                        <th class="text-center">Tiene Igv (Venta)</th>
                        <th v-if="columns.purchase_has_igv_description.visible" class="text-center">Tiene Igv (Compra)
                        </th>
                        <th class="text-right">Acciones</th>
                    </tr>

                    <tr></tr>
                    <tr
                        slot-scope="{ index, row }"
                        :class="{ disable_color: !row.active }"
                    >
                        <td>{{ index }}</td>
                        <td>{{ row.internal_id }}</td>
                        <td>{{ row.unit_type_id }}</td>
                        <td>{{ row.description }}</td>
                        <td v-if="columns.model.visible">{{ row.model }}</td>
                        <td >{{ row.brand }}</td>
                        <td v-if="columns.description.visible">{{ row.name }}</td>
                        <td v-if="columns.item_code.visible">{{ row.item_code }}</td>
                        <td >{{ row.sanitary }} </td>
                        <td >{{ row.cod_digemid }} </td>
                        <td >{{ row.name_disa }} </td>
                        <td >{{ row.laboratory }} </td>
                        <td >
                            <el-checkbox
                                @change="updateExportable(row.id,row)"
                                v-model="row.exportable_pharmacy"
                            >{{ (row.exportable_pharmacy === true) ? 'Si' : 'No' }}
                            </el-checkbox>
                        </td>
                        <td>
                            <div v-if="config.product_only_location == true">
                                {{ row.stock }}
                            </div>
                            <div v-else>
                                <template
                                    v-if="
                                        typeUser == 'seller' &&
                                        row.unit_type_id != 'ZZ'
                                    "
                                >{{ row.stock }}
                                </template
                                >
                                <template
                                    v-else-if="
                                        typeUser != 'seller' &&
                                        row.unit_type_id != 'ZZ'
                                    "
                                >
                                    <button
                                        class="btn waves-effect waves-light btn-xs btn-info"
                                        type="button"
                                        @click.prevent="
                                            clickWarehouseDetail(row.warehouses, row.item_unit_types)
                                        "
                                    >
                                        <i class="fa fa-search"></i>
                                    </button>
                                </template>
                            </div>
                            <!-- <template v-for="item in row.warehouses">
                                <template>{{item.stock}} - {{item.warehouse_description}}</template><br>
                            </template> -->

                            <!-- <br/>Mín:{{ row.stock_min }} -->
                        </td>
                        <td class="text-right">{{ row.sale_unit_price }}</td>
                        <td v-if="typeUser != 'seller' && columns.purchase_unit_price.visible" class="text-right">
                            {{ row.purchase_unit_price }}
                        </td>
                        <td class="text-center">
                            {{ row.has_igv_description }}
                        </td>
                        <td v-if="columns.purchase_has_igv_description.visible" class="text-center">
                            {{ row.purchase_has_igv_description }}
                        </td>
                        <td class="text-right">
                            <template v-if="typeUser === 'admin'">
                                <button
                                    class="btn waves-effect waves-light btn-xs btn-info"
                                    type="button"
                                    @click.prevent="clickCreate(row.id)"
                                >
                                    Editar
                                </button>
                                <button
                                    class="btn waves-effect waves-light btn-xs btn-danger"
                                    type="button"
                                    @click.prevent="clickDelete(row.id)"
                                >
                                    Eliminar
                                </button>
                                <button
                                    class="btn waves-effect waves-light btn-xs btn-warning"
                                    type="button"
                                    @click.prevent="duplicate(row.id)"
                                >
                                    Duplicar
                                </button>

                                <button
                                    v-if="row.active"
                                    class="btn waves-effect waves-light btn-xs btn-danger"
                                    type="button"
                                    @click.prevent="clickDisable(row.id)"
                                >
                                    Inhabilitar
                                </button>
                                <button
                                    v-else
                                    class="btn waves-effect waves-light btn-xs btn-primary"
                                    type="button"
                                    @click.prevent="clickEnable(row.id)"
                                >
                                    Habilitar
                                </button>

                                <button
                                    class="btn waves-effect waves-light btn-xs btn-primary"
                                    type="button"
                                    @click.prevent="clickBarcode(row)"
                                >
                                    Cod. Barras
                                </button>

                                <button
                                    class="btn waves-effect waves-light btn-xs btn-primary"
                                    type="button"
                                    @click.prevent="clickPrintBarcode(row)"
                                >
                                    Etiquetas
                                </button>
                            </template>
                        </td>
                    </tr>
                </data-table>
            </div>
            <items-form
                :recordId="recordId"
                :showDialog.sync="showDialog"
                :type="type"
                :pharmacy="pharmacy"
            ></items-form>
            <items-import
                :pharmacy="pharmacy"
                :showDialog.sync="showImportDialog"
            ></items-import>
            <catalog-import
                :pharmacy="pharmacy"
                :showDialog.sync="showDigemidImportDialog"
            ></catalog-import>

            <items-export
                :pharmacy="pharmacy"
                :showDialog.sync="showExportDialog"
            ></items-export>
            <items-export-wp
                :pharmacy="pharmacy"
                :showDialog.sync="showExportWpDialog"
            ></items-export-wp>

            <items-export-barcode
                :pharmacy="pharmacy"
                :showDialog.sync="showExportBarcodeDialog"
            ></items-export-barcode>

            <item-export-digemid
                :pharmacy="pharmacy"
                :showDialog.sync="showExportDigemid"
            ></item-export-digemid>

            <warehouses-detail
                :item_unit_types="item_unit_types"
                :showDialog.sync="showWarehousesDetail"
                :warehouses="warehousesDetail"
            >
            </warehouses-detail>

            <items-import-list-price
                :pharmacy="pharmacy"
                :showDialog.sync="showImportListPriceDialog"
            ></items-import-list-price>
        </div>
    </div>
</template>
<script>
import ItemsForm from "../../../../../../resources/js/views/tenant/items/form.vue";
import WarehousesDetail from "../../../../../../resources/js/views/tenant/items/partials/warehouses.vue";
import ItemsImport from "../../../../../../resources/js/views/tenant/items/import.vue";
import  CatalogImport from "../../../../../../resources/js/views/tenant/items/catalog.vue";
import ItemsImportListPrice from "../../../../../../resources/js/views/tenant/items/partials/import_list_price.vue";
import ItemsExport from "../../../../../../resources/js/views/tenant/items/partials/export.vue";
import ItemsExportWp from "../../../../../../resources/js/views/tenant/items/partials/export_wp.vue";
import ItemsExportBarcode from "../../../../../../resources/js/views/tenant/items/partials/export_barcode.vue";
import ItemExportDigemid from "../../../../../../resources/js/views/tenant/items/partials/export_digemid.vue";
import DataTable from "../../../../../../resources/js/views/tenant/items/../../../components/DataTable.vue";
import {deletable} from "../../../../../../resources/js/mixins/deletable";

export default {
    props: [
        "configuration",
        "typeUser",
        "type"
    ],
    mixins: [deletable],
    components: {
        ItemExportDigemid,
        ItemsForm,
        ItemsImport,
        ItemsExport,
        ItemsExportWp,
        ItemsExportBarcode,
        CatalogImport,
        DataTable,
        WarehousesDetail,
        ItemsImportListPrice,
    },
    data() {
        return {
            can_add_new_product: false,
            showDialog: false,
            showImportDialog: false,
            showDigemidImportDialog: false,
            showExportDialog: false,
            showExportWpDialog: false,
            showExportBarcodeDialog: false,
            showExportDigemid: false,
            showImportListPriceDialog: false,
            pharmacy: true,
            showWarehousesDetail: false,
            resource: "items",
            recordId: null,
            warehousesDetail: [],
            config: {},
            columns: {
                description: {
                    title: 'Descripción',
                    visible: false
                },
                item_code: {
                    title: 'Cód. SUNAT',
                    visible: false
                },
                purchase_unit_price: {
                    title: 'P.Unitario (Compra)',
                    visible: false
                },
                purchase_has_igv_description: {
                    title: 'Tiene Igv (Compra)',
                    visible: false
                },
                model: {
                    title: 'Modelo',
                    visible: false
                },
                brand: {
                    title: 'Marca',
                    visible: false
                },
                exportable_pharmacy: {
                    title: 'Exportable',
                    visible: true
                },
            },
            item_unit_types: [],
            titleTopBar: '',
            title: ''
        };
    },
    created() {
        if (this.type === 'ZZ') {
            this.titleTopBar = 'Servicios';
            this.title = 'Listado de servicios';
        } else {
            this.titleTopBar = 'Productos';
            this.title = 'Listado de productos DIGEMID';
        }
        this.$http.get(`/configurations/record`).then((response) => {
            this.config = response.data.data;
            this.$setStorage('configuration',this.config)
        });
        this.canCreateProduct();
    },
    computed: {
        columnsComputed: function () {
            return this.columns;
        }
    },
    methods: {
        updateExportable(id,row){
            row.exportable_pharmacy = !row.exportable_pharmacy;
            this.$http
                .post(`digemid/update_exportable/${id}`, {id})
                .then((response) => {
                    this.$eventHub.$emit("reloadData");
                })
                .catch((error) => {
                    this.$message.error("No se guardaron los cambios");
                });
            //
        },
        canCreateProduct() {
            if (this.typeUser === 'admin') {
                this.can_add_new_product = true
            } else if (this.typeUser === 'seller') {
                if (this.configuration !== undefined && this.configuration.seller_can_create_product !== undefined) {
                    this.can_add_new_product = this.configuration.seller_can_create_product;
                }
            }
            return this.can_add_new_product;
        },
        duplicate(id) {
            this.$http
                .post(`${this.resource}/duplicate`, {id})
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(
                            "Se guardaron los cambios correctamente."
                        );
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error("No se guardaron los cambios");
                    }
                })
                .catch((error) => {
                });
            this.$eventHub.$emit("reloadData");
        },
        clickWarehouseDetail(warehouses, item_unit_types) {
            this.warehousesDetail = warehouses;
            this.item_unit_types = item_unit_types
            this.showWarehousesDetail = true;
        },
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickImport() {
            this.showImportDialog = true;
        },
        clickDigemidImport() {
            this.showDigemidImportDialog = true;
        },
        clickExport() {
            this.showExportDialog = true;
        },
        clickExportWp() {
            this.showExportWpDialog = true;
        },
        clickExportBarcode() {
            this.showExportBarcodeDialog = true;
        },
        clickExportDigemid() {
            this.showExportDigemid = true;
        },
        clickImportListPrice() {
            this.showImportListPriceDialog = true;
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickDisable(id) {
            this.disable(`/${this.resource}/disable/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickEnable(id) {
            this.enable(`/${this.resource}/enable/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickBarcode(row) {
            if (!row.barcode) {
                return this.$message.error(
                    "Para generar el código de barras debe registrar el código de barras."
                );
            }

            window.open(`/${this.resource}/barcode/${row.id}`);
        },
        clickPrintBarcode(row) {
            if (!row.barcode) {
                return this.$message.error(
                    "Para generar el código de barras debe registrar el código de barras."
                );
            }

            window.open(`/${this.resource}/export/barcode/print?id=${row.id}`);
        },
    },
};
</script>
