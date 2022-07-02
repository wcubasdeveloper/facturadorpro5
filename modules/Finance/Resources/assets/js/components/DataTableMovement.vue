<template>
    <div>
        <div class="row">

            <div class="col-md-12 col-lg-12 col-xl-12 ">

                <div class="row mt-2">
                    <div class="col-md-3">
                        <label class="control-label">Periodo
                            <el-tooltip class="item" content="Filtra por fecha de pago" effect="dark"
                                        placement="top-start">
                                <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                        </label>
                        <el-select v-model="form.period" @change="changePeriod">
                            <el-option key="month" label="Por mes" value="month"></el-option>
                            <el-option key="between_months" label="Entre meses" value="between_months"></el-option>
                            <el-option key="date" label="Por fecha" value="date"></el-option>
                            <el-option key="between_dates" label="Entre fechas" value="between_dates"></el-option>
                        </el-select>
                    </div>
                    <template v-if="form.period === 'month' || form.period === 'between_months'">
                        <div class="col-md-3">
                            <label class="control-label">Mes de</label>
                            <el-date-picker v-model="form.month_start" :clearable="false"
                                            format="MM/yyyy"
                                            type="month" value-format="yyyy-MM" @change="changeDisabledMonths"></el-date-picker>
                        </div>
                    </template>
                    <template v-if="form.period === 'between_months'">
                        <div class="col-md-3">
                            <label class="control-label">Mes al</label>
                            <el-date-picker v-model="form.month_end" :clearable="false"
                                            :picker-options="pickerOptionsMonths"
                                            format="MM/yyyy" type="month" value-format="yyyy-MM"></el-date-picker>
                        </div>
                    </template>
                    <template v-if="form.period === 'date' || form.period === 'between_dates'">
                        <div class="col-md-3">
                            <label class="control-label">Fecha del</label>
                            <el-date-picker v-model="form.date_start" :clearable="false"
                                            format="dd/MM/yyyy"
                                            type="date" value-format="yyyy-MM-dd"
                                            @change="changeDisabledDates"></el-date-picker>
                        </div>
                    </template>
                    <template v-if="form.period === 'between_dates'">
                        <div class="col-md-3">
                            <label class="control-label">Fecha al</label>
                            <el-date-picker v-model="form.date_end" :clearable="false"
                                            :picker-options="pickerOptionsDates"
                                            format="dd/MM/yyyy" type="date"
                                            value-format="yyyy-MM-dd"></el-date-picker>
                        </div>
                    </template>

                    <div class="col-md-3 mt-4">
                        <el-checkbox v-model="form.last_cash_opening" @change="getRecordsByFilter">Última apertura de
                                                                                                   caja
                        </el-checkbox>
                    </div>

                    <div class="col-lg-7 col-md-7 col-md-7 col-sm-12" style="margin-top:29px">
                        <el-button :loading="loading_submit" class="submit" icon="el-icon-search"
                                   type="primary" @click.prevent="getRecordsByFilter">Buscar
                        </el-button>

                        <template v-if="records.length>0">

                            <!--<el-button :loading="loading_submit" class="submit" icon="el-icon-tickets" type="danger"
                                       @click.prevent="clickDownload('pdf')">Exportar PDF
                            </el-button>-->

                            <el-button :loading="loading_submit" class="submit" type="success"
                                       @click.prevent="clickDownload('excel')"><i class="fa fa-file-excel"></i> Exportar
                                                                                                                Excel
                            </el-button>

                        </template>

                    </div>

                </div>
                <div class="row mt-1 mb-4">

                </div>
            </div>


            <div class="col-md-12">

                <div class="pull-right">
                    <el-select v-model="per_page" @change="handleCurrentChange">
                        <el-option key="10" label="10" value="10"></el-option>
                        <el-option key="15" label="15" value="15"></el-option>
                        <el-option key="25" label="25" value="25"></el-option>
                        <el-option key="50" label="50" value="50"></el-option>
                        <el-option key="todos" label="Todos" value="todos"></el-option>
                    </el-select>
                </div>
                <div class="table-responsive">
                    <el-table
                        :data="currentTableData"
                        :default-sort="{prop: 'date_of_payment', order: 'ascending'}"
                        :summary-method="getSummaries"
                        show-summary
                        style="width: 100%"
                        @sort-change="changeTableSort">


                        <!--
                        <el-table-column
                            class="d-none"
                            label="#"
                            prop="index"

                        ></el-table-column>
                        -->

                        <el-table-column
                            label="Fecha"
                            prop="date_of_payment"
                            sortable
                            type="date"
                        >
                        </el-table-column>
                        <el-table-column
                            label="Adquiriente"
                            prop="person_name"
                            sortable>
                            <template slot-scope="scope">
                                <span>{{ scope.row.person_name }}
                                    <br/>
                                    <small>{{ scope.row.person_number }}</small>
                                    </span>

                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Documento/Transacción"
                            prop="document_type_description"
                            sortable>
                            <template slot-scope="scope">
                                <span>
                                    {{ scope.row.number_full }}<br/>
                            <small v-text="scope.row.document_type_description"></small>
                                    </span>

                            </template>
                        </el-table-column>
                        <el-table-column

                        >
                            <!-- sortable -->
                            <template slot="header" slot-scope="scope">
                                Detalle
                                <el-tooltip
                                    class="item"
                                    content="Aplica a Ingresos/Gastos"
                                    effect="dark"
                                    placement="top-start"
                                >
                                    <i class="fa fa-info-circle"></i>
                                </el-tooltip>
                            </template>
                            <template slot-scope="scope">
                                <div v-for="(item, index) in scope.row.items">
                                    <label :key="index">- {{ item.description }}<br/></label>
                                </div>

                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Moneda"
                            prop="currency_type_id"
                        >
                        </el-table-column>
                        <el-table-column
                            label="Destino"
                            prop="destination_name"
                            v-if="showDestination!== true"
                            sortable>
                        </el-table-column>
                        <el-table-column
                            label="Tipo"
                            prop="instance_type_description"
                            v-if="showDestination!== false"
                            sortable>
                        </el-table-column>
                        <el-table-column
                            :formatter="MonedaFormater"
                            label="Ingresos"
                            prop="input">
                        </el-table-column>
                        <el-table-column
                            :formatter="MonedaFormater"
                            label="Gastos"
                            prop="output">
                        </el-table-column>
                        <el-table-column
                            :formatter="MonedaFormater"
                            label="Saldo"
                            prop="balance">
                        </el-table-column>


                    </el-table>


                    <!--
                    <table class="table">
                        <thead>
                        <slot name="heading"></slot>
                        </thead>
                        <tbody>
                        <slot v-for="(row, index) in records" :index="customIndex(index)" :row="row"></slot>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="7"></td>
                            <td>S/{{ totals.total_input }}</td>
                            <td>S/{{ totals.total_output }}</td>
                            <td>S/{{ totals.total_balance }}</td>
                        </tr>
                        </tfoot>

                    </table>
                    -->
                    <div>
                        <!--v-if="showPagination" -->
                        <el-pagination

                            :current-page.sync="currentPage"
                            :page-size="itemsPerPage"
                            :total="records.length"
                            layout="total, prev, pager, next"
                            @current-change='handleCurrentChange'
                        >
                        </el-pagination>
                    </div>
                    <!--
                    <div>
                        <el-pagination
                                @current-change="getRecords()"
                                layout="total, prev, pager, next"
                                :total="pagination.total"
                                :current-page.sync="pagination.current_page"
                                :page-size="pagination.per_page">
                        </el-pagination>
                    </div>
                    -->
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
        filter: {
            type: Object,
            required: false,
            default: false
        },
        configuration: {
            type: Object,
            required: false,
            default: false
        },
        ismovements: {
            type: Number,
            required: false,
            default: 1
        },
        applyCustomer: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    data() {
        return {
            filterdata: {
                column: null,
                order: null
            },
            current_page: 1, // current page
            currentPage: 1, // current page
            per_page: 10,
            loading_submit: false,
            loading_search: false,
            links: {},
            columns: [],
            records: [],
            currentTableData: [],
            headers: headers_token,
            pagination: {},
            search: {},
            payment_types: [],
            destination_types: [],
            form: {},
            totals: {},
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
            sellers: [],
            config: {},
        }
    },
    computed: {
        showPagination: function () {
            if (this.per_page === 'todos') return false;

            if (this.records.length < this.currentTableData.length) {
                return false
            }
            if (this.records.length < this.per_page) {
                return false
            }
            return true
        },
        itemsPerPage: function () {
            if (this.per_page === 'todos') {
                return this.records.length
            }
            return this.per_page
        },
        showDestination:function(){
            return !(this.ismovements !== undefined && this.ismovements === 0);
        },
    },
    created() {
        if (this.configuration !== undefined && this.configuration !== null && this.configuration.length > 0) {
            this.$setStorage('configuration', this.configuration)
        }
        this.config = this.$getStorage('configuration');
//item_per_page
        this.initForm()
        this.initTotals()
        this.$eventHub.$on('reloadData', () => {
            this.getRecords()
        })
    },
    async mounted() {

        // await this.$http.get(`/${this.resource}/filter`)
        //     .then(response => {
        //         this.payment_types = response.data.payment_types;
        //         this.destination_types = response.data.destination_types;
        //     });


        await this.getRecords()

    },
    methods: {
        getSummaries(param) {
            const {columns, data} = param;
            const sums = [];
            columns.forEach((column, index) => {
                if (index < 6) {
                    sums[index] = '';
                    return;
                }

                const values = data.map(item => Number(item[column.property]));
                if (!values.every(value => isNaN(value))) {
                    let valor = values.reduce((prev, curr) => {
                        const value = Number(curr);
                        if (!isNaN(value)) {
                            return prev + curr;
                        } else {
                            return prev;
                        }
                    }, 0);

                    sums[index] = 'S/ ' + valor.toLocaleString('es')
                } else {
                    sums[index] = 'N/A';
                }
            });

            return sums;
        },
        DetailFormater: (row, col, value, index) => {
            let text = '';
            for (let i = 0; i < row.items.length; i++) {
                let item = row.items[i]
                text = text + ` <label>- {{ item.description }}<br/></label>`
            }

            return text;
        },
        DocumentFormater: (row, col, value, index) => {

            return row.number_full + '<br/> <small >' + row.document_type_description + '</small>';
        },
        personFormater: (row, col, value, index) => {
            return `${row.person_name}<br/><small>${row.person_number}</small> `;
        },
        MonedaFormater: (row, col, value, index) => {
            if (value === null) return '-';
            if (isNaN(parseFloat(value))) return '-';
            return `S/ ${value}`
        },
        handleCurrentChange() {
            this.currentTableData = this.records.slice(
                (this.currentPage - 1) * this.itemsPerPage,
                this.currentPage * this.itemsPerPage
            )
        },
        /*
        changeFilter() {
            if (this.filter.column !== undefined) {
                if (this.filter.order !== this.filter.order) {
                    this.filterdata = this.filter;
                    this.getRecords();
                }
            }
        },
        */
        initTotals() {

            this.totals = {
                total_input: 0,
                total_output: 0,
                total_balance: 0,
            }

        },
        newClickDownload(type) {
            let url = `/${this.resource}/${type}`;
            this.loading_submit = true;

            this.$http({
                url: url,
                data: {
                    data: this.records,
                    column: this.filterdata.column,
                    order: this.filterdata.order,
                    ismovements: this.ismovements,
                },
                method: 'post',
                responseType: 'blob', // important
            }).then((response) => {
                let fileName = 'Reporte_Movimientos_' + moment().format('YYYYMMDD_HHmmss')
                if (type == 'pdf') {
                    fileName += ".pdf"
                } else {
                    fileName += ".xlsx"
                }
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', fileName);
                document.body.appendChild(link);
                link.click();
            }).catch((error) => {
                console.error(error)
            }).finally(() => {
                this.loading_submit = false;
            });


        },
        clickDownload(type) {

            return this.$http.get(`/${this.resource}/${type}?${this.getQueryParameters()}`).then((response) => {

                if(response.data.success)
                {
                    this.$message.success(response.data.message)
                }
                else{
                    this.$message.error(response.data.message)
                }

            }).finally(() => {
                //this.getOtherData()
                //this.loading_submit = false
            });


            return this.newClickDownload(type);
            let query = queryString.stringify({
                ...this.form
            });
            window.open(`/${this.resource}/${type}?${query}`, '_blank');
        },
        initForm() {

            this.form = {
                payment_type: null,
                destination_type: null,
                period: 'month',
                date_start: moment().format('YYYY-MM-DD'),
                date_end: moment().format('YYYY-MM-DD'),
                month_start: moment().format('YYYY-MM'),
                month_end: moment().format('YYYY-MM'),
                last_cash_opening: false,
            }

        },
        customIndex(index) {
            return 1;
            // return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
        },
        async getRecordsByFilter() {

            this.loading_submit = await true
            await this.getRecords(true)
            this.loading_submit = await false

        },
        getRecords(init_current_page = false) {

            if (init_current_page) {
                // this.pagination.current_page = 1
                this.currentPage = 1
            }
            this.records = [];
            this.loading_submit = true;
            this.pagination.current_page = 0;
            return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`).then((response) => {
                this.records = response.data.data
                this.pagination = response.data.meta
                // this.pagination.per_page = parseInt(response.data.meta.per_page)
                this.records = this.records.filter((item, index, self) =>
                    index === self.findIndex((t) => (
                        t.id === item.id
                    ))
                )
                this.getTotals(response.data.data)
                this.currentTableData = this.records.slice(0, this.itemsPerPage)
            }).finally(() => {
                this.getOtherData()
                this.loading_submit = false
            });
        },
        reindex_array_keys(array, start) {
            var temp = [];
            start = typeof start == 'undefined' ? 0 : start;
            start = typeof start != 'number' ? 0 : start;
            for (var i in array) {
                array[i].index = parseInt(i) + 1;
                temp[start++] = array[i];
            }
            return temp;
        },
        async getOtherData() {
            this.pagination.current_page = this.pagination.current_page + 1;
            if (this.pagination.current_page <= this.pagination.last_page) {
                return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`)
                    .then((response) => {
                        let temp = [...this.records, ...response.data.data]
                        this.records = this.reindex_array_keys(temp);
                        this.pagination = response.data.meta
                    }).catch(() => {
                        // Si existe el error, habilita la busqueda
                        this.pagination.current_page = 0;
                        this.currentTableData = this.records.slice(0, this.itemsPerPage)
                    })
                    .finally(() => {
                        this.loading_submit = false;
                        this.getOtherData()
                    });
            } else {
                this.pagination.current_page = 0;
                this.loading_submit = false;
                this.currentTableData = this.currentTableData.filter((item, index, self) =>
                    index === self.findIndex((t) => (
                        t.id === item.id
                    ))
                )
                this.currentTableData = this.records.slice(0, this.itemsPerPage)
            }
        },
        getTotals(records) {

            this.initTotals()
            this.totals.total_input = _.round(_.sumBy(records, (row) => {
                return (row.input == '-') ? 0 : parseFloat(row.input)
            }), 2)
            this.totals.total_output = _.round(_.sumBy(records, (row) => {
                return (row.output == '-') ? 0 : parseFloat(row.output)
            }), 2)
            this.totals.total_balance = this.totals.total_input - this.totals.total_output

        },

        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                column: this.filterdata.column,
                order: this.filterdata.order,
                ismovements: this.ismovements,
                paginate: 1,
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
        changeTableSort(column) {
            var fieldName = column.prop;
            var sortingType = column.order;
            if (sortingType == null) {
                sortingType = 'ascending';
            }
            this.filterdata = {
                order: fieldName,
                prop: sortingType,
            };
            if (fieldName == "date_of_payment") {
                this.records.map(item => {
                    item.date_of_payment = moment(item.date_of_payment).valueOf();
                });
            }
            if (sortingType == "descending") {
                this.records = this.records.sort((a, b) => b[fieldName] - a[fieldName]);
            } else {
                this.records = this.records.sort((a, b) => a[fieldName] - b[fieldName]);
            }
            if (fieldName == "date_of_payment") {
                this.records.map(item => {
                    item.date_of_payment = moment(item.date_of_payment).format(
                        "YYYY-MM-DD HH:mm:ss"
                    );
                });
            }
            this.currentTableData = this.records.slice(
                (this.currentPage - 1) * this.itemsPerPage,
                this.currentPage * this.itemsPerPage
            )
        }
    }
}
</script>
