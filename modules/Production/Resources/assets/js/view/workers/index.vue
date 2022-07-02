<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <div class="right-wrapper pull-right"> 
                <button class="btn btn-custom btn-sm  mt-2 mr-2" type="button" @click.prevent="clickCreate()"><i
                    class="fa fa-plus-circle"></i> Nuevo
                </button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de {{ title }}</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">

                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombre</th>
                        <th class="text-center">Tipo de documento</th>
                        <th class="text-center">Número</th>
                        <th class="text-center">Fecha admisión</th>
                        <th >Cargo</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>
                        <td class="text-center">{{ row.document_type }}</td>
                        <td class="text-center">{{ row.number }}</td> 
                        <td class="text-center">{{ row.admission_date }}</td> 
                        <td >{{ row.occupation }}</td> 
                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                    <div>
                                        <button class="dropdown-item"
                                                @click.prevent="clickCreate(row.id)">Editar
                                        </button>
                                    </div>
                                    <button class="dropdown-item"
                                        @click.prevent="clickDelete(row.id)">Eliminar
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </data-table>
            </div>

            <worker-form :recordId="recordId"
                          :showDialog.sync="showDialog"></worker-form>


        </div>
    </div>
</template>

<script>

import WorkerForm from './form.vue'
import DataTable from '@components/DataTable.vue'
import {deletable} from '@mixins/deletable'

export default {
    mixins: [deletable],
    components: {WorkerForm, DataTable},
    data() {
        return {
            title: null,
            showDialog: false,
            resource: 'workers',
            recordId: null, 
        }
    },
    created() {
        this.title = 'Empleados'
    },
    methods: {
        clickCreate(recordId = null) {
            this.recordId = recordId
            this.showDialog = true
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },
    }
}
</script>
