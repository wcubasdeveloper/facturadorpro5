<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Ingreso de insumo</span>
                </li>
            </ol>
            <div class="right-wrapper pull-right pt-2">
                <!--
                <el-button class="submit"
                           type="success"
                           @click.prevent="clickDownload('excel')"><i class="fa fa-file-excel"></i> Exportar Excel
                </el-button>
                -->
                <a :href="`/${resource}/create`"
                   class="btn btn-custom btn-sm ">
                    <i class="fa fa-plus-circle"></i>
                    Nuevo
                </a>
            </div>
        </div>
        <div class="card mb-0">
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

                        <el-button class="submit"
                                   icon="el-icon-tickets"
                                   type="danger"
                                   @click.prevent="clickDownloadPdf()">Exportar PDF
                        </el-button>

                        <el-button class="submit"
                                   type="success"
                                   @click.prevent="clickDownloadExcel()"><i class="fa fa-file-excel"></i> Exportal Excel
                        </el-button>
                    </div>
                </div>
                <div class="col-12 table-responsive p-t-20">
                    <table class="table">
                    <thead>
                        <tr>

                            <th>#</th>
                            <th class="text-center">Número de Ficha</th>
                            <th>Fecha de inicio</th>
                            <th>Hora de inicio</th>
                            <th>Fecha de fin</th>
                            <th>Hora de fin</th>
                            <th>Insumos</th>
                            <th>Usuario</th>
                            <th>Comentario</th>
                            <!--                        <th class="text-right">Acciones</th>-->

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in records">
                            <td>{{ index + 1}}</td>

                            <td>{{ row.name }}</td>
                            <td>{{ row.date_start }}</td>
                            <td>{{ row.time_start }}</td>
                            <td>{{ row.date_end }}</td>
                            <td>{{ row.time_end }}</td>
                            <td>
                            <span
                                v-for="(m_item, index_item) in row.mill_items"
                                v-if=" row.mill_items"
                                :key="index_item">
                                {{ m_item.item_name }}  <small>{{ m_item.color }}</small> <br>
                            </span>
                            </td>
                            <td>{{ row.user }}</td>
                            <td>{{ row.comment }}</td>
                            <!--
        <td class="text-right">
         <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Ver</button>

         <template v-if="typeUser === 'admin'">
             <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
         </template>
                            </td> -->

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

            <!--
            <document-payments :showDialog.sync="showDialogPayments"
                               :expenseId="recordId"></document-payments>
            <expense-voided :showDialog.sync="showDialogVoided"
                            :expenseId="recordId"></expense-voided>

            <expense-payments
                :showDialog.sync="showDialogExpensePayments"
                :expenseId="recordId"
                :external="true"
            ></expense-payments>
            -->
        </div>
    </div>

</template>

<script>


import DataTable from '@components/DataTable.vue'
// import DocumentPayments from './partials/payments.vue'
// import ExpenseVoided from './partials/voided.vue'
// import ExpensePayments from '@viewsModuleExpense/expense_payments/payments.vue'
import queryString from 'query-string'
import moment from "moment";
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    components: {
        // DocumentPayments,
        // ExpenseVoided,
        // ExpensePayments,
        DataTable

    },
    computed: {
        ...mapState([
            'config',
        ]),
    },
    data() {
        return {
            showDialogVoided: false,
            resource: 'mill-production',
            loading_submit: false,
            showDialogPayments: false,
            showDialogExpensePayments: false,
            recordId: null,
            showDialogOptions: false,
            form: {},
            records: [],
            pagination: {},
        }
    },
    created() {
        this.loadConfiguration();
        this.initForm()
        this.getRecords()
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
        clickCreate(id = '') {
            location.href = `/${this.resource}/create/${id}`
        },
        clickExpensePayment(recordId) {
            this.recordId = recordId;
            this.showDialogExpensePayments = true
        },
        clickVoided(recordId) {
            this.recordId = recordId;
            this.showDialogVoided = true;
        },
        clickDownload(download) {
            let data = this.$root.$refs.DataTable.getSearch();
            let query = queryString.stringify({
                'column': data.column,
                'value': data.value
            });

            window.open(`/${this.resource}/report/excel/?${query}`, '_blank');
        },
        clickOptions(recordId = null) {
            this.recordId = recordId
            this.showDialogOptions = true
        },
        clickPayment(recordId) {
            this.recordId = recordId;
            this.showDialogPayments = true;
        },


        clickDownloadPdf() {
            window.open(`/${this.resource}/pdf?${this.getQueryParameters()}`, '_blank');
        },
        clickDownloadExcel() {
            window.open(`/${this.resource}/excel?${this.getQueryParameters()}`, '_blank');
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
