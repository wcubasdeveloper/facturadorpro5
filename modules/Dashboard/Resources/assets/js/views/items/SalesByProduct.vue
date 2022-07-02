<template>
    <div>
        <header
            class="page-header"
            style="display: flex; justify-content: space-between; align-items: center">
            <div>
                <h2>Ventas por producto</h2>
            </div>
        </header>
        <div class="card mb-0">
            <div class="row">
                <div class="col-12">
                    <section class="card card-dashboard">
                        <div class="card-body pt-2 pb-0">
                            <div class="row border-bottom mb-2 no-gutters">
                                <small class="col-12 text-muted text-center">Filtrar datos históricos</small>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-3 form-group">
                                    <label class="control-label">Establecimiento</label>
                                    <el-select v-model="form.establishment_id" @change="loadAll">
                                        <el-option
                                        v-for="option in establishments"
                                        :key="option.id"
                                        :value="option.id"
                                        :label="option.name"
                                        ></el-option>
                                    </el-select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="control-label">Periodo</label>
                                    <el-select v-model="form.period" @change="changePeriod">
                                        <el-option key="all" value="all" label="Todos"></el-option>
                                        <el-option key="last_week" value="last_week" label="Última semana"></el-option>
                                        <el-option key="month" value="month" label="Por mes"></el-option>
                                        <el-option key="between_months" value="between_months" label="Entre meses"></el-option>
                                        <el-option key="date" value="date" label="Por fecha"></el-option>
                                        <el-option key="between_dates" value="between_dates" label="Entre fechas"></el-option>
                                    </el-select>
                                </div>
                                <template v-if="form.period === 'month' || form.period === 'between_months'">
                                    <div class="col-6 col-md-3">
                                        <label class="control-label">Mes de</label>
                                        <el-date-picker
                                            v-model="form.month_start"
                                            type="month"
                                            @change="changeDisabledMonths"
                                            value-format="yyyy-MM"
                                            format="MM/yyyy"
                                            :clearable="false"
                                        ></el-date-picker>
                                    </div>
                                </template>
                                <template v-if="form.period === 'between_months'">
                                    <div class="col-6 col-md-3">
                                        <label class="control-label">Mes al</label>
                                        <el-date-picker
                                            v-model="form.month_end"
                                            type="month"
                                            :picker-options="pickerOptionsMonths"
                                            @change="loadAll"
                                            value-format="yyyy-MM"
                                            format="MM/yyyy"
                                            :clearable="false"
                                        ></el-date-picker>
                                    </div>
                                </template>
                                <template v-if="form.period === 'date' || form.period === 'between_dates'">
                                    <div class="col-6 col-md-3">
                                        <label class="control-label">Fecha del</label>
                                        <el-date-picker
                                            v-model="form.date_start"
                                            type="date"
                                            @change="changeDisabledDates"
                                            value-format="yyyy-MM-dd"
                                            format="dd/MM/yyyy"
                                            :clearable="false"
                                        ></el-date-picker>
                                    </div>
                                </template>
                                <template v-if="form.period === 'between_dates'">
                                    <div class="col-6 col-md-3">
                                        <label class="control-label">Fecha al</label>
                                        <el-date-picker
                                            v-model="form.date_end"
                                            type="date"
                                            :picker-options="pickerOptionsDates"
                                            @change="loadAll"
                                            value-format="yyyy-MM-dd"
                                            format="dd/MM/yyyy"
                                            :clearable="false"
                                        ></el-date-picker>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <section class="card card-dashboard">
                        <div class="card-body" v-if="loaders.items_by_sales">
                            <template>
                                <loader-graph :rows="1" :columns="1" :radius="100" :hideCircle="true"></loader-graph>
                            </template>
                        </div>
                        <div class="card-body pb-0" v-show="!loaders.items_by_sales">
                            <div class="mt-3">
                                <el-checkbox  v-model="form.enabled_move_item" @change="loadDataAditional">Ordenar por movimientos</el-checkbox><br>
                            </div>
                        </div>
                        <div class="card-body p-0" v-show="!loaders.items_by_sales">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th class="text-right">
                                                Movimientos
                                                <el-tooltip class="item" effect="dark" content=" Cantidad de veces vendido" placement="top-start"><i class="fa fa-info-circle"></i></el-tooltip>
                                            </th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(row, index) in items_by_sales">
                                            <tr :key="index">
                                                <td>{{ Number(index)+1 }}</td>
                                                <td>{{ row.internal_id }}</td>
                                                <td>{{ row.description }}</td>
                                                <td class="text-right">{{ row.move_quantity }}</td>
                                                <td class="text-right">{{ row.total }}</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <el-pagination
                                    @current-change="loadDataAditional"
                                    layout="prev, pager, next"
                                    :total="pagination.total"
                                    :current-page.sync="pagination.current_page"
                                    :page-size="pagination.per_page"
                                >
                                </el-pagination>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import LoaderGraph from "../../components/loaders/l-graph.vue";
import queryString from 'query-string'

export default {
    components: { LoaderGraph },
    data() {
        return {
            resource: "dashboard",
            form: {},
            filter_item: false,
            items_by_sales: [],
            establishments: [],
            pickerOptionsMonths: {
                disabledDate: (time) => {
                time = moment(time).format("YYYY-MM");
                return this.form.month_start > time;
                },
            },
            pickerOptionsDates: {
                disabledDate: (time) => {
                time = moment(time).format("YYYY-MM-DD");
                return this.form.date_start > time;
                },
            },
            pagination: {},
        }
    },
    async created() {
        this.initLoaders();
        this.initForm();
        await this.$http.get(`/${this.resource}/filter`).then((response) => {
            this.establishments = response.data.establishments;
            this.form.establishment_id = this.establishments.length > 0 ? this.establishments[0].id : null;
        });
        await this.loadAll();
    },
    methods: {
        initLoaders() {
            this.loaders = {
                items_by_sales: true,
            };
        },
        initForm() {
            this.form = {
                item_id: null,
                establishment_id: null,
                enabled_expense: null,
                enabled_move_item: false,
                enabled_transaction_customer: false,
                period: "month",
                date_start: moment().subtract(7, 'days').format("YYYY-MM-DD"),
                date_end: moment().format("YYYY-MM-DD"),
                month_start: moment().format("YYYY-MM"),
                month_end: moment().format("YYYY-MM"),
                customer_id: null,
                no_take: true,
                page: this.pagination.current_page,
            };
        },
        loadAll() {
            this.loadDataAditional();
        },
        loadDataAditional() {
            this.showLoadersLoadDataAditional();

            this.$http
                .post(`/${this.resource}/data_aditional?${this.getQueryParameters()}`, this.form)
                .then((response) => {
                this.items_by_sales = response.data.data.items_by_sales.data;
                this.pagination = response.data.data.items_by_sales;
                this.hideLoadersLoadDataAditional();
            });
        },
        showLoadersLoadDataAditional() {
            this.loaders.items_by_sales = true;
        },
        hideLoadersLoadDataAditional() {
            this.loaders.items_by_sales = false;
        },
        changeDisabledMonths() {
            if (this.form.month_end < this.form.month_start) {
                this.form.month_end = this.form.month_start;
            }
            this.loadAll();
        },
        changePeriod() {
            if (this.form.period === "month") {
                this.form.month_start = moment().format("YYYY-MM");
                this.form.month_end = moment().format("YYYY-MM");
            }
            if (this.form.period === "between_months") {
                this.form.month_start = moment().startOf("year").format("YYYY-MM"); //'2019-01';
                this.form.month_end = moment().endOf("year").format("YYYY-MM");
            }
            if (this.form.period === "date") {
                this.form.date_start = moment().format("YYYY-MM-DD");
                this.form.date_end = moment().format("YYYY-MM-DD");
            }
            if (this.form.period === "between_dates") {
                this.form.date_start = moment().startOf("month").format("YYYY-MM-DD");
                this.form.date_end = moment().endOf("month").format("YYYY-MM-DD");
            }
            this.loadAll();
        },
        changeDisabledDates() {
            if (this.form.date_end < this.form.date_start) {
                this.form.date_end = this.form.date_start;
            }
            this.loadAll();
        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: 10
            })
        },
    }
};
</script>
