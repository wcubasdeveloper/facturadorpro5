<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Consulta de guías por producto</h3>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Número</th>
                        <th>Estado</th>
                        <th class="text-center">Fecha Envío</th>
                        <th class="text-center">Orden de pedido</th>
                        <th class="text-center">Producto</th>
                        <th class="text-center">Cantidad</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.dispatches.date_of_issue }}</td>
                        <td>{{ row.dispatches.customer_name }} <br/> <small>{{ row.dispatches.customer_number }}</small>
                        </td>
                        <td>{{ row.dispatches.user_name }}</td>
                        <td>{{ row.dispatches.number }}</td>
                        <td>
                        <span
                            :class="{'bg-secondary': (row.dispatches.state_type_id === '01'),
                             'bg-info': (row.dispatches.state_type_id === '03'), 'bg-success': (row.dispatches.state_type_id === '05'), 'bg-secondary':
                              (row.dispatches.state_type_id === '07'), 'bg-dark': (row.dispatches.state_type_id === '09')}"
                            class="badge bg-secondary text-white">
                            {{ row.dispatches.state_type_description }}</span>
                        </td>
                        <td class="text-center">{{ row.dispatches.date_of_shipping }}</td>

                        <td class="text-center">{{ row.dispatches.order_form_description }}</td>

                        <td class="text-center">
                            {{ row.item.description }}
                        </td>
                        <td class="text-center">
                            {{ row.quantity }}
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
    </div>
</template>
<script>
import DataTable from './partials/DataTableGuide.vue'

export default {
    components: {DataTable},
    props: ['configuration'],
    data() {
        return {
            resource: 'reports/guides',
            form: {},
        }
    },
    async created() {
    },
    methods: {}
}
</script>
