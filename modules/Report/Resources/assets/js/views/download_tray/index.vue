<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Bandeja de descargas</h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th class="">#</th>
                            <th class="">Formato</th>
                            <th class="">Fecha generación</th>
                            <th class="">Fecha proceso completado</th>
                            <th>Estado</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th class="text-center">Descarga</th>
                        </tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td> 
                            <td>{{row.format}}</td>
                            <td>{{row.date_init}}</td>
                            <td>{{row.date_end}}</td>
                            <td>{{row.status}}</td>
                            <td>{{row.user}}</td>
                            <td>{{row.type}}</td>
                            <td class="text-center">  
                                <el-button v-if="row.file_name" @click="clickDownload(row.id)">
                                    <i :class="row.format == 'pdf' ? 'fa fa-file-pdf' : 'fa fa-file-excel'"></i> 
                                    <span class="ml-1">Descargar archivo</span>
                                </el-button>
                            </td>
                        </tr>
                        
                    </data-table>
                     
                    
                </div> 
        </div>
 
    </div>
</template>

<script>
 
    import DataTable from '../../components/DataTableDownloadTray.vue'

    export default {
        components: {DataTable},
        data() {
            return {
                resource: 'reports/download-tray',                 
                form: {}, 

            }
        },
        async created() { 
        },
        methods: { 
            clickDownload(id) {
                window.open(`/reports/download-tray/download/${id}`, '_blank');
            },
        }
    }
</script>