<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>
                        Listado de maquinas
                    </span>
                </li>
            </ol>
            <div class="right-wrapper pull-right pt-2">
                <a :href="`/machine-type-production/create`"
                   class="btn btn-custom btn-sm ">
                    <i class="fa fa-plus-circle"></i>
                    Nuevo tipo de maquina
                </a>
            </div>
            <div class="right-wrapper pull-right pt-2">
                <a :href="`/${resource}/create`"
                   class="btn btn-custom btn-sm ">
                    <i class="fa fa-plus-circle"></i>
                    Nueva maquina
                </a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo de maquinaria</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Fuerza de cierre</th>
                        <th></th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>

                        <td>
                            <div v-if="row.machine_type && row.machine_type.name">
                                {{ row.machine_type.name }}
                            </div>
                        </td>
                        <td>{{ row.brand }}</td>
                        <td>{{ row.model }}</td>
                        <td>{{ row.closing_force }}</td>
                        <td>
                            <button
                                class="btn waves-effect waves-light btn-xs btn-info"
                                type="button"
                                @click.prevent="clickCreate(row.id)">Editar
                            </button>

                        </td>

                    </tr>
                </data-table>
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

export default {
    components: {
        // DocumentPayments,
        // ExpenseVoided,
        // ExpensePayments,
        DataTable

    },
    data() {
        return {
            showDialogVoided: false,
            resource: 'machine-production',
            showDialogPayments: false,
            showDialogExpensePayments: false,
            recordId: null,
            showDialogOptions: false
        }
    },
    created() {
    },
    methods: {
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
