<template>
    <div class="row card-table-report">
        <div class="col-md-12">
            <div
                 class="card card-primary" v-loading="loading">
                <div class="card-header">
                    <h4 class="card-title">Consulta de inventarios</h4>
                    <div class="data-table-visible-columns"
                         style="top:10px">
                        <el-dropdown :hide-on-click="false">
                            <el-button type="primary">
                                Mostrar/Ocultar filtros<i class="el-icon-arrow-down el-icon--right"></i>
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item v-for="(column, index) in filters"
                                                  :key="index">
                                    <el-checkbox v-model="column.visible">{{ column.title }}</el-checkbox>
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>

                </div>
                <div class="card-body">
                    <div class="row m-b-10">
                        <div class="col-md-4 mb-3">
                            <label class="control-label">Almacen</label>
                            <el-select v-model="form.warehouse_id"
                                       placeholder="Seleccionar almacén"
                                       @change="changeWarehouse">
                                <el-option key="all"
                                           label="Todos"
                                           value="all"></el-option>
                                <el-option v-for="opt in warehouses"
                                           :key="opt.id"
                                           :label="opt.description"
                                           :value="opt.id">
                                </el-option>
                            </el-select>
                        </div>
                        <div v-if="filters.categories.visible"
                             class="col-md-3">
                            <label class="control-label">Categoría</label>
                            <el-select
                                v-model="form.category_id"
                                clearable
                                filterable
                                placeholder="Seleccionar categoría"
                                @change="changeFilter">
                                <el-option v-for="option in categories"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                        <div v-if="filters.brand.visible"
                             class="col-md-3">
                            <label class="control-label">Marca</label>
                            <el-select
                                v-model="form.brand_id"
                                clearable
                                filterable
                                placeholder="Seleccionar marca"
                                @change="changeFilter">
                                <el-option v-for="option in brands"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>

                        </div>
                        <div class="col-md-3">
                            <label class="control-label">Por stock</label>

                            <el-select v-model="form.filter"
                                       placeholder="Seleccionar filtro"
                                       @change="changeFilter">
                                <el-option key="01"
                                           label="Todos"
                                           value="01"></el-option>
                                <el-option key="02"
                                           label="Stock < 0"
                                           value="02"></el-option>
                                <el-option key="03"
                                           label="Stock = 0"
                                           value="03"></el-option>
                                <el-option key="04"
                                           label="0 < Stock <= Stock mínimo"
                                           value="04"></el-option>
                                <el-option key="05"
                                           label="Stock > Stock mínimo"
                                           value="05"></el-option>
                            </el-select>
                        </div>

                        <div v-if="filters.active.visible"
                             class="col-md-3">
                            <label class="control-label">Estado del item</label>
                            <el-select v-model="form.active"
                                       :clearable="true"
                                       placeholder="Seleccionar filtro"
                                       @change="changeFilter">
                                <el-option key="01"
                                           label="Habilitados"
                                           value="01"></el-option>
                                <el-option key="00"
                                           label="Inhabilitados"
                                           value="00"></el-option>
                            </el-select>
                        </div>

                        <div v-if="filters.range.visible"
                             class="col-md-3">
                            <label class="control-label">Fecha de vencimiento - inicio</label>
                            <el-date-picker v-model="form.date_start"
                                            :clearable="true"
                                            format="dd/MM/yyyy"
                                            type="date"
                                            value-format="yyyy-MM-dd"
                                            @change="changeDisabledDates"></el-date-picker>
                        </div>
                        <div v-if="filters.range.visible"
                             class="col-md-3">
                            <label class="control-label">Fecha de vencimiento - Fecha término</label>
                            <el-date-picker v-model="form.date_end"
                                            :clearable="true"
                                            :picker-options="pickerOptionsDates"
                                            format="dd/MM/yyyy"
                                            type="date"
                                            value-format="yyyy-MM-dd"
                                            @change="changeDisabledDates"></el-date-picker>
                        </div>

                        <div class="col-12">&nbsp;</div>
                        <div class="col-auto">
                            <el-button :disabled="records.length <= 0"
                                       :loading="loadingPdf"
                                       @click="clickExport('pdf')"><i class="fa fa-file-pdf"></i> Exportar PDF
                            </el-button>
                        </div>
                        <div class="col-auto">
                            <el-button :disabled="records.length <= 0"
                                       :loading="loadingXlsx"
                                       @click="clickExport('xlsx')"><i class="fa fa-file-excel"></i> Exportar Excel
                            </el-button>
                        </div>
                    </div>

                    <div v-if="records.length > 0"
                         class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-responsive-xl table-bordered table-hover"
                                >
                                    <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th v-if="filters.model.visible">Modelo</th>
                                        <th>Categoria</th>
                                        <th class="text-right">Stock mínimo</th>
                                        <th class="text-right">Stock actual</th>
                                        <th class="text-right">Precio de venta</th>
                                        <th class="text-right">Costo</th>
                                        <th>Ganancia
                                            <el-tooltip
                                                class="item"
                                                content="Precio de venta - Costo"
                                                effect="dark"
                                                placement="top-start"
                                            >
                                                <i class="fa fa-info-circle"></i>
                                            </el-tooltip>
                                        </th>
                                        <th>Ganancia Total
                                            <el-tooltip
                                                class="item"
                                                content="Precio de venta - Costo * Cantidad"
                                                effect="dark"
                                                placement="top-start"
                                            >
                                                <i class="fa fa-info-circle"></i>
                                            </el-tooltip>
                                        </th>
                                        <th>Marca</th>
                                        <th class="text-center">F. vencimiento</th>
                                        <th>Almacén</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in records"
                                        :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ row.name }}</td>
                                        <td v-if="filters.model.visible">{{ row.model }}</td>
                                        <td>{{ row.item_category_name }}</td>
                                        <td class="text-right">{{ row.stock_min }}</td>
                                        <td class="text-right">{{ row.stock }}</td>
                                        <td class="text-right">{{ row.sale_unit_price }}</td>
                                        <td class="text-right">{{ row.purchase_unit_price }}</td>
                                        <td class="text-right">{{ row.profit }}</td>
                                        <td class="text-right">{{ Math.abs(row.profit * row.stock).toFixed(2) }}</td>
                                        <td>{{ row.brand_name }}</td>
                                        <td class="text-center">{{ row.date_of_due }}</td>
                                        <td>{{ row.warehouse_name }}</td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td class="celda"
                                            colspan="5"></td>
                                        <td class="celda">S/ {{ totals.sale_unit_price }}</td>
                                        <td class="celda">S/ {{ totals.purchase_unit_price }}</td>

                                        <td class="celda">S/ {{ total_profit }}</td>
                                        <td class="celda">S/ {{ total_all_profit }}</td>
                                    </tr>
                                    </tfoot>
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
                        <div class="col-md-12">
                            Total {{ records.length }}
                        </div>
                    </div>
                    <div v-else
                         class="row">
                        <div class="col-md-12">
                            <strong>No se encontraron registros</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import moment from "moment";
import queryString from "query-string";
export default {
    props: [],
    data() {
        return {
            // loading_submit: false,
            // showDialogLots: false,
            // showDialogLotsOutput: false,
            // titleDialog: null,
            total_profit: 0,
            total_all_profit: 0,
            loading: false,
            loadingPdf: false,
            loadingXlsx: false,
            resource: 'inventory/report',
            errors: {},
            form: {},
            warehouses: [],
            categories: [],
            brands: [],
            filters: [],
            records: [],
            totals: {
                purchase_unit_price: 0,
                sale_unit_price: 0,
            },
            pickerOptionsDates: {
                disabledDate: (time) => {
                    time = moment(time).format('YYYY-MM-DD')
                    return this.form.date_start > time
                }
            },
            pagination: {},
        }
    },
    created() {
        this.initTables();
        this.initForm();
        this.filters = {
            categories: {
                title: 'Categorias',
                visible: false
            },
            model: {
                title: 'Modelo',
                visible: false
            },
            brand: {
                title: 'Marcas',
                visible: false
            },
            active: {
                title: 'Estado',
                visible: false
            },
            range: {
                title: 'Rango de fechas',
                visible: false
            },
        }
    },
    methods: {
        changeDisabledDates() {
            if (this.form.date_end < this.form.date_start) {
                this.form.date_end = this.form.date_start
            }
            this.getRecords();
        },
        initTotals() {

            this.totals = {
                purchase_unit_price: 0,
                sale_unit_price: 0,
            }

        },
        initForm() {
            this.form = {
                'warehouse_id': null,
                'filter': '01',
                'category_id': null,
                'brand_id': null,
                active: null
            }
        },
        calculeTotalProfit() {

            this.total_profit = 0;
            this.total_all_profit = 0;
            this.total_purchase_unit_price = 0;
            this.total_sale_unit_price = 0;

            if (this.records.length > 0) {

                let el = this;
                this.records.forEach(function (a, b) {

                    el.total_profit += Math.abs(a.profit);
                    el.total_all_profit += Math.abs(a.profit * a.stock);

                    el.totals.purchase_unit_price += parseFloat(a.purchase_unit_price)
                    el.totals.sale_unit_price += parseFloat(a.sale_unit_price)

                })

            }

            this.total_profit = this.total_profit.toFixed(2)
            this.total_all_profit = this.total_all_profit.toFixed(2)

            this.totals.purchase_unit_price = this.totals.purchase_unit_price.toFixed(6)
            this.totals.sale_unit_price = this.totals.sale_unit_price.toFixed(6)

        },
        initTables() {
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.warehouses = response.data.warehouses;
                    this.brands = response.data.brands;
                    this.categories = response.data.categories;
                });
        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                ...this.form
            });
        },
        async getRecords() {

            if (_.isNull(this.form.warehouse_id)) {
                this.$message.error('Seleccionar un almacén ');
                return false;
            }

            this.loading = true

            this.records = [];
            this.total_profit = 0;
            this.total_all_profit = 0;
            this.initTotals()
            let range = this.filters.range.visible
            if (range !== true) {
                delete this.form.date_start
                delete this.form.date_end
            }

            await this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`)
                .then(response => {
                    this.records = response.data.data;
                    this.pagination = response.data.meta
                    this.pagination.per_page = parseInt(response.data.meta.per_page)
                    this.calculeTotalProfit()
                })
            this.loading = false;
        },
        changeWarehouse() {
            this.getRecords();
        },
        changeFilter() {
            this.getRecords();
        },
        async clickExport(format) {
            this.loading = true;
            this.loadingSubmit = true;
            this.loadingPdf = (format === 'pdf');
            this.loadingXlsx = (format === 'xlsx');
            this.errors = {};
            await this.$http({
                url: `/${this.resource}/export`,
                method: 'POST',
                data: {
                    'format': format,
                    'filter': this.form.filter,
                    'warehouse_id': this.form.warehouse_id,
                    brand_id: this.form.brand_id,
                    category_id: this.form.category_id,
                },
            })
                .then(response => {
                    let res = response.data;
                    if (res.success) {
                        this.$message.success(res.message);
                    } else {
                        this.$message.error('Error al exportar');
                        /*const url = window.URL.createObjectURL(new Blob([res]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'ReporteInv_' + moment().format('HHmmss') + '.' + format);
                        document.body.appendChild(link);
                        link.click();*/
                    }
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error;
                })
                .then(() => {
                    this.loadingPdf = false;
                    this.loadingXlsx = false;
                    this.loading = false;
                });
            // this.loadingSubmit = false;
            //

        }
    }
}
</script>
