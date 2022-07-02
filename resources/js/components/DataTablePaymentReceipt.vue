<template>
    <div>
        <div class="row ">

            <div class="col-md-12 col-lg-12 col-xl-12 ">
                <br>
                <br>
                <div v-if="applyFilter"
                     class="row">
                    <div class="col-12 pb-3">Filtrar por:</div>
                    <div class="col-lg-2 col-md-4 col-sm-12 pb-2">
                        <div class="d-flex">
                            <el-select v-model="search.column"
                                       placeholder="Select">
                                <el-option v-for="(label, key) in columns"
                                           :key="key"
                                           :label="label"
                                           :value="key"></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 pb-2">
                        <template v-if="search.column=='date_of_issue' || search.column=='date_of_due' || search.column=='date_of_payment'">
                            <el-date-picker
                                v-model="search.value"
                                placeholder="Buscar"
                                style="width: 100%;"
                                type="date"
                                value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </template>
                        <template v-else>
                            <el-input v-model="search.value"
                                      placeholder="Nombre del cliente"
                                      style="width: 100%;">
                            </el-input>
                        </template>
                    </div>
                    <div class="col-lg-2 col-md-2 form-group">
                        <el-select v-model="search.series"
                                   clearable
                                   filterable
                                   placeholder="Serie">
                            <el-option v-for="option in series"
                                       :key="option.number"
                                       :label="option.number"
                                       :value="option.number"></el-option>
                        </el-select>
                    </div>
                    <div class="col-lg-2 col-md-3 form-group">
                        <el-select v-model="search.total_canceled"
                                   clearable
                                   placeholder="Estado de pago">
                            <el-option :value="1"
                                       label="Pagado"></el-option>
                            <el-option :value="0"
                                       label="Pendiente"></el-option>
                        </el-select>
                    </div>
                    <!--
                    <div class="col-lg-2 col-md-2 form-group">
                        <el-input v-model="search.purchase_order"
                                  clearable
                                  placeholder="Orden de compra"></el-input>
                    </div>
                    -->
                    <div class="col-lg-1 col-md-2 form-group">
                        <el-button class="w-100"
                                   type="primary"
                                   @click="getRequestData">
                            <i class="fa fa-search"></i>
                        </el-button>
                    </div>
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

                    <div class="row mb-5">
                        <div class="col-md-4 text-center">Total recibo de pagos en soles S/. {{ totals.total_pen }}</div>
                        <div class="col-md-4 text-center">Total pagado en soles S/. {{ totals.total_paid_pen }}</div>
                        <div class="col-md-4 text-center">Total por cobrar en soles S/.
                                                          {{ totals.total_pending_paid_pen }}
                        </div>
                    </div>

                    <div>
                        <el-pagination
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page"
                            :total="pagination.total"
                            layout="total, prev, pager, next"
                            @current-change="getRequestData">
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>

import moment from 'moment'
import queryString from 'query-string'

export default {
    props: {
        resource: String,
        extraquery: {
            type: Object,
            default: {},
            required: false
        },
        applyFilter: {
            type: Boolean,
            default: true,
            required: false
        }
    },
    data() {
        return {
            search: {
                column: null,
                value: null,
                series: null,
                total_canceled: null
            },
            totals: {
                total_pen: 0,
                total_paid_pen: 0,
                total_pending_paid_pen: 0
            },
            columns: [],
            records: [],
            pagination: {},
            series: []
        }
    },
    computed: {},
    created() {
        this.$eventHub.$on('reloadData', () => {
            this.getRecords()
            this.getTotals()
        })
    },
    async mounted() {
        let column_resource = _.split(this.resource, '/')
        // console.log(column_resource)
        await this.$http.get(`/${_.head(column_resource)}/columns`).then((response) => {
            this.columns = response.data
            this.search.column = _.head(Object.keys(this.columns))
        });

        await this.$http.get(`/${_.head(column_resource)}/columns2`).then((response) => {
            this.series = response.data.series
        });


        await this.getRecords()
        await this.getTotals()
    },
    methods: {
        getTotals() {

            this.$http.get(`/${this.resource}/totals?${this.getQueryParameters()}`)
                .then((response) => {
                    this.totals = response.data
                });

        },
        customIndex(index) {
            return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
        },
        getRecords() {
            return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`).then((response) => {
                this.records = response.data.data
                this.pagination = response.data.meta
                this.pagination.per_page = parseInt(response.data.meta.per_page)
            });
        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                ...this.search,
                ...this.extraquery
            })
        },
        async changeClearInput() {
            this.search.value = ''
            await this.getRecords()
            await this.getTotals()

        },
        async getRequestData() {
            await this.getRecords()
            await this.getTotals()
        }
    }
}
</script>
