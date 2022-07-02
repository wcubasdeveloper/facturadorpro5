<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard">
                    <i class="fas fa-tachometer-alt">
                    </i>
                </a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>
                        Prestamos Bancarios
                    </span>
                </li>
            </ol>
            <div class="right-wrapper pull-right pt-2">
                <!--
                @todo Crear exportador
                <el-button class="submit"
                           type="success"
                           @click.prevent="clickDownload('excel')">
                    <i class="fa fa-file-excel">
                    </i> Exportar Excel
                </el-button>
                -->
                <a :href="`/${resource}/create`"
                   class="btn btn-custom btn-sm ">
                    <i class="fa fa-plus-circle">
                    </i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Banco</th>
                        <th>Número</th>
<!--                        <th>Motivo</th>-->
                        <th class="text-center">Pagos</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Dist. Prestamo Bancario</th>
                    <tr>
                    <tr slot-scope="{ index, row }"
                        :class="setClassToTable(row)">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.bank.description }}
                        </td>
                        <td>{{ row.number }}<br/>
                            <small v-text="row.payment_type_description">
                            </small>
                            <br/>
                        </td>
<!--                        <td class="">{{ row.expense_reason_description }}</td>-->
                        <td class="text-center">
                            <button
                                class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                style="min-width: 41px"
                                type="button"
                                @click.prevent="clickExpensePayment(row.id)"
                            >Pagos
                            </button>
                        </td>
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <td class="text-right">{{ row.total }}</td>

                        <td class="text-center">

                            <button v-if="row.state_type_id != '11'"
                                    class="btn waves-effect waves-light btn-xs btn-primary m-1__2"
                                    style="min-width: 41px"
                                    type="button"
                                    @click.prevent="clickCreate(row.id)">
                                <i class="fa fa-pen">
                                </i>
                            </button>

                            <!--
                            <button class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    style="min-width: 41px"
                                    type="button"
                                    @click.prevent="clickPayment(row.id)">
                                <i class="fa fa-search">
                                </i>
                            </button>
                            -->
                            <button v-if="row.state_type_id === '05'"
                                    class="btn waves-effect waves-light btn-xs btn-danger m-1__2"
                                    style="min-width: 41px"
                                    type="button"
                                    @click.prevent="clickVoided(row.id)">
                                <i class="fa fa-trash">
                                </i>
                            </button>
                        </td>

                    </tr>
                </data-table>
            </div>


            <document-payments :expenseId="recordId"
                               :showDialog.sync="showDialogPayments">
            </document-payments>
            <expense-voided :expenseId="recordId"
                            :showDialog.sync="showDialogVoided">
            </expense-voided>

            <loan-payments
                :expenseId="recordId"
                :external="true"
                :showDialog.sync="showDialogExpensePayments"
            >
            </loan-payments>
        </div>
    </div>

</template>

<script>

import DataTable from '@components/DataTable.vue'
import DocumentPayments from './partials/payments.vue'
import ExpenseVoided from './partials/voided.vue'
import LoanPayments from '@viewsModuleExpense/loan_payments/payments.vue'
import queryString from 'query-string'

export default {
    components: {
        DataTable,
        DocumentPayments,
        ExpenseVoided,
        LoanPayments
    },
    data() {
        return {
            showDialogVoided: false,
            resource: 'bank_loan',
            showDialogPayments: false,
            showDialogExpensePayments: false,
            recordId: null,
            showDialogOptions: false
        }
    },
    created() {
    },
    methods: {
        setClassToTable(row) {
            let text = 'text-danger'
            if (row.state_type_id === '11') {
                text = 'text-warning';
            } else if (row.state_type_id === '13') {
                text = 'border-light';
            } else if (row.state_type_id === '01') {
                text = 'border-left border-info';
            } else if (row.state_type_id === '03') {
                text = 'border-left border-success';
            } else if (row.state_type_id === '05') {
                text = 'border-left border-secondary';
            } else if (row.state_type_id === '07') {
                text = 'border-left border-dark';
            } else if (row.state_type_id === '09') {
                text = 'border-left border-danger';
            } else if (row.state_type_id === '11') {
                text = 'border-left border-warning';
            }
            return text;
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
    }
}
</script>
