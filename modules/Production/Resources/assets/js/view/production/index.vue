<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>
                        Productos Fabricados
                    </span>
                </li>
            </ol>
            <div class="right-wrapper pull-right">
                <template
                >
                    <button
                        class="btn btn-custom btn-sm  mt-2 mr-2"
                        type="button"
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
                    Productos Fabricados
                </h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Fecha de envio</label>
                        <el-select v-model="form.period"
                                   @change="changePeriod">
                            <el-option key="month"
                                       label="Por mes"
                                       value="month"></el-option>
                            <el-option key="between_months"
                                       label="Entre meses"
                                       value="between_months"></el-option>
                            <el-option key="date"
                                       label="Por fecha"
                                       value="date"></el-option>
                            <el-option key="between_dates"
                                       label="Entre fechas"
                                       value="between_dates"></el-option>
                        </el-select>
                    </div>
                    <template v-if="form.period === 'month' || form.period === 'between_months'">
                        <div class="col-md-3">
                            <label class="control-label">Mes de</label>
                            <el-date-picker v-model="form.month_start"
                                            :clearable="false"
                                            format="MM/yyyy"
                                            type="month"
                                            value-format="yyyy-MM"
                                            @change="changeDisabledMonths"></el-date-picker>
                        </div>
                    </template>
                    <template v-if="form.period === 'between_months'">
                        <div class="col-md-3">
                            <label class="control-label">Mes al</label>
                            <el-date-picker v-model="form.month_end"
                                            :clearable="false"
                                            :picker-options="pickerOptionsMonths"
                                            format="MM/yyyy"
                                            type="month"
                                            value-format="yyyy-MM"></el-date-picker>
                        </div>
                    </template>
                    <template v-if="form.period === 'date' || form.period === 'between_dates'">
                        <div class="col-md-3">
                            <label class="control-label">Fecha del</label>
                            <el-date-picker v-model="form.date_start"
                                            :clearable="false"
                                            format="dd/MM/yyyy"
                                            type="date"
                                            value-format="yyyy-MM-dd"
                                            @change="changeDisabledDates"></el-date-picker>
                        </div>
                    </template>
                    <template v-if="form.period === 'between_dates'">
                        <div class="col-md-3">
                            <label class="control-label">Fecha al</label>
                            <el-date-picker v-model="form.date_end"
                                            :clearable="false"
                                            :picker-options="pickerOptionsDates"
                                            format="dd/MM/yyyy"
                                            type="date"
                                            value-format="yyyy-MM-dd"></el-date-picker>
                        </div>
                    </template>
                    <div class="col-12 mt-4">
                        <el-button :loading="loading_submit"
                                   class="submit"
                                   icon="el-icon-search"
                                   type="primary"
                                   @click.prevent="getRecordsByFilter">Buscar
                        </el-button>


                        <!--                        <el-button class="submit" type="danger"  icon="el-icon-tickets" @click.prevent="clickDownloadPdf()" >Exportar PDF</el-button>-->

                        <el-button class="submit"
                                   type="success"
                                   @click.prevent="clickDownloadExcel()"><i class="fa fa-file-excel"></i> Exportal Excel
                                                                                                          (Productos
                                                                                                          fabricados)
                        </el-button>
                        <el-button class="submit"
                                   type="success"
                                   @click.prevent="clickDownloadExcel2()"><i class="fa fa-file-excel"></i> Exportal
                                                                                                           Excel
                                                                                                           (Productos en
                                                                                                           proceso)
                        </el-button>
                    </div>
                    <div class="col-12 p-t-20 table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Número de Ficha</th>
                                <th>Cód. Interno</th>
                                <th>Tipo de proceso</th>
                                <th>Prod. F. de inicio</th>
                                <th>Prod. F. de Fin</th>
                                <th>Colaborador de producción</th>
                                <th>Cantidad</th>
                                <th>Conformes</th>
                                <th>Defectuosas</th>
                                <th>Maquinaria</th>
                                <th>Lote</th>
                                <th>Color</th>
                                <th>Producto</th>
                                <th>Orden de Producción</th>
                                <th>Mez. F. de inicio</th>
                                <th>Mez. F. de Fin</th>
                                <th>Colaborador de mezcla</th>
                                <th>Comentario</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in records">
                                <td>{{ index + 1 }}</td>
                                <td>{{ row.name }}</td>
                                <td>000{{ row.id }}</td>
                                <td>{{ row.proccess_type }}</td>
                                <td>{{ row.date_start }} - {{ row.time_start }}</td>
                                <td>{{ row.date_end }} - {{ row.time_end }}</td>
                                <td>{{ row.production_collaborator }}</td>
                                <td>{{ row.quantity }}</td>
                                <td>{{ row.agreed }}</td>
                                <td>{{ row.imperfect }}</td>
                                <td>
                                    <div v-if="row.machine && row.machine.name">
                                        {{ row.machine.name }}
                                    </div>
                                </td>
                                <td>{{ row.lot_code }}</td>
                                <td>{{ row.color }}</td>
                                <td>{{ row.item_name }}</td>
                                <td>{{ row.production_order }}</td>
                                <td>{{ row.mix_date_start }} - {{ row.mix_time_start }}</td>
                                <td>{{ row.mix_date_end }} - {{ row.mix_time_end }}</td>
                                <td>{{ row.mix_collaborator }}</td>
                                <td>{{ row.comment }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div>
                            <el-pagination
                                @current-change="getRecords"
                                layout="total, prev, pager, next"
                                :total="pagination.total"
                                :current-page.sync="pagination.current_page"
                                :page-size="pagination.per_page">
                            </el-pagination>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</template>
<script>


import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import {deletable} from "../../../../../../../resources/js/mixins/deletable";
import moment from "moment";
import queryString from 'query-string'

export default {
    props: [
        'configuration',
        'typeUser',
    ],
    mixins: [deletable],
    components: {},
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
            loading_submit: false,
            showImportSetDialog: false,
            showImportSetIndividualDialog: false,
            showWarehousesDetail: false,
            resource: 'production',
            recordId: null,
            form: {},
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
            records: [],

            pickerOptionsDates: {
                disabledDate: (time) => {
                    time = moment(time).format('YYYY-MM-DD')
                    return this.form.date_start > time
                }
            },
            pickerOptionsMonths: {
                disabledDate: (time) => {
                    time = moment(time).format('YYYY-MM')
                    return this.form.month_start > time
                }
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
        this.initForm();
        this.$eventHub.$on('reloadData', () => {
            this.getRecords()
        })
        this.getRecords()


        //this.canCreateProduct();

    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        initForm() {

            this.form = {
                period: 'month',
                date_start: moment().format('YYYY-MM-DD'),
                date_end: moment().format('YYYY-MM-DD'),
                month_start: moment().format('YYYY-MM'),
                month_end: moment().format('YYYY-MM'),
            }

        },
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
            window.open(`/${this.resource}/pdf?${this.getQueryParameters()}`, '_blank');
        },
        clickDownloadExcel() {
            window.open(`/${this.resource}/excel?${this.getQueryParameters()}`, '_blank');
        },
        clickDownloadExcel2() {
            window.open(`/${this.resource}/excel2?${this.getQueryParameters()}`, '_blank');
        },

        async getRecordsByFilter() {
            /*
                          if(!this.form.item_id){
                              return this.$message.error('Debe seleccionar un producto')
                          }
          */
            this.loading_submit = await true
            await this.getRecords()
            this.loading_submit = await false

        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                form: JSON.stringify(this.form),
                limit: this.limit
            })
        },
        getRecords() {
            return this.$http
                .get(`/${this.resource}/records?${this.getQueryParameters()}`)
                .then(response => {
                    this.records = response.data.data;
                    this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    );
                })
                .catch(error => {
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
        changePeriod() {
            if (this.form.period === 'month') {
                this.form.month_start = moment().format('YYYY-MM');
                this.form.month_end = moment().format('YYYY-MM');
            }
            if (this.form.period === 'between_months') {
                this.form.month_start = moment().startOf('year').format('YYYY-MM'); //'2019-01';
                this.form.month_end = moment().endOf('year').format('YYYY-MM');

            }
            if (this.form.period === 'date') {
                this.form.date_start = moment().format('YYYY-MM-DD');
                this.form.date_end = moment().format('YYYY-MM-DD');
            }
            if (this.form.period === 'between_dates') {
                this.form.date_start = moment().startOf('month').format('YYYY-MM-DD');
                this.form.date_end = moment().endOf('month').format('YYYY-MM-DD');
            }
            // this.loadAll();
        },

        changeDisabledDates() {
            if (this.form.date_end < this.form.date_start) {
                this.form.date_end = this.form.date_start
            }
            // this.loadAll();
        },
        changeDisabledMonths() {
            if (this.form.month_end < this.form.month_start) {
                this.form.month_end = this.form.month_start
            }
            // this.loadAll();
        },

    }
}
</script>
