<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>
                        Zona de embalaje
                    </span>
                </li>
            </ol>
            <div class="right-wrapper pull-right">
                <template
                >
                    <button
                        type="button"
                        class="btn btn-custom btn-sm  mt-2 mr-2"
                        @click.prevent="clickCreate()"
                    >
                        <i class="fa fa-plus-circle"></i>
                        Nuevo
                    </button>
                </template>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">
                    Zona de embalaje
                </h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-12">

                        <el-button class="submit" type="danger"  icon="el-icon-tickets" @click.prevent="clickDownloadPdf()" >Exportar PDF</el-button>

                        <el-button class="submit" type="success" @click.prevent="clickDownloadExcel()"><i class="fa fa-file-excel" ></i>  Exportal Excel</el-button>
                    </div>
                    <div class="col-12 p-t-20 table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Número de registro</th>
                                <th>Número de ficha</th>
                                <th>Producto</th>
                                <th>Usuario</th>
                                <th>Establecimiento</th>
                                <th>Cantidad</th>
                                <th># Paquetes</th>
                                <th>Lote</th>
                                <th>Fecha de inicio</th>
                                <th>Fecha de fin</th>
                                <th>Colaborador</th>
                                <th>Comentario</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in records">
                                <td>{{ index + 1 }}</td>
                                <td>000{{ row.id}}</td>
                                <td>{{ row.name}}</td>
                                <td>{{ row.item.name}}</td>
                                <td>{{ row.user}}</td>
                                <td>{{ row.stablishment}}</td>
                                <td>{{ row.quantity}}</td>
                                <td>{{ row.number_packages}}</td>
                                <td>{{ row.lot_code}}</td>
                                <td >{{ row.date_start }} - {{row.time_start}}</td>
                                <td >{{ row.date_end }} - {{row.time_end}}</td>
                                <td>{{ row.packaging_collaborator}}</td>
                                <td>{{ row.observation}}</td>


                            </tr>
                            </tbody>
                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>
</template>
<script>


import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import {deletable} from "../../../../../../../resources/js/mixins/deletable";

export default {
    props: [
        'configuration',
        'typeUser',
    ],
    mixins: [deletable],
    components: {
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
            resource: 'packaging',
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
            pagination: {},
            records: []
        }
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
        if (this.config.is_pharmacy !== true) {
            // delete this.columns.sanitary;
            // delete this.columns.cod_digemid;
        }

        return this.$http
                .get(`/${this.resource}/records`)
                .then(response => {
                    this.records = response.data.data;
                    this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    );
                })
                .catch(error => {})
                .then(() => {
                    this.loading_submit = false;
                });

        //this.canCreateProduct();

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
            window.location.href = `./${this.resource}/create`;


            // this.recordId = recordId
            // this.showDialog = true
        },
        clickImportSet() {
            this.showImportSetDialog = true
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },
        clickDownloadPdf() {
            window.open(`${this.resource}/pdf`, '_blank');
        },
        clickDownloadExcel() {
            window.open(`${this.resource}/excel`, '_blank');
        },
    }
}
</script>
