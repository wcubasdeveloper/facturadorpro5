<template>
    <el-dialog :title="title"
               :visible="showDialog"
               @close="close"
               @open="getData">
        <div class="form-body">
            <div class="row">
                <div v-if="form.payments.length > 0"
                     class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Método de Prestamo Bancario</th>
                                <th>Destino</th>
                                <th>Referencia</th>
                                <th>Monto</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in form.payments"
                                :key="index">
                                <template v-if="row.id">
                                    <!-- <td>{{ row.date_of_payment }}</td> -->
                                    <td>{{ row.expense_method_type_description }}</td>
                                    <td>{{ row.destination_description }}</td>
                                    <td>{{ row.reference }}</td>
                                    <td>{{ row.payment }}</td>
                                </template>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="col-md-12 text-center pt-2" v-if="showAddButton && (document.total_difference > 0)">
                    <el-button type="primary" icon="el-icon-plus" @click="clickAddRow">Nuevo</el-button>
                </div> -->
            </div>
        </div>
    </el-dialog>

</template>

<script>

// import {deletable} from '../../../../mixins/deletable'

export default {
    props: ['showDialog', 'expenseId'],
    // mixins: [deletable],
    data() {
        return {
            title: null,
            resource: 'bank_loan',
            form: {},
            showAddButton: true,
            document: {}
        }
    },
    async created() {
        await this.initForm();
    },
    methods: {
        initForm() {
            this.form = {
                payments: []
            };
        },
        async getData() {
            this.initForm();
            await this.$http.get(`/${this.resource}/record/${this.expenseId}`)
                .then(response => {
                    this.form = response.data.data
                    this.title = 'Distribución de Prestamo Bancario: G-' + this.form.number;

                });
        },
        close() {
            this.$emit('update:showDialog', false);
        }
    }
}
</script>
