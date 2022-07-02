<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Propinas 
            </h3>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Empleado</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Documento</th>
                        <th class="text-center">Estado</th>
                        <th>Tipo documento</th>
                        <th class="text-center">Total propina</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>  
                        <td>{{row.worker_full_name}}</td>
                        <td class="text-center">{{row.origin_date_of_issue}}</td>
                        <td class="text-center">{{row.document_number_full}}</td> 
                        <td class="text-center">{{row.state_type_description}}</td> 
                        <td>{{row.document_type_description}}</td> 
                        <td class="text-center">{{row.total}}</td> 
                    </tr>
                    
                </data-table>
            </div> 
        </div>
 
    </div>
</template>

<script>
 
    import DataTable from '../../components/DataTablePeriods.vue'

    export default { 
        components: {DataTable},
        data() {
            return {
                resource: 'reports/tips',                 
            }
        },
        async created() { 
        },
        methods: { 
            
        }
    }
</script>