<template>
    <div>
        <!-- modificar componente DataTable en Comisiones vendedores - utilidades (comparten los mismos campos) -->
        <div class="row">

            <div class="col-md-12 col-lg-12 col-xl-12 ">

                <div class="row mt-2">
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

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Tipo de usuario</label>
                            <el-select v-model="form.user_type">
                                <el-option key="user_id"
                                        label="Registrado por"
                                        value="user_id"></el-option>
                                <el-option key="seller_id"
                                        label="Vendedor asignado"
                                        value="seller_id"></el-option>
                            </el-select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label class="control-label">
                                {{ form.user_type === 'user_id' ? 'Usuario' : 'Vendedor' }}
                            </label>
                            <el-select v-model="form.user_seller_id"
                                       clearable
                                       filterable
                                       placeholder="Nombre usuario"
                                       popper-class="el-select-customers">
                                <el-option v-for="option in sellers"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Establecimiento</label>
                            <el-select v-model="form.establishment_id"
                                       clearable>
                                <el-option v-for="option in establishments"
                                           :key="option.id"
                                           :label="option.name"
                                           :value="option.id"></el-option>
                            </el-select>
                        </div>
                    </div>

                </div>

                <div class="row">
                    
                    <div class="col-lg-7 col-md-7 col-md-7 col-sm-12"
                         style="margin-top:29px">
                        <el-button :loading="loading_submit"
                                   class="submit"
                                   icon="el-icon-search"
                                   type="primary"
                                   @click.prevent="getRecordsByFilter">Buscar
                        </el-button>

                        <template v-if="records.length>0">

                            <el-button class="submit"
                                       icon="el-icon-tickets"
                                       type="danger"
                                       @click.prevent="clickDownload('pdf')">Exportar PDF
                            </el-button>

                            <el-button class="submit"
                                       type="success"
                                       @click.prevent="clickDownload('excel')"><i class="fa fa-file-excel"></i> Exportal
                                                                                                                Excel
                            </el-button>

                        </template>
                    </div>
                </div>
                <div class="row mt-1 mb-4">

                </div>
            </div>


            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <slot name="heading"></slot>
                        </thead>
                        <tbody>
                        <slot v-for="(row, index) in records"
                              :index="customIndex(index)"
                              :row="row"></slot>
                        </tbody> 
                    </table>
                    <div>
                        <el-pagination
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page"
                            :total="pagination.total"
                            layout="total, prev, pager, next"
                            @current-change="getRecords">
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<style>
.font-custom {
    font-size: 15px !important
}
</style>
<script>

import moment from 'moment'
import queryString from 'query-string'

export default {
    props: {
        resource: String,
    },
    data() {
        return {
            loading_submit: false,
            columns: [],
            records: [],
            document_types: [],
            pagination: {},
            search: {},
            establishment: null,
            establishments: [],
            web_platforms: [],
            form: {},
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
            sellers: []
        }
    },
    computed: {

    },
    created() {
        this.initForm()
        this.$eventHub.$on('reloadData', () => {
            this.getRecords()
        })
    },
    async mounted() {

        await this.$http.get(`/${this.resource}/filter`)
            .then(response => {
                this.establishments = response.data.establishments;
                this.sellers = response.data.sellers
                this.web_platforms = response.data.web_platforms
            });


        await this.getRecords()

    },
    methods: {
        clickDownload(type) {
            let query = queryString.stringify({
                ...this.form
            });
            window.open(`/${this.resource}/${type}/?${query}`, '_blank');
        },
        initForm() {

            this.form = {
                user_type: 'user_id',
                establishment_id: null,
                period: 'month',
                date_start: moment().format('YYYY-MM-DD'),
                date_end: moment().format('YYYY-MM-DD'),
                month_start: moment().format('YYYY-MM'),
                month_end: moment().format('YYYY-MM'),
                user_seller_id: null,
            }

        },
        customIndex(index) {
            return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
        },
        async getRecordsByFilter() {

            this.loading_submit = await true
            await this.getRecords()
            this.loading_submit = await false

        },
        getRecords() {
            return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`).then((response) => {
                this.records = response.data.data
                this.pagination = response.data.meta
                this.pagination.per_page = parseInt(response.data.meta.per_page)
                this.loading_submit = false
            });


        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                ...this.form
            })
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
    }
}
</script>
