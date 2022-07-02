<template>
    <div v-loading="loading_submit">
        <div class="row ">
            <div class="col-md-12 col-lg-12 col-xl-12 ">
                <div v-if="applyFilter"
                     class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 pb-2">
                        <div class="d-flex">
                            <div style="width:100px">
                                Filtrar por:
                            </div>
                            <el-select
                                v-model="search.column"
                                placeholder="Select"
                                @change="changeClearInput"
                            >
                                <el-option
                                    v-for="(label, key) in columns"
                                    :key="key"
                                    :label="label"
                                    :value="key"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 pb-2">
                        <template
                            v-if="
                                search.column === 'date_of_issue' ||
                                    search.column === 'date_of_due' ||
                                    search.column === 'date_of_payment' ||
                                    search.column === 'delivery_date'
                            "
                        >
                            <el-date-picker
                                v-model="search.value"
                                placeholder="Buscar"
                                style="width: 100%;"
                                type="date"
                                value-format="yyyy-MM-dd"
                                @change="getRecords"
                            >
                            </el-date-picker>
                        </template>
                        <template v-else>
                            <el-input
                                v-model="search.value"
                                placeholder="Buscar"
                                prefix-icon="el-icon-search"
                                style="width: 100%;"
                                @input="getRecords"
                            >
                            </el-input>
                        </template>
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
                        <slot
                            v-for="(row, index) in table_data"
                            :index="customIndex(index)"
                            :row="row"
                        ></slot>
                        </tbody>
                    </table>
                    <div>
                        <el-pagination
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page"
                            :total="pagination.total"
                            layout="total, prev, pager, next"
                            @current-change="getRecords"
                        >
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import queryString from "query-string";
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props: {
        extraquery: {
            type: Object,
            default: {},
            required: false
        },
        productType: {
            type: String,
            required: false,
            default: ''
        },
        // resource: String,
        applyFilter: {
            type: Boolean,
            default: true,
            required: false
        },
        pharmacy: Boolean,
    },
    data() {
        return {
            search: {
                column: null,
                value: null
            },
            columns: [],
            records: [],
            pagination: {},
            loading_submit: false,
            fromPharmacy: false,
        };
    },
    created() {
        this.loadConfiguration();
        if (this.pharmacy !== undefined && this.pharmacy === true) {
            this.fromPharmacy = true;
        }
        this.$eventHub.$on("reloadData", () => {
            //  console.log('Dispara reloadData')
            this.getRecords();
        });
        this.$root.$refs.DataTable = this;
    },
    async mounted() {
        let column_resource = _.split(this.resource, "/");
        let url = _.head(column_resource);
        await this.$http
            .get(`/full_suscription/${url}/columns`)
            .then(response => {
                this.columns = response.data;
                this.search.column = _.head(Object.keys(this.columns));
            });
        await this.getRecords();
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        customIndex(index) {
            return ( this.pagination.per_page * (this.pagination.current_page - 1) + index + 1 );
        },
        getRecords() {
            this.loading_submit = true;
            let url = `/full_suscription/${this.resource}/records`;
            return this.$http
                .post(url,this.getQueryParameters())
                .then(response => {
                    this.records = response.data.data;
                    this.$store.commit('setTableData',this.records)
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
        getQueryParameters() {
            if (this.productType == 'ZZ') {
                this.search.type = 'ZZ';
            }
            return {
                page: this.pagination.current_page,
                limit: this.limit,
                isPharmacy: this.fromPharmacy,
                ...this.search,
                ...this.extraquery
            };
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                isPharmacy: this.fromPharmacy,
                ...this.search
            });
        },
        changeClearInput() {
            this.search.value = "";
            this.getRecords();
        },
        getSearch() {
            return this.search;
        }
    },

    computed: {
        ...mapState([
            'config',
            'table_data',
            'resource',
        ]),
    },
};
</script>
