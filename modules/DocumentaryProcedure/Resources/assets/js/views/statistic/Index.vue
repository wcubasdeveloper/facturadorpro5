<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>
                    Estadísticas de Trámites
                    </span>
                </li>
            </ol>
            <div class="right-wrapper pull-right">
                <div class="btn-group flex-wrap">
                    <!--
                    <button
                        class="btn btn-custom btn-sm mt-2 mr-2"
                        type="button"
                        @click="onCreate"
                    >
                        <i class="fa fa-plus-circle"></i> Nuevo
                    </button>
                    -->
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">
                    Estadísticas de Trámites
                </h3>
            </div>
            <div class="card-body">
                <div class="row">


                    <div class="col-md-3">
                        <label class="control-label">Periodo</label>
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


                    <div class="col-lg-7 col-md-7 col-md-7 col-sm-12"
                         style="margin-top:29px">
                        <el-button
                            :loading="loading"
                            class="submit"
                            icon="el-icon-search"
                            type="primary"
                            @click.prevent="onFilter">
                            Buscar
                        </el-button>


                            <el-button
                                v-if="total  > 0"
                                class="submit"
                                icon="el-icon-tickets"
                                type="danger"
                                @click.prevent="clickDownload('pdf')">
                                Exportar PDF
                            </el-button>

                        <!--
                        // EN EXCEL; NO CUADRA LAS CELDAS CORRECTAMENTE
                            <el-button
                                v-if="total  > 0"
                                class="submit"
                                type="success"
                                @click.prevent="clickDownload('excel')">
                                <i class="fa fa-file-excel"></i>
                                Exportar Excel
                            </el-button>
                        -->


                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td v-for="(item,index) in items">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{ item.month }}</th>
                                    </tr>
                                    </thead>
                                    <tr>

                                        <td>Total: {{ item.total }}</td>
                                    </tr>
                                    <tr>
                                        <td>Completados: {{ item.complete }}</td>
                                    </tr>
                                    <tr>
                                        <td>En Proceso: {{ item.process }}</td>
                                    </tr>
                                    <tr>

                                        <td>Porcentaje tramitado: {{
                                                (item.complete * 100) / item.total|toDecimals
                                            }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import ModalAddEdit from "./ModalAddEdit";
import {mapActions, mapState} from "vuex";
import moment from "moment";
import queryString from 'query-string'

export default {
    props: {
        configuration: {},
    },
    components: {
        // ModalAddEdit,
    },
    computed: {
        ...mapState([
            'config'
        ])
    },
    data() {
        return {
            items: [],
            total: 0,
            loading: false,
            form: {
                period: 'month',
                user_type: null,
                user_id: [],
            },
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
            basePath: '/documentary-procedure/stadistic'
        };
    },
    created() {
        this.$store.commit('setConfiguration', this.configuration);
        this.loadConfiguration()
    },
    mounted() {
        // this.form.user_id.push(this.config.user.id)
    },
    methods: {
        ...mapActions([
            'loadWorkers',
            'loadConfiguration',
            'loadStatusDocumentary'
        ]),
        onFilter() {
            this.loading = true;
            this.items= [];
            this.total= 0;
            this.$http
                .post(this.basePath, this.form)
                .then((response) => {
                    this.items = response.data.statistic
                    this.total = response.data.total
                })
                .finally(() => {
                    this.loading = false;
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
        clickDownload(type) {
            let query = queryString.stringify({
                ...this.form
            });
            delete (query.user_id)
            delete (query.document_type_id)

            window.open(`${this.basePath}/${type}/?${query}&user_id=${JSON.stringify(this.form.user_id)}`, '_blank');
        },
    },
};
</script>
